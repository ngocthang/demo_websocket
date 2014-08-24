<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
</head>
<body>	

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script language="javascript" type="text/javascript">  
$(document).ready(function(){
    //tao doi tuong websocket
    var wsUri = "ws://localhost:9000/demo_websocket/server.php";   
    wsocket = new WebSocket(wsUri); 
    
    //khi ket noi duoc mo
    wsocket.onopen = function(ev) {
        $('#msgbox').append("<div>Đã kết nối</div>");
    }

    $('#message').keypress(function(e) {
        if (e.keyCode == 13 ) {
            $('#sendbtn').click();
        }
    })

    $('#sendbtn').click(function(){
        var msg = $('#message').val(); 
        
        if(msg == ""){ //chua nhap message
            alert("Vui lòng nhập thông điệp");
            return;
        }
        
        //tao doi tuong JSON
        var msg = {
            message: msg,
            name: name,
        };
        //gui chuoi json len server
        wsocket.send(JSON.stringify(msg));
        $('#message').val(''); //reset text
    });
    
    //khi nhan dc message tu sever
    wsocket.onmessage = function(ev) {
        var msg = JSON.parse(ev.data); //chuyen chuoi JSON thanh doi tuong JSON
        var type = msg.type; //kieu message
        var umsg = msg.message; //message
        var uname = msg.name; //ten
        var time = msg.time;

        if(type == 'usermsg') //neu la message cua user
        {
            $('#msgbox').append("<div><span>"+time+"</span> | <span class='user_name'>"+uname+"</span> : <span class='user_message'>"+umsg+"</span></div>");
        }
        if(type == 'system') //neu la message cua he thong
        {
            $('#msgbox').append("<div class='msgsys'>"+umsg+"</div>");
        }
        
        $('#msgbox').scrollTop($('#msgbox')[0].scrollHeight);
    };
    
    wsocket.onerror   = function(ev){$('#msgbox').append("<div class='errorsys'>Co loi xay ra - "+ev.data+"</div>");}; 
    wsocket.onclose   = function(ev){$('#msgbox').append("<div class='msgsys'>Ngat ket noi</div>");}; 
});
</script>
<div class="chat_wrapper">
	<div class="message_box" id="msgbox">
    </div>
	<div class="panel">
		<input type="text" name="message" id="message" placeholder="Thông điệp" maxlength="80" style="width:60%" />
		<button id="sendbtn">Send</button>
	</div>
</div>

</body>
</html>
