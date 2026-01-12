# Phân Tích Thiết Kế Hệ Thống Web Food

## 1. Giới Thiệu
### 1.1 Giới thiệu đề tài
- Tên dự án: Food_order
- Mục tiêu: Xây dựng hệ thống đặt đồ ăn trực tuyến đơn giản, có giao diện người dùng thân thiện, API cho thao tác giỏ hàng và nhắn tin, cùng trang quản trị để quản lý danh mục, món ăn, đơn hàng và cuộc hội thoại.
- Phạm vi báo cáo: mô tả chức năng, luồng dữ liệu, API chính, cấu trúc cơ sở dữ liệu, xác thực, bảo mật cơ bản và hướng dẫn chạy môi trường local để minh họa.


### 1.2 Mô tả bài toán
Dự án Food_order được thực hiện với mục đích xây dựng một hệ thống đặt đồ ăn trực tuyến đơn giản, dễ sử dụng và phù hợp cho việc học tập, nghiên cứu và minh họa quy trình phát triển một ứng dụng web hoàn chỉnh. Hệ thống hướng tới việc cung cấp cho người dùng một nền tảng thuận tiện để xem danh mục món ăn, lựa chọn sản phẩm, quản lý giỏ hàng, đặt đơn và trao đổi thông tin thông qua chức năng nhắn tin.

Bên cạnh đó, dự án còn tập trung xây dựng API backend phục vụ các thao tác cốt lõi như quản lý giỏ hàng, xử lý đơn hàng và quản lý hội thoại, giúp minh họa mô hình tách biệt giữa frontend và backend trong phát triển web hiện đại. Hệ thống quản trị (Admin) được thiết kế nhằm hỗ trợ người quản lý trong việc kiểm soát danh mục, món ăn, đơn hàng và các cuộc hội thoại với khách hàng một cách hiệu quả.

Thông qua dự án này, nhóm thực hiện mong muốn:

Nắm vững quy trình phân tích yêu cầu và thiết kế hệ thống web.

Áp dụng kiến thức về thiết kế cơ sở dữ liệu, xây dựng API và xác thực người dùng.

Hiểu và triển khai các biện pháp bảo mật cơ bản trong ứng dụng web.

Minh họa cách triển khai và chạy hệ thống trong môi trường local phục vụ phát triển và kiểm thử.

Dự án Food_order không chỉ đáp ứng các chức năng cơ bản của một hệ thống đặt đồ ăn trực tuyến mà còn đóng vai trò là tài liệu tham khảo cho việc học tập và phát triển các hệ thống web tương tự trong tương lai.

*Mô tả mục đích chính của hệ thống web food*
Hệ thống Web Food được xây dựng nhằm cung cấp một nền tảng trực tuyến hỗ trợ việc đặt đồ ăn một cách nhanh chóng, thuận tiện và hiệu quả. Mục đích chính của hệ thống là giúp người dùng dễ dàng tìm kiếm, lựa chọn món ăn, quản lý giỏ hàng và thực hiện đặt đơn thông qua giao diện web thân thiện, dễ sử dụng.

Đối với phía quản lý, hệ thống cung cấp trang quản trị cho phép quản lý danh mục món ăn, thông tin sản phẩm, đơn hàng và các cuộc hội thoại với khách hàng. Điều này giúp tối ưu hóa quy trình quản lý, giảm thiểu thao tác thủ công và nâng cao hiệu quả vận hành.

Bên cạnh đó, hệ thống Web Food còn hướng tới việc xây dựng và minh họa một kiến trúc web hiện đại với sự tách biệt rõ ràng giữa giao diện người dùng và API xử lý nghiệp vụ. Thông qua hệ thống, các chức năng xác thực, phân quyền và bảo mật cơ bản được áp dụng nhằm đảm bảo an toàn dữ liệu và quyền truy cập của người dùng.

## 2. Mô tả tổng quan
Website bán đồ ăn là một hệ thống ứng dụng web được xây dựng nhằm phục vụ nhu cầu đặt món và quản lý hoạt động kinh doanh ẩm thực trong môi trường trực tuyến. Hệ thống cho phép khách hàng truy cập, xem thực đơn, lựa chọn món ăn, đặt hàng và theo dõi trạng thái đơn hàng một cách nhanh chóng và thuận tiện thông qua trình duyệt web

