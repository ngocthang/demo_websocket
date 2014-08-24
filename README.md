demo_websocket
==============

demo websocket

<h2>HTML5 Websocket</h2>

Websocket là một chức năng mới của HTML5, được thực hiện để tạo kết nối giữa 
client và server qua một port nào đó. Tất cả dữ liệu giao tiếp giữa 
client-server sẽ được gửi trực tiếp qua port này thay vì request HTTP bình 
thường, làm cho thông tin được gửi đi nhanh chóng và liên tục khi cần thiết.

Ứng dụng dưới đây sử dụng jquery làm client, phía server viết bằng PHP và chạy 
bằng localhost.

<h3>Phía client</h3>

- Mở kết nối <b>new Websocket(“ws://webserver_url”);</b>

set các event phục vụ business của ứng dụng web chat.

WebSocket(wsUri) — tạo đối tượng websocket mới<br />
.onopen — event được gọi khi bắt đầu mở kết nối đến server<br />
.onclose — event được gọi khi ngắt kết nối đến server<br />
.onmessage — event được gọi khi nhận được message gửi từ server về<br />
.onerror — event được gọi khi có lỗi mạng xảy ra.<br />
.send(message) — gửi message đến server<br />
.close() — ngắt kết nối<br />

<h3>Phía server</h3>
-Quy trình xử lý của websocket server:<br />
-Mở socket<br />
-Ràng buộc vào IP, domain<br />
-Lắng nghe kết nối đến<br />
-Chấp nhận kết nối<br />
-WebSocket Handshake.<br />
-Giải mã/ mã hóa frame gửi nhận<br />
-Xử lý thông tin<br />
-Ngắt kết nối<br />

<h3>Chạy websocket server: </h3>
<b>php -q \pathtoserver\server.php</h3>
