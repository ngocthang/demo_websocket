demo_websocket
==============


<h2>HTML5 Websocket</h2>

Websocket là một chức năng mới của HTML5, được thực hiện để tạo kết nối giữa 
client và server qua một port nào đó. Tất cả dữ liệu giao tiếp giữa 
client-server sẽ được gửi trực tiếp qua port này thay vì request HTTP bình 
thường, làm cho thông tin được gửi đi nhanh chóng và liên tục khi cần thiết.<br 
/>

- WebSoket hỗ trợ giao tiếp hai chiều giữa client và server bằng cách sử dụng 
  một TCP socket để tạo một kết nối hiệu quả và ít tốn kém. Mặc dù được thiết kế 
  để chuyên sử dụng cho các ứng dụng web, lập trình viên vẫn có thể đưa chúng 
  vào bất kì loại ứng dụng nào.<br />

- Dữ liệu truyền tải thông qua giao thức HTTP (thường dùng với kĩ thuật Ajax) 
  chứa nhiều dữ liệu không cần thiết trong phần header. Một header 
  request/response của HTTP có kích thước khoảng 871 byte, trong khi với 
  WebSocket, kích thước này chỉ là 2 byte (sau khi đã kết nối). <br />

- Vậy giả sử làm một ứng dụng game có thể tới 10,000 người chơi đăng nhập cùng 
lúc, và mỗi giây họ sẽ gửi/nhận dữ liệu từ server. Hãy so sánh lượng dữ liệu 
header mà giao thức HTTP và WebSocket trong mỗi giây:<br />
– HTTP: 871 x 10,000 = 8,710,000 bytes = 69,680,000 bits per second (66 Mbps)<br 
  />
  – WebSocket: 2 x 10,000 = 20,000 bytes = 160,000 bits per second (0.153 Kbps) 
  <br />
  Chỉ riêng phần header thôi cũng đã chiếm một phần lưu lượng đáng kể với giao 
  thức HTTP truyền thống.<br />

  - Khuyết điểm lớn nhất websocket là không hỗ trợ các trình duyệt cũ <br />

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
