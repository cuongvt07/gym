# PROJECT SPECIFICATION: JOE FITNESS CENTER MANAGEMENT SYSTEM (V2.0)

## 1. TỔNG QUAN DỰ ÁN
* **Tên dự án:** Hệ thống Quản lý Trung tâm Thể hình Joe Fitness Center
* **Mục tiêu:** Quản lý PT, khách hàng, lịch tập, theo dõi tiến độ, điểm danh và chấm công
* **Địa chỉ:** Số 1 Lê Hồng Phong, Phường Ngô Quyền, TP. Hải Phòng
* **Tech Stack:** Laravel + Blade, MySQL, MVC

**Đặc điểm hệ thống:**
- Gói tập chỉ tính theo **SỐ BUỔI** (10, 20, 30 buổi...)
- Mỗi buổi tập phải có **PT hướng dẫn**
- Điểm danh dựa trên **đăng nhập vào hệ thống** (không dùng QR code)
- Chấm công PT tự động từ **buổi học hoàn thành**
- Thanh toán chỉ hỗ trợ **tiền mặt**
- Phân quyền đơn giản dựa trên **role** (admin/pt/user)
- **Tất cả role hoạt động trên 1 hệ thống thống nhất** (không tách trang riêng)

---

## 2. PHÂN QUYỀN (3 ROLES)

### A. Admin (Quản trị viên)
**Quyền hạn:**
- Quản lý PT (thêm/sửa/xóa, xem lịch dạy, duyệt chấm công, tính lương)
- Quản lý khách hàng (đăng ký mới, gia hạn, xem thông tin, theo dõi tiến độ)
- Quản lý gói tập (CRUD gói tập)
- Quản lý lịch tập (xếp lịch, hủy lịch)
- Điểm danh học viên (check-in/out)
- Quản lý thanh toán (thu tiền, xem lịch sử)
- Xem báo cáo và thống kê
- Xử lý phản hồi khách hàng

### B. PT (Personal Trainer)
**Quyền hạn:**
- Xem lịch dạy cá nhân
- Quản lý học viên được phân công (xem danh sách, theo dõi tiến độ)
- Ghi chỉ số cơ thể học viên
- Tạo và quản lý giáo án tập luyện
- Quản lý thư viện bài tập (CRUD bài tập)
- Xem lịch sử điểm danh học viên
- Chấm công buổi dạy

### C. User (Khách hàng/Học viên)
**Quyền hạn:**
- Xem thông tin cá nhân và mã thẻ
- Xem danh sách gói tập và PT
- Xem lịch tập cá nhân
- Xem tiến độ (chỉ số cơ thể, giáo án tập)
- Xem lịch sử điểm danh
- Xem lịch sử thanh toán
- Gửi phản hồi/khiếu nại

---

## 3. CHỨC NĂNG CHI TIẾT

### 3.1. MODULE ADMIN

#### 3.1.1. Dashboard
- Tổng quan: Doanh thu ngày/tháng, Số khách hàng mới, Số học viên đang hoạt động, Số PT
- Biểu đồ: Xu hướng đăng ký gói tập
- Cảnh báo: Gói tập sắp hết hạn (7 ngày)

#### 3.1.2. Quản lý PT
**Danh sách PT:**
- Filter: Trạng thái (Hoạt động/Nghỉ việc/Khóa)
- Search: Tên, Email, SĐT
- Hiển thị: Avatar, Họ tên, Email, SĐT, Chuyên môn, Trạng thái
- Actions: Xem chi tiết, Sửa, Khóa/Mở khóa

**Thêm/Sửa PT:**
- Thông tin cơ bản: Họ tên, Email (unique), SĐT, Password, Ngày sinh, Giới tính, Avatar
- Thông tin công việc: Lương cơ bản, Lương/giờ, Số giờ tiêu chuẩn/tháng
- Chuyên môn: Kinh nghiệm, Chứng chỉ, Chuyên môn (Yoga, Gym, Boxing...)
- Role: Tự động set là 'pt'

**Lịch dạy của PT:**
- Xem lịch theo: Ngày/Tuần/Tháng
- Filter: Trạng thái (Đã xếp/Đã học/Hủy/Vắng mặt)
- Hiển thị: Ngày, Giờ, Học viên, Loại buổi, Trạng thái

**Chấm công PT:**
- Xem bảng chấm công theo tháng
- Hiển thị: Ngày, Giờ bắt đầu-kết thúc, Số giờ, Loại công, Trạng thái duyệt
- Action: Duyệt công (Chưa duyệt → Đã duyệt)
- Tổng kết: Tổng giờ làm, Tổng lương = (Số giờ × Lương/giờ) + Lương cơ bản

