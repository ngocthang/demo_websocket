<?php
$host = 'localhost'; //host
$port = '9000'; //port
$null = NULL; //null var

//Tao socket TPP/IP
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//Port su dung lai
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//mo socket voi host truyen vao
socket_bind($socket, 0, $port);

//lang nghe tren port
socket_listen($socket);

//chuyen nhung port dang lang nghe thanh mang
$clients = array($socket);

//chay doan script khong bao gio ngung
while (true) {
	//quan ly nhieu ket noi
	$changed = $clients;
	//tra ve socket resource tu nhung client
	socket_select($changed, $null, $null, 0, 10);
	
	//kiem tra socket moi hay khong
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //dong y ket noi
		$clients[] = $socket_new; //add client vao mang
		
		$header = socket_read($socket_new, 1024); //don du lieu gui tu client moi
		perform_handshaking($header, $socket_new, $host, $port); //bat tay
		$ip = "";
		socket_getpeername($socket_new, $ip); //lay ip cua client
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' vừa vào chat room'))); //tao json thong bao co socket moi
		send_message($response); //gui message xuong client
		
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}
	
	//lap qua tat cac socket da ket noi
	foreach ($changed as $changed_socket) {	
		
		//kiem tra xem co du lieu gui len hay ko
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //giai ma du lieu
			$tst_msg = json_decode($received_text); //decode json
			$user_name = $tst_msg->name; //ten nguoi gui
			$user_message = $tst_msg->message; //message
			
			//tao json de gui den cac client khac
			$ip = "";
			socket_getpeername($changed_socket, $ip); //lay ip cua client
			$date = date('d/m/Y h:i:s A');
			$response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$ip, 'message'=>htmlentities($user_message), 'time' => $date)));
			$myFile = "log.txt";
			$fh = fopen($myFile, 'a') or die("can't open file");
			$stringData = "<div><span>".$date."</span> | <span class='user_name'>".$ip."</span> : <span class='user_message'>".htmlentities($user_message)."</span></div>";
			fwrite($fh, $stringData);
			fclose($fh);
			send_message($response_text); //gui du lieu
			break 2; //thoat khoi foreach
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // kiem tra client ngat ket noi
			// xoa client khoi mang $clients
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			
			//thong bao cho cac client con lai
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}
}
// dong ket noi
socket_close($sock);

function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}


//giai ma
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//ma hoa
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//bat tay
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}

	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//tra ve header cua viec bat tay
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}
?>