Về phía người dùng, website hỗ trợ các chức năng cơ bản như đăng ký và đăng nhập tài khoản, tìm kiếm và xem chi tiết món ăn, quản lý giỏ hàng, thực hiện đặt hàng và lựa chọn phương thức thanh toán phù hợp. Thông tin đơn hàng sau khi được tạo sẽ được lưu trữ và cập nhật liên tục, giúp khách hàng dễ dàng theo dõi quá trình xử lý và giao hàng.

Về phía quản trị viên, hệ thống cung cấp các công cụ quản lý toàn diện bao gồm quản lý danh mục và món ăn, quản lý đơn hàng, quản lý người dùng và thống kê doanh thu. Các chức năng này giúp người quản lý kiểm soát hiệu quả hoạt động kinh doanh, giảm thiểu sai sót trong quá trình xử lý đơn và nâng cao chất lượng dịch vụ.

Website được thiết kế theo mô hình  client–server , kết hợp với cơ sở dữ liệu để lưu trữ và xử lý dữ liệu. Hệ thống chú trọng đến các yếu tố như  tính bảo mật, độ ổn định, hiệu năng và khả năng mở rộng , đảm bảo đáp ứng tốt nhu cầu sử dụng thực tế và có thể phát triển trong tương lai, chẳng hạn như tích hợp thanh toán trực tuyến hoặc dịch vụ giao hàng.
### 2.1 Tổng quan hệ thống
### 2.2 Xác định các tác nhân
### 2.3 Xác định yêu cầu hệ thống
#### 2.3.1 Yêu cầu chức năng
#### 2.3.2 Yêu cầu phi chức năng
## 3. Luồng màn hình

### 3.1 Các chức năng hệ thống

```
┌─────────────────────────────────────────────┐
│          Client Layer                       │
│  ┌──────────────┐  ┌──────────────┐        │
│  │ Web Frontend │  │ Mobile App   │        │
│  └──────────────┘  └──────────────┘        │
└─────────────────────────────────────────────┘
           │                   │
┌─────────────────────────────────────────────┐
│        API Gateway / Load Balancer          │
└─────────────────────────────────────────────┘
           │
┌─────────────────────────────────────────────┐
│        Application Layer (Microservices)    │
│  ┌──────────────┐  ┌──────────────┐        │
│  │ Auth Service │  │ Order Service│        │
│  └──────────────┘  └──────────────┘        │
│  ┌──────────────┐  ┌──────────────┐        │
│  │ User Service │  │ Payment Svc  │        │
│  └──────────────┘  └──────────────┘        │
└─────────────────────────────────────────────┘
           │
┌─────────────────────────────────────────────┐
│         Data Access Layer                   │
│  ┌──────────────┐  ┌──────────────┐        │
│  │ Database     │  │ Cache (Redis)│        │
│  └──────────────┘  └──────────────┘        │
└─────────────────────────────────────────────┘
```
### 3.2 Biểu đồ mô tả hệ thống