#### 3.1.3. Quản lý Khách hàng
**Danh sách khách hàng:**
- Filter: Trạng thái thẻ (Hoạt động/Khóa/Hết hạn), Loại gói
- Search: Mã thẻ, Tên, SĐT, Email
- Hiển thị: Mã thẻ, Avatar, Họ tên, SĐT, Gói hiện tại, Ngày hết hạn, Trạng thái thẻ
- Actions: Xem chi tiết, Gia hạn, Khóa thẻ, Điểm danh

**Đăng ký khách hàng mới:**
- Step 1: Nhập thông tin cá nhân (Họ tên, Email, SĐT, Password, Ngày sinh, Giới tính)
- Step 2: Chụp ảnh thẻ → Upload avatar
- Step 3: Chọn gói tập (theo số buổi)
- Step 4: Chọn PT phụ trách
- Step 5: Thanh toán tiền mặt → Tạo bản ghi thanh toán
- Step 6: Tạo mã thẻ (Auto: KH + timestamp)

**Lưu ý:** 
- `tong_buoi = buoi_con_lai = so_buoi` của gói tập
- `buoi_da_tap = 0` ban đầu

**Chi tiết khách hàng (Tabs):**
1. **Thông tin cá nhân:** Họ tên, Email, SĐT, Ngày sinh, Giới tính, Mã thẻ, Trạng thái
2. **Gói tập hiện tại:** Tên gói, Số buổi, Ngày đăng ký, PT phụ trách, Buổi đã tập, Buổi còn lại, Trạng thái
3. **Lịch sử đăng ký:** Bảng các gói đã đăng ký (Tên gói, Số buổi, Ngày đăng ký, PT, Trạng thái)
4. **Lịch sử thanh toán:** Ngày, Số tiền, Phương thức, Người thu
5. **Lịch sử điểm danh:** Ngày, Giờ check-in/out, Lịch tập
6. **Chỉ số cơ thể:** 
   - Biểu đồ line chart: Cân nặng, BMI, Khối cơ theo thời gian
   - Bảng chi tiết: Ngày đo, Các chỉ số, PT đo, Ghi chú
7. **Giáo án tập:** Danh sách giáo án (Tên, PT tạo, Trạng thái)
8. **Phản hồi:** Danh sách phản hồi (Loại, Nội dung, Trạng thái)

**Gia hạn gói:**
- Chọn khách hàng → Chọn gói mới (theo số buổi)
- Chọn PT phụ trách
- Thanh toán → Tạo đăng ký mới
- Cập nhật trạng thái thẻ = 'hoat_dong'

**Khóa/Mở thẻ:**
- Khóa thẻ: trang_thai_the = 'khoa' (Không thể check-in)
- Mở thẻ: trang_thai_the = 'hoat_dong'

**Điểm danh:**
- Khách hàng đăng nhập vào hệ thống
- Kiểm tra: Thẻ hoạt động + Có lịch tập hôm nay
- Check-in: Ghi giờ vào, liên kết với lịch tập
- Check-out: Ghi giờ ra
- Tự động cập nhật trạng thái lịch tập = 'da_hoc'
- Tự động trừ buổi: `buoi_da_tap += 1`, `buoi_con_lai -= 1`

#### 3.1.4. Quản lý Gói tập
**Danh sách gói:**
- Filter: Trạng thái (Hoạt động/Tạm ngưng)
- Hiển thị: Tên gói, Số buổi, Giá, Trạng thái
- Actions: Sửa, Tạm ngưng/Kích hoạt

**Thêm/Sửa gói:**
- Tên gói: VD: "Gói 10 buổi", "Gói 20 buổi", "Gói 30 buổi"
- Số buổi: 10, 20, 30, 50... (Chỉ tính theo buổi)
- Giá tiền
- Mô tả chi tiết
- Trạng thái: Hoạt động/Tạm ngưng

**Lưu ý:**
- Tất cả gói đều tính theo số buổi
- Mỗi buổi phải có PT hướng dẫn
- Không có phân loại VIP/Thường hay Tập tự do/PT cá nhân

#### 3.1.5. Quản lý Lịch tập
**Xem lịch tổng quan:**
- View: Ngày/Tuần/Tháng (Calendar)
- Filter: PT, Khách hàng, Trạng thái
- Hiển thị: Time slot → PT, Học viên, Trạng thái

