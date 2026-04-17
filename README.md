# IC3-Class: Hệ Thống Quản Lý Bảng Điểm Trực Tuyến

Dự án này là một hệ thống web nội bộ (chạy trên XAMPP) giúp giáo viên theo dõi điểm thi trực tuyến của học sinh (đến từ các file bài tập iSpring) một cách tự động theo đúng số máy (PC). Hệ thống nhận dữ liệu qua mạng LAN và cập nhật trên bảng điều khiển.

## 📥 Yêu Cầu Cài Đặt (Máy Chủ / Máy Giáo Viên)
- XAMPP (bản dành cho Windows có hỗ trợ thẻ PHP, ví dụ phiên bản 8.2+).
- iSpring Suite (để xuất bài giảng trắc nghiệm HTML5/EXE).
- Trình quản lý hệ thống này nằm trong thư mục `c:\xampp\htdocs\ic3-class`.

## 📦 Tải Phần Mềm Liên Quan
Các phần mềm bổ trợ cần thiết (XAMPP, iSpring) hoặc các tool khác có thể được tải xuống từ link sau:
- [Thư mục phần mềm cài đặt (Google Drive)](https://drive.google.com/drive/u/4/folders/1r4QKR8xHMbaaOMF6-VfbuJT00fT6m2zH)

---

## 🚀 Hướng Dẫn Cài Đặt Ban Đầu

### Bước 1: Thiết Lập Nền Tảng Và Mạng
1. **Cài đặt XAMPP**: Tải xuống và cài đặt XAMPP vào ổ đĩa. Khởi động **XAMPP Control Panel** và nhấn "Start" cho dịch vụ **Apache**.
2. **Cấu hình IP Tĩnh (Set Static IP)** cho máy giáo viên:
   - Nhấn phím `Windows + R`, gõ `ncpa.cpl` rồi `Enter`.
   - Chuột phải vào mạng đang dùng (Ethernet hoặc Wi-Fi) -> Chọn `Properties`.
   - Chọn `Internet Protocol Version 4 (TCP/IPv4)` -> Bấm nút `Properties`.
   - Tích vào `Use the following IP address` và điền thông số IP tĩnh (Ví dụ: IP `192.168.50.215`). 
   - Nhấn OK và Close.
3. **Mở cổng Firewall (Cổng 80) để nhận điểm**:
   - Nhấn Windows, tìm và mở `Windows Defender Firewall with Advanced Security`.
   - Ở cột bên trái, chọn `Inbound Rules`. Ở cột bên phải, chọn `New Rule...`
   - Chọn `Port` -> `Next`.
   - Chọn `TCP`, tại ô *Specific local ports* gõ `80` -> `Next`.
   - Chọn `Allow the connection` -> `Next`.
   - Tích cả 3 ô (Domain, Private, Public) -> `Next`.
   - Đặt tên (VD: `XAMPP_HTTP`) rồi nhấn Finish.

### Bước 2: Cấu Trúc Mã Nguồn (Code)
Sao chép thư mục dự án `ic3-class` vào đường dẫn `C:\xampp\htdocs\`.
Cấu trúc các file chính:
- `save_score.php`: Nhận điểm từ iSpring gửi về (ưu tiên nhận diện qua IP).
- `get_ip.php`: Chức năng nhận diện máy học sinh và tự động cập nhật sơ đồ IP.
- `api.php`: API cung cấp dữ liệu cho bảng điều khiển Dashboard.
- `quanly.php`: Giao diện bảng điểm trực tuyến hiển thị cho giáo viên.
- `ip_mapping.txt`: Lưu danh sách ánh xạ giữa IP và Tên/Số máy.
- Thư mục `danhsach/`: Chứa các danh sách học sinh theo lớp (Ví dụ: `danhsach_7.1.txt`).

### Bước 3: Cấp Quyền Đọc/Ghi (Phân Quyền Folder)
Để tránh lỗi "Read-only" không thể ghi file điểm và file cấu hình, hãy Mở `CMD` bằng quyền **Administrator** và chạy lệnh:
```cmd
icacls "C:\xampp\htdocs\ic3-class" /grant Everyone:(OI)(CI)F /T
```
*(Nếu cài bằng máy thường: Tắt "Read Only" của folder `ic3-class`, sang tab `Security` của folder, chọn `Edit` -> `Add` tài khoản `Everyone` và cấp quyền `Full control`).*

### Bước 4: Cấu Hình Tùy Chọn iSpring 
Mỗi khi xuất bản bài kiểm tra, bạn cần chọn tùy chọn gửi điểm vào Server:
- Trong phần *Publish* của iSpring, chọn tab **Reporting**.
- Tích chọn `Report to server`.
- Tại **URL**, điền đường dẫn của máy thầy: `http://[IP_MAY_THAY]/ic3-class/save_score.php` (Thay `[IP_MAY_THAY]` bằng dãy IP tĩnh của máy tính giáo viên. VD: `http://192.168.50.215/ic3-class/save_score.php`).

### Bước 5: Cấu Hình Shortcut (Tiện Ích Giáo Viên)
- Chuột phải ở Desktop -> `New` -> `Shortcut`.
- Tại ô *Type location of the item*, dán đường dẫn: `http://localhost/ic3-class/index.php`.
- Đặt tên lối tắt ví dụ `QuanLyDiem_IC3`. Khi nào cần theo dõi, chỉ cần chạy shortcut này.

### Bước 6: Xuất File Trắc Nghiệm iSpring Cho Học Sinh
- Mở file `.quiz` bài tập.
- Chọn ngân hàng câu hỏi thích hợp (Select random question).
- Publish bài học ra dạng HTML/EXE. Đổi tên bài kiểm tra để dễ quản lý. Gửi file xuống máy học sinh.

---

## 🛠 Hướng Dẫn Sử Dụng Trong Lớp Học

1. **Khởi động**: Máy giáo viên bắt buộc mở **XAMPP** và Start **Apache**.
2. **Mở Bảng Điều Khiển**: Nhấp vào Shortcut mới tạo hoặc truy cập `http://localhost/ic3-class/index.php` trong trình duyệt.
3. **Quản Lý Lớp**:
   - Màn hình đầu tiên sẽ yêu cầu bạn chọn **Tên Lớp** giảng dạy (Khối 6, Khối 7, Khối 8).
   - Chọn xong hệ thống sẽ tải màn hình Giám sát lớp học.
   - Bên tay trái là khu vực quản lý **Danh sách lớp**. Bạn có thể sao chép / dán danh sách học sinh từ Excel vào học sinh (để hiển thị đúng tên với từng PC) -> Nhấn *Lưu Danh Sách*.
   - Có thể click **Xóa dữ liệu điểm** nếu là một buổi làm bài mới.
4. **Giám Sát**:
   - Khi học sinh bấm nộp bài thực hành dạng iSpring ở dưới máy con, dữ liệu điểm sẽ được gửi trực tiếp đến Server và cập nhật *realtime* lên Bảng điều khiển (cập nhật mới sau mỗi 5 giây).
   - Điểm số cao nhất và số lần làm bài cùng thời gian hoàn thành sẽ được ghi nhận.
5. **Xuất Điểm**: Sau giờ học, bấm vào *Xuất File Excel* ở menu bên trái để tải bảng tổng kết điểm về máy.

## 🤝 Ghi chú
Nếu hệ thống bị sai sơ đồ số máy (có bạn ngồi khác máy tính quy định), có thể thiết lập học sinh chạy lại file `lay_ip.bat` để Server cập nhật lại ánh xạ IP hiện trường của phòng Tin Học. Vui lòng đảm bảo các client machine gửi đúng định dạng `IP_MAY` về `get_ip.php`.