| STT | Màn hình | Mô tả |
|-----|----------|-------|
| 1 | Trang chủ | Người dùng xem các món ăn hiển thị trên màn hình, tìm kiếm món ăn, nhập email liên hệ và điều hướng sang các trang khác |
| 2 | Danh sách món ăn | Hiển thị các món ăn theo danh mục; bộ lọc theo loại món, giá, mức độ phổ biến |
| 3 | Chi tiết món ăn | Hiển thị tên món, mã món, giá, hình ảnh, mô tả, thành phần, số lượng còn lại |
| 4 | Giỏ hàng | Danh sách món ăn đã chọn, số lượng, tổng tiền, thông tin khách hàng, phương thức thanh toán |
| 5 | Theo dõi đơn | Hiển thị tình trạng đơn hàng đang được xử lý (đang chuẩn bị, đang giao, hoàn thành) |
| 6 | Liên hệ | Thông tin admin/cửa hàng: số điện thoại, email, địa chỉ |
| 7 | Đăng nhập | Nhân viên và admin đăng nhập vào hệ thống |
| 8 | Đăng ký | Nhân viên đăng ký tài khoản |
| 9 | Quên mật khẩu | Nhập email để nhận mã xác thực |
| 10 | OTP | Nhập mã OTP để xác thực |
| 11 | Đổi mật khẩu | Nhập thông tin cần thiết để đổi mật khẩu |
| 12 | Quản lý danh mục | Xem trạng thái, người tạo, tìm kiếm; thao tác thêm, sửa, xóa danh mục món ăn |
| 13 | Tổng quan | Thống kê người dùng, doanh thu, số đơn hàng, biểu đồ doanh thu, đơn hàng mới |
| 14 | Tạo danh mục | Tạo danh mục món ăn mới: tên, danh mục cha, vị trí, trạng thái, ảnh, mô tả |
| 15 | Chỉnh sửa danh mục | Cập nhật thông tin danh mục món ăn |
| 16 | Thông tin liên hệ | Danh sách email khách hàng và ngày tạo |
| 17 | Quản lý đơn hàng | Tìm kiếm, xem thông tin đơn hàng: mã đơn, khách hàng, món ăn, thanh toán, trạng thái |
| 18 | Chỉnh sửa đơn hàng | Sửa tên khách, số điện thoại, ghi chú, phương thức thanh toán, trạng thái đơn |
| 19 | Quản lý món ăn | Tìm kiếm, tạo mới, sửa, xóa món ăn; xem người tạo/cập nhật, giá, trạng thái |
| 20 | Tạo mới món ăn | Nhập thông tin món ăn: tên, danh mục, giá, số lượng, hình ảnh, mô tả |
| 21 | Thùng rác | Danh sách món ăn đã xóa và chức năng khôi phục |
| 22 | Cài đặt chung | Quản lý thông tin website, tài khoản và nhóm quyền |
| 23 | Thông tin website | Chỉnh sửa tên website, số điện thoại, email, địa chỉ, logo, favicon |
| 24 | Quản trị tài khoản | Danh sách nhân viên: số điện thoại, nhóm quyền, chức vụ |
| 25 | Tạo tài khoản quản trị | Nhập họ tên, email, số điện thoại, nhóm quyền, chức vụ, trạng thái, mật khẩu |
| 26 | Nhóm quyền | Danh sách nhóm quyền và mô tả |
| 27 | Tạo nhóm quyền | Thêm nhóm quyền mới |
| 28 | Chỉnh sửa nhóm quyền | Chỉnh sửa tên nhóm quyền, mô tả và phân quyền |
| 29 | Thông tin cá nhân | Quản lý thông tin cá nhân nhân viên |

### 3.3 Luồng Dữ Liệu Chính

*Mô tả cách dữ liệu chảy qua hệ thống*

---

## 4. Tổng quan về phần mềm

### 4.1 Kiến trúc hệ thống

*Liệt kê các bảng chính và mối quan hệ*

```
Users
├── id (PK)
├── email
├── password_hash
├── phone
├── address
├── created_at
└── updated_at

Restaurants
├── id (PK)
├── name
├── owner_id (FK → Users)
├── address
├── rating
└── created_at

MenuItems
├── id (PK)
├── restaurant_id (FK → Restaurants)
├── name
├── price
├── description
└── image_url

Orders
├── id (PK)
├── user_id (FK → Users)
├── restaurant_id (FK → Restaurants)
├── total_amount
├── status
└── created_at

OrderDetails
├── id (PK)
├── order_id (FK → Orders)
├── menu_item_id (FK → MenuItems)
└── quantity
```

### 4.2 Luồng hoạt động phần mềm


### 4.3 Các Ràng Buộc (Constraints)

*Mô tả primary key, foreign key, unique constraints*

---

## 5. Thiết Kế Phần Mềm 

### 5.1 Tổng quan các chức năng người dùng (User-facing)