**Xếp lịch mới:**
- Chọn học viên (Chỉ hiển thị học viên có gói còn buổi > 0)
- Chọn PT
- Chọn ngày và giờ bắt đầu-kết thúc
- Ghi chú
- Validate: 
  - PT không trùng lịch
  - Học viên còn buổi (`buoi_con_lai > 0`)

**Quản lý buổi học:**
- Hủy lịch: trang_thai = 'huy' (Không trừ buổi)
- Đánh dấu vắng mặt: trang_thai = 'vang_mat' (Trừ buổi)
- Xác nhận đã học: trang_thai = 'da_hoc' (Tự động khi điểm danh)

#### 3.1.6. Thanh toán & Tài chính
**Thu tiền (POS):**
- Chọn khách hàng
- Chọn gói tập → Hiển thị giá
- Phương thức: **Chỉ tiền mặt**
- Nhập số tiền → Tạo đăng ký gói + Thanh toán
- In hóa đơn (PDF)

**Lịch sử giao dịch:**
- Filter: Ngày, Người thu
- Search: Mã giao dịch, Tên khách hàng
- Hiển thị: Ngày, Khách hàng, Gói, Số tiền (tiền mặt), Người thu
- Xuất Excel

**Báo cáo doanh thu:**
- Biểu đồ: Doanh thu theo ngày/tháng/năm
- Doanh thu theo gói tập (Pie chart)
- Doanh thu theo PT (Bar chart)
- So sánh theo kỳ

#### 3.1.7. Báo cáo & Thống kê
- Báo cáo khách hàng: Mới/Gia hạn/Hết hạn
- Báo cáo tần suất check-in
- Báo cáo hiệu suất PT: Số học viên, Giờ dạy, Doanh thu
- Báo cáo gói tập phổ biến
- Xuất PDF/Excel

#### 3.1.8. Phản hồi khách hàng
- Danh sách phản hồi
- Filter: Loại (Khiếu nại/Góp ý/Khen ngợi), Trạng thái
- Hiển thị: Khách hàng, Loại, Nội dung, Trạng thái, Ngày gửi
- Xử lý: Trả lời, Cập nhật trạng thái

---

### 3.2. MODULE PT

#### 3.2.1. Dashboard PT
- Lịch dạy hôm nay
- Số học viên đang phụ trách
- Tổng giờ dạy tháng này
- Thông báo lịch sắp tới

#### 3.2.2. Lịch làm việc
**Xem lịch cá nhân:**
- View: Ngày/Tuần/Tháng
- Hiển thị: Giờ, Học viên, Loại buổi, Trạng thái
- Lọc: Trạng thái (Đã xếp/Đã học/Hủy)

**Chi tiết buổi học:**
- Thông tin học viên
- Giáo án tập cho buổi đó
- Ghi chú PT
- Action: Xác nhận hoàn thành (Tự động chấm công)

#### 3.2.3. Quản lý Học viên
**Danh sách học viên:**
- Hiển thị: Avatar, Họ tên, Gói tập (Số buổi), Buổi đã tập, Buổi còn lại
- Search: Tên, SĐT

**Chi tiết học viên:**
- Thông tin cá nhân
- Gói tập hiện tại (Số buổi, Buổi đã tập, Buổi còn lại)
- Chỉ số cơ thể (Biểu đồ + Bảng)
- Giáo án tập
- Lịch sử điểm danh

**Ghi chỉ số cơ thể:**
- Chọn học viên
- Nhập: Cân nặng, Chiều cao, % Khối cơ, % Mỡ, Các vòng
- Tự động tính BMI
- Ngày đo (Mặc định hôm nay)
- Ghi chú

#### 3.2.4. Thư viện Bài tập
**Danh sách bài tập:**
- Filter: Nhóm cơ, Độ khó
- Search: Tên bài tập
- Hiển thị: Tên, Nhóm cơ, Độ khó, Người tạo

**Thêm bài tập:**
- Tên bài tập
- Nhóm cơ: Ngực/Lưng/Chân/Vai/Tay/Bụng...
- Độ khó: Dễ/Trung bình/Khó/Rất khó
- Hướng dẫn (Text editor)
- Link video (YouTube/Vimeo)
- Upload ảnh minh họa
- Người tạo: Tự động = PT hiện tại

#### 3.2.5. Giáo án Tập luyện
**Danh sách giáo án:**
- Filter: Học viên, Trạng thái
- Hiển thị: Tên giáo án, Học viên, Ngày bắt đầu-kết thúc, Trạng thái

**Tạo giáo án:**
- Chọn học viên
- Tên giáo án: VD: "Giảm cân 4 tuần", "Tăng cơ 8 tuần"
- Ngày bắt đầu - kết thúc
- Mô tả mục tiêu

