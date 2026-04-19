# ☕ Hệ Thống Quản Lý Coffee Shop (Coffee Shop POS & Management)

## 📖 Mô tả dự án
Hệ thống Quản lý Coffee Shop là một ứng dụng web toàn diện được phát triển theo mô hình MVC. Dự án không chỉ dừng lại ở việc quản lý thông tin cơ bản mà còn cung cấp một hệ sinh thái khép kín cho quán cà phê, bao gồm: Quản trị viên (Admin), Thu ngân (POS), Bếp/Pha chế (KDS) và Khách hàng (Customer QR Menu). 

Hệ thống được thiết kế với giao diện chuẩn SaaS, tối ưu hóa trải nghiệm người dùng (UX/UI) và hỗ trợ Dark/Light Mode.

## 🚀 Công nghệ sử dụng
* **Backend:** PHP (Mô hình kiến trúc MVC thuần)
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla/ES6)
* **CSS Framework:** Bootstrap 5
* **Database:** MySQL
* **Thư viện hỗ trợ:** * Chart.js (Vẽ biểu đồ thống kê)
  * AOS Animation (Hiệu ứng chuyển động)
  * FontAwesome (Icons)

## ✨ Các tính năng nổi bật đã hoàn thiện
1. **Giao diện Quản trị (Admin Dashboard):**
   - Thống kê trực quan bằng biểu đồ (Doanh thu, Đơn hàng, Tồn kho).
   - Sidebar gom nhóm thông minh, Topbar có chuông thông báo.
   - Hỗ trợ chuyển đổi giao diện Tối/Sáng (Dark/Light Mode) lưu trạng thái.

2. **Quầy Thu Ngân (POS):**
   - Sơ đồ không gian quán (Table Map) phong cách Neon hiện đại.
   - Bàn phím số ảo (Numpad) hỗ trợ thao tác chạm nhanh.
   - Xử lý giỏ hàng, giảm giá, tính tiền thừa tự động.

3. **Màn hình Pha Chế (Kitchen Display System - KDS):**
   - Tự động lấy đơn hàng mới theo thời gian thực (AJAX Polling).
   - Âm thanh thông báo khi có đơn mới.
   - Quản lý trạng thái "Đang làm" và "Trả món".

4. **Thực đơn Khách Hàng (Customer QR Menu):**
   - Thiết kế Mobile-First dạng App tiện lợi.
   - Giỏ hàng nổi (Offcanvas Bottom) và hiệu ứng Popup mượt mà.

5. **Quản lý Kho (Inventory):**
   - Theo dõi xuất/nhập/tồn kho.
   - **Phiếu kiểm kho:** Tự động tính toán độ lệch giữa tồn hệ thống và thực tế.

---

## 📋 Danh sách công việc cần thực hiện (To-Do List)

### Giai đoạn 1: Khởi tạo & Cấu hình cơ sở
- [x] Thiết kế Database (Tables: Users, Products, Orders, Order_Details, Tables, Inventory).
- [x] Xây dựng cấu trúc thư mục MVC (Core, Controllers, Models, Views).
- [x] Tích hợp Bootstrap 5 và các thư viện cần thiết.
- [x] Hoàn thiện chức năng Đăng nhập / Đăng xuất và phân quyền (Admin/Staff).

### Giai đoạn 2: Phát triển Core Modules
- [x] Xây dựng CRUD cho Quản lý Sản phẩm (Thực đơn).
- [x] Xây dựng CRUD cho Quản lý Nhân sự.
- [x] Thiết kế giao diện Dashboard Admin với Chart.js.
- [x] Tích hợp tính năng Dark Mode / Light Mode cho toàn hệ thống.

### Giai đoạn 3: Module Bán hàng (POS) & Khách hàng
- [x] Xây dựng giao diện POS chia đôi màn hình (Sản phẩm & Giỏ hàng).
- [x] Code logic xử lý thêm/sửa/xóa giỏ hàng bằng JavaScript.
- [x] Tích hợp Bàn phím số ảo (Numpad) cho Thu ngân.
- [x] Vẽ Sơ đồ bàn (Floor Plan) với trạng thái Trống/Có khách.
- [x] Phát triển giao diện Customer Menu (Mobile-First) cho khách quét QR.

### Giai đoạn 4: Module Bếp (KDS) & Kho hàng
- [x] Tạo giao diện KDS nền tối cho khu vực pha chế.
- [x] Viết logic AJAX lấy đơn hàng liên tục không cần reload.
- [x] Thêm âm thanh thông báo (Ting ting) khi có đơn mới.
- [x] Xây dựng màn hình Báo cáo Nhập-Xuất-Tồn.
- [x] Xây dựng chức năng Phiếu Kiểm Kho tự động tính lệch.

### Giai đoạn 5: Tối ưu & Nâng cao (Dự kiến)
- [ ] Định dạng khổ giấy và viết logic In Hóa Đơn nhiệt (80mm).
- [ ] Thêm chức năng xuất báo cáo doanh thu ra file Excel.
- [ ] Thiết lập trang Cài đặt (Settings) để thay đổi Tên quán, Logo, % Thuế VAT.
- [ ] Test toàn diện các luồng dữ liệu và fix bugs.

---

## 👨‍💻 Cài đặt & Chạy dự án
1. Clone repository này về máy.
2. Đặt thư mục dự án vào `htdocs` (nếu dùng XAMPP) hoặc `www` (nếu dùng WAMP).
3. Import file database `coffee_shop.sql` vào phpMyAdmin.
4. Mở file `app/config/config.php` và cấu hình lại thông số kết nối Database (DB_USER, DB_PASS, URLROOT) cho phù hợp với máy của bạn.
5. Truy cập `http://localhost/coffee_shop` để trải nghiệm.




admin va staff: http://localhost:8080/coffee_shop
giao dien khach: http://localhost:8080/coffee_shop/order/menu/1