5.1.1 Duyệt và tìm món
- `index.php`: Trang chủ hiển thị các danh mục nổi bật và các món ăn tiêu biểu. Cho phép dẫn link tới `category-food.php` hoặc `food.php`.
- `categories.php`: Liệt kê toàn bộ danh mục kèm ảnh minh họa.
- `category-food.php`: Hiển thị danh sách món theo `category_id` kèm phân trang nếu cần.
- `food-search.php` và `search-suggestions.php`: Tìm kiếm tên món (có AJAX gợi ý khi nhập từ khóa).

5.1.2 Xem chi tiết và đặt món
- `food.php`: Hiển thị thông tin chi tiết món (tên, mô tả, thành phần, giá, ảnh), cho phép chọn số lượng và thêm vào giỏ hàng.
- Thêm vào giỏ có thể thực hiện qua AJAX gọi `api/add-to-cart.php`.

5.1.3 Giỏ hàng và thanh toán
- `user/cart.php`: Hiện danh sách món trong giỏ, cho phép sửa số lượng, xóa mục.
- `user/checkout.php`: Form nhập địa chỉ giao hàng, phương thức thanh toán và tóm tắt đơn.
- `user/payment.php`: Xử lý trả về trạng thái thanh toán (trong đồ án có thể mô phỏng thành công/thất bại).
- `order.php` (file gốc): Tạo bản ghi đơn hàng nếu dự án thiết kế như vậy.

5.1.4 Quản lý tài khoản
- `user/register.php`, `user/login.php`, `user/logout.php`: Đăng ký/đăng nhập/đăng xuất người dùng.
- `user/forgot-password.php`, `user/reset-password.php`, `api/send-verification.php`, `user/verify-code.php`: Luồng khôi phục mật khẩu bằng mã xác minh gửi email.

5.1.5. Tin nhắn / Chat (user ↔ admin)
- `user/chat.php`: Giao diện chat cho user.
- API hỗ trợ: `api/send-message.php`, `api/get-messages.php`, `api/get-chat-list.php`, `api/get-unread-count.php`, `api/mark-messages-read.php`.

---
### 5.2 Chức năng quản trị (Admin)

- `admin/login.php`, `admin/logout.php`: Xác thực admin (session-based). Kiểm tra quyền truy cập bằng `admin/partials/login-check.php`.
- `admin/index.php`: Bảng điều khiển hiển thị thông tin tổng quan: đơn mới, doanh thu, số tin nhắn chưa đọc.
- Quản lý admin: `admin/manage-admin.php`, `admin/add-admin.php`, `admin/update-admin.php`, `admin/delete-admin.php`.
- Quản lý danh mục: `admin/manage-category.php`, `admin/add-category.php`, `admin/update-category.php`, `admin/delete-category.php`.
- Quản lý món ăn: `admin/manage-food.php`, `admin/add-food.php`, `admin/update-food.php`, `admin/delete-food.php`.
- Quản lý đơn hàng: `admin/manage-order.php`, `admin/update-order.php`.
- Quản lý chat: `admin/manage-chat.php` để xem và trả lời khách.

---

## 6. THIẾT KẾ DỮ LIỆU HỆ THỐNG

### 6.1 Mô tả dữ liệu

Hệ thống Web Food được xây dựng nhằm phục vụ hoạt động đặt món ăn trực tuyến, quản lý đơn hàng và hỗ trợ khách hàng. Cơ sở dữ liệu **food-order** được thiết kế trên nền tảng MariaDB/MySQL, đáp ứng các yêu cầu lưu trữ, truy xuất và đảm bảo tính toàn vẹn dữ liệu.

Các nhóm dữ liệu chính trong hệ thống bao gồm:

* **Dữ liệu quản trị viên** : phục vụ cho việc quản lý hệ thống, theo dõi và xử lý các yêu cầu từ người dùng.
* **Dữ liệu người dùng** : lưu trữ thông tin tài khoản khách hàng đăng ký sử dụng dịch vụ.
* **Dữ liệu danh mục món ăn** : hỗ trợ phân loại món ăn theo từng nhóm cụ thể.
* **Dữ liệu món ăn** : quản lý thông tin chi tiết của các món được cung cấp trên hệ thống.
* **Dữ liệu đơn hàng** : ghi nhận quá trình đặt món, trạng thái và thông tin giao hàng.
* **Dữ liệu trò chuyện** : lưu lịch sử trao đổi giữa khách hàng và quản trị viên nhằm hỗ trợ và giải đáp thắc mắc.