**Chi tiết giáo án:**
- Thêm bài tập vào các ngày trong tuần
- Chọn bài tập từ thư viện
- Chọn ngày trong tuần (Thứ 2-7, CN)
- Nhập: Số set, Số rep, Thời gian nghỉ
- Ghi chú
- Có thể copy sang ngày khác

#### 3.2.6. Chấm công
**Bảng chấm công:**
- Xem theo tháng
- Hiển thị: Ngày, Buổi học, Học viên, Giờ bắt đầu-kết thúc, Số giờ, Trạng thái duyệt
- Trạng thái: Chưa duyệt/Đã duyệt (Do Admin duyệt)

**Lưu ý:**
- Chấm công tự động tạo khi điểm danh check-out
- PT chỉ xem, không thể sửa/xóa

---

### 3.3. MODULE USER (KHÁCH HÀNG)

#### 3.3.1. Dashboard User
- Thông tin thẻ: Mã thẻ, Trạng thái
- Gói tập hiện tại: Tên gói, Buổi còn lại (nếu có)
- Lịch tập sắp tới (3 buổi gần nhất)
- Chỉ số cơ thể mới nhất
- Nút điểm danh (Check-in/Check-out) khi có lịch tập

#### 3.3.2. Thông tin Cá nhân
- Xem: Họ tên, Email, SĐT, Ngày sinh, Giới tính, Avatar
- Sửa: SĐT, Avatar, Password

#### 3.3.3. Gói tập & PT
**Danh sách gói tập:**
- Hiển thị: Tên gói, Số buổi, Giá, Mô tả

**Gói của tôi:**
- Gói đang sử dụng
- Số buổi đã tập
- Số buổi còn lại
- PT phụ trách

#### 3.3.4. Lịch tập
- Xem lịch theo: Tuần/Tháng
- Hiển thị: Ngày, Giờ, PT, Trạng thái
- Chi tiết buổi học: Giáo án, Ghi chú PT

#### 3.3.5. Tiến độ Tập luyện
**Chỉ số cơ thể:**
- Biểu đồ line chart: Cân nặng, BMI, Khối cơ theo thời gian
- Bảng chi tiết: Ngày đo, Các chỉ số, PT đo

**Giáo án tập:**
- Danh sách giáo án
- Chi tiết: Xem bài tập theo ngày, Hướng dẫn, Video

#### 3.3.6. Lịch sử
**Lịch sử điểm danh:**
- Hiển thị: Ngày, Giờ check-in/out, Lịch tập (Buổi học với PT nào)
- Filter: Tháng

**Lịch sử thanh toán:**
- Hiển thị: Ngày, Gói tập, Số tiền (tiền mặt)
- Xuất hóa đơn (PDF)

#### 3.3.7. Phản hồi
**Gửi phản hồi:**
- Loại: Khiếu nại/Góp ý/Khen ngợi
- Nội dung
- Gửi

**Danh sách phản hồi:**
- Hiển thị: Ngày gửi, Loại, Trạng thái
- Xem phản hồi của Admin

---

## 4. MÔ HÌNH DỮ LIỆU CỐT LÕI

### 4.1. Các bảng chính (14 bảng)
1. **nguoi_dung** - Người dùng (3 roles: admin, pt, user)
2. **pt** - Thông tin PT
3. **khach_hang** - Thông tin khách hàng
4. **goi_tap** - Gói tập (Chỉ theo số buổi)
5. **dang_ky_goi** - Đăng ký gói tập
6. **lich_tap** - Lịch tập
7. **diem_danh** - Điểm danh (Liên kết với lịch tập)
8. **cham_cong_pt** - Chấm công PT (Liên kết với lịch tập)
9. **chi_so_co_the** - Chỉ số cơ thể
10. **bai_tap** - Thư viện bài tập
11. **giao_an_tap** - Giáo án tập
12. **chi_tiet_giao_an** - Chi tiết giáo án
13. **thanh_toan** - Thanh toán
14. **phan_hoi** - Phản hồi

### 4.2. Quan hệ chính
- **1 User** → có 1 role (admin/pt/user)
- **1 PT** → Có nhiều học viên, nhiều lịch dạy, nhiều chấm công
- **1 Khách hàng** → Có nhiều đăng ký gói, nhiều điểm danh, nhiều chỉ số, nhiều giáo án
- **1 Gói tập** → Chỉ có số buổi (không có thời hạn)
- **1 Đăng ký gói** → Có 1 PT, có nhiều lịch tập, có 1 thanh toán
- **1 Lịch tập** → Có 1 điểm danh, có 1 chấm công PT
- **1 Giáo án** → Có nhiều bài tập (chi tiết giáo án)

---

## 5. QUY TẮC NGHIỆP VỤ

### 5.1. Đăng ký Gói tập
- Khi tạo đăng ký gói:
  - Copy `goi_tap.so_buoi` → `dang_ky_goi.tong_buoi` và `buoi_con_lai`
  - Set `buoi_da_tap = 0`
  - Bắt buộc chọn PT phụ trách
  - Set `trang_thai_thanh_toan = 'da_thanh_toan'` sau khi thanh toán

### 5.2. Xếp Lịch tập
- Chỉ xếp lịch cho học viên có `buoi_con_lai > 0`
- Không được trùng lịch PT (cùng PT, cùng khung giờ)
- Mỗi lịch tập phải có: Học viên + PT + Ngày + Giờ

### 5.3. Điểm danh
- Kiểm tra điều kiện check-in:
  - `khach_hang.trang_thai_the = 'hoat_dong'`
  - `dang_ky_goi.trang_thai = 'hoat_dong'`
  - `buoi_con_lai > 0`
  - Phải có lịch tập hôm nay
- Khi check-in: Ghi `gio_check_in`, liên kết `id_lich_tap`
- Khi check-out: 
  - Ghi `gio_check_out`
  - Cập nhật `lich_tap.trang_thai = 'da_hoc'`
  - Trừ buổi: `buoi_da_tap += 1`, `buoi_con_lai -= 1`
  - Tự động tạo bản ghi `cham_cong_pt`

### 5.4. Chấm công PT
- Tự động tạo khi điểm danh check-out:
  - `id_pt` từ lịch tập
  - `ngay_lam` = ngày điểm danh
  - `gio_bat_dau`, `gio_ket_thuc` từ lịch tập
  - `so_gio_lam = (gio_ket_thuc - gio_bat_dau) / 3600`
  - `trang_thai = 'chua_duyet'`
- Admin duyệt: Cập nhật `trang_thai = 'da_duyet'`

### 5.5. Tính lương PT
- Lương tháng = (Tổng giờ đã duyệt × Lương/giờ) + Lương cơ bản
- Chỉ tính `cham_cong_pt.trang_thai = 'da_duyet'`
- Query: 
  ```sql
  SUM(so_gio_lam) WHERE trang_thai = 'da_duyet' AND MONTH(ngay_lam) = ?
  ```

### 5.6. Hết gói tập
- Khi `buoi_con_lai = 0`:
  - Tự động set `dang_ky_goi.trang_thai = 'het_han'`
  - Không thể xếp lịch mới
  - Vẫn có thể hoàn thành các buổi đã xếp

### 5.7. Hủy/Vắng mặt
- **Hủy lịch** (`trang_thai = 'huy'`): 
  - KHÔNG trừ buổi
  - Không tạo chấm công
- **Vắng mặt** (`trang_thai = 'vang_mat'`):
  - TRỪ buổi: `buoi_da_tap += 1`, `buoi_con_lai -= 1`
  - Tạo chấm công cho PT

---

## 6. YÊU CẦU PHÁT TRIỂN
- **Backend:** Laravel (MVC)
- **Frontend:** Blade Template (1 hệ thống thống nhất cho tất cả roles) , bootstrap, js, css
- **Database:** MySQL
- **Phân quyền:** Kiểm tra role đơn giản (admin/pt/user) từ bảng người dùng
- **Điểm danh:** Đăng nhập vào hệ thống (không dùng QR code)
- **Thanh toán:** Chỉ hỗ trợ tiền mặt
- **Storage:** Local/S3 cho ảnh và video
- **Report:** Export PDF (DomPDF), Export Excel (Maatwebsite)

---

## 7. LỘ TRÌNH TRIỂN KHAI

### Phase 1: Core System (Tuần 1-2)
- Authentication & Authorization (3 roles)
- Quản lý User, PT, Khách hàng
- Quản lý Gói tập

### Phase 2: Booking & Attendance (Tuần 3-4)
- Quản lý Lịch tập
- Điểm danh (Login-based)
- Chấm công PT

### Phase 3: Training Management (Tuần 5-6)
- Thư viện bài tập
- Giáo án tập luyện
- Theo dõi chỉ số cơ thể

### Phase 4: Finance & Report (Tuần 7-8)
- Thanh toán
- Báo cáo doanh thu
- Báo cáo thống kê
- Xuất PDF/Excel

### Phase 5: Additional Features (Tuần 9-10)
- Phản hồi khách hàng
- Dashboard charts
- Notification system
- Testing & Bug fixes