Cách tổ chức dữ liệu theo mô hình quan hệ giúp hệ thống vận hành ổn định, dễ bảo trì và thuận tiện cho việc mở rộng trong tương lai.

### 6.2 Biểu đồ ER (Entity – Relationship)

Dựa trên cơ sở dữ liệu hiện tại, hệ thống bao gồm các thực thể chính sau:

1. **Quản trị viên (tbl_admin)**
   * id (khóa chính)
   * full_name
   * email
   * username
   * password
2. **Người dùng (tbl_user)**
   * id (khóa chính)
   * full_name
   * username (duy nhất)
   * password
   * email (duy nhất)
   * phone
   * address
   * status
   * created_at
3. **Danh mục (tbl_category)**
   * id (khóa chính)
   * title
   * featured
   * active
   * image_name
4. **Món ăn (tbl_food)**
   * id (khóa chính)
   * title
   * description
   * price
   * image_name
   * category_id (khóa ngoại)
   * featured
   * active
5. **Đơn hàng (tbl_order)**
   * id (khóa chính)
   * order_code (duy nhất)
   * user_id (khóa ngoại)
   * food
   * price
   * qty
   * total
   * order_date
   * status
   * customer_name
   * customer_contact
   * customer_email
   * customer_address
6. **Trò chuyện (tbl_chat)**
   * id (khóa chính)
   * user_id (khóa ngoại)
   * admin_id (khóa ngoại)
   * sender_type
   * message
   * is_read
   * created_at

**Các mối quan hệ giữa các thực thể:**

* Một người dùng có thể phát sinh nhiều đơn hàng.
* Người dùng và quản trị viên có thể trao đổi nhiều tin nhắn thông qua chức năng chat.
* Mỗi danh mục có thể chứa nhiều món ăn khác nhau.

### 6.3 Thiết kế dữ liệu (Lược đồ quan hệ)

Các bảng dữ liệu trong hệ thống được thiết kế như sau:

* **TBL_ADMIN** (id PK, full_name, email, username, password)
* **TBL_USER** (id PK, full_name, username UQ, password, email UQ, phone, address, status, created_at)
* **TBL_CATEGORY** (id PK, title, featured, active, image_name)
* **TBL_FOOD** (id PK, title, description, price, image_name, category_id FK, featured, active)
* **TBL_ORDER** (id PK, order_code UQ, user_id FK, food, price, qty, total, order_date, status, customer_name, customer_contact, customer_email, customer_address)
* **TBL_CHAT** (id PK, user_id FK, admin_id FK, sender_type, message, is_read, created_at)

Các ràng buộc khóa ngoại được thiết lập nhằm đảm bảo tính toàn vẹn dữ liệu, đồng thời hỗ trợ kiểm soát mối quan hệ giữa các bảng.

### 6.4 Sơ đồ ERD

Sơ đồ ERD thể hiện rõ cấu trúc tổng thể của cơ sở dữ liệu và mối liên hệ giữa các bảng. Người dùng liên kết với bảng đơn hàng và bảng trò chuyện; quản trị viên tham gia vào quá trình trao đổi hỗ trợ; danh mục đóng vai trò phân loại cho các món ăn.

Mặc dù thiết kế hiện tại đáp ứng tốt nhu cầu của hệ thống, tuy nhiên bảng đơn hàng vẫn lưu trực tiếp tên món ăn thay vì liên kết khóa ngoại tới bảng món ăn. Điều này có thể gây khó khăn trong việc mở rộng và chuẩn hóa dữ liệu khi hệ thống phát triển lớn hơn.

**Kết luận:**
Thiết kế dữ liệu của hệ thống Web Food được xây dựng phù hợp với yêu cầu nghiệp vụ thực tế, đảm bảo khả năng quản lý, truy xuất và vận hành ổn định. Trong giai đoạn tiếp theo, hệ thống có thể được cải tiến bằng cách chuẩn hóa sâu hơn cấu trúc cơ sở dữ liệu nhằm nâng cao hiệu quả và tính mở rộng.


