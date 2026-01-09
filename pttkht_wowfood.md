# Phân Tích Thiết Kế Hệ Thống Web Food

## Mục Lục
- [1. Giới Thiệu](#1-giới-thiệu)    
- [2. Mô tả tổng quan](#2-phân-tích-yêu-cầu)
- [3. Luồng màn hình](#3-kiến-trúc-hệ-thống) 
- [4. Tổng quan về phần mềm](#4-thiết-kế-cơ-sở-dữ-liệu)
- [5. Thiết kế phần mềm ](#5-thiết-kế-api)
- [6. Thiết kế dữ liệu](#6-bảo-mật)
- 

## 1. Giới Thiệu
- Tên dự án: Food_order
- Mục tiêu: Xây dựng hệ thống đặt đồ ăn trực tuyến đơn giản, có giao diện người dùng thân thiện, API cho thao tác giỏ hàng và nhắn tin, cùng trang quản trị để quản lý danh mục, món ăn, đơn hàng và cuộc hội thoại.
- Phạm vi báo cáo: mô tả chức năng, luồng dữ liệu, API chính, cấu trúc cơ sở dữ liệu, xác thực, bảo mật cơ bản và hướng dẫn chạy môi trường local để minh họa.


### 1.1 Mục Đích Dự Án
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

### 1.2 Phạm Vi Hệ Thống

*Liệt kê các tính năng chính*

### 1.3 Các Bên Liên Quan

*Xác định người dùng chính: Khách hàng, Nhà hàng, Admin, Tài xế giao hàng, v.v.*

---

## 2. Mô tả bài toán

### 2.1 Yêu Cầu Chức Năng

- **Quản lý người dùng**: Đăng ký, đăng nhập, cập nhật hồ sơ
- **Quản lý nhà hàng**: Thêm menu, cập nhật giá cả, quản lý đơn hàng
- **Quản lý đơn hàng**: Tạo, theo dõi, hủy đơn
- **Thanh toán**: Tích hợp gateway thanh toán
- **Giao hàng**: Quản lý tài xế, theo dõi vị trí
- **Đánh giá & Bình luận**: Hệ thống rating sao

### 2.2 Yêu Cầu Phi Chức Năng

- **Hiệu suất**: Thời gian phản hồi < 2 giây
- **Khả dụng**: 99.9% uptime
- **Bảo mật**: Mã hóa dữ liệu, xác thực
- **Khả năng mở rộng**: Hỗ trợ 100,000+ người dùng đồng thời
- **Khả năng bảo trì**: Code sạch, tài liệu đầy đủ

### 2.3 Trường Hợp Sử Dụng Chính

Website bán đồ ăn là một hệ thống ứng dụng web được xây dựng nhằm phục vụ nhu cầu đặt món và quản lý hoạt động kinh doanh ẩm thực trong môi trường trực tuyến. Hệ thống cho phép khách hàng truy cập, xem thực đơn, lựa chọn món ăn, đặt hàng và theo dõi trạng thái đơn hàng một cách nhanh chóng và thuận tiện thông qua trình duyệt web.

Về phía người dùng, website hỗ trợ các chức năng cơ bản như đăng ký và đăng nhập tài khoản, tìm kiếm và xem chi tiết món ăn, quản lý giỏ hàng, thực hiện đặt hàng và lựa chọn phương thức thanh toán phù hợp. Thông tin đơn hàng sau khi được tạo sẽ được lưu trữ và cập nhật liên tục, giúp khách hàng dễ dàng theo dõi quá trình xử lý và giao hàng.

Về phía quản trị viên, hệ thống cung cấp các công cụ quản lý toàn diện bao gồm quản lý danh mục và món ăn, quản lý đơn hàng, quản lý người dùng và thống kê doanh thu. Các chức năng này giúp người quản lý kiểm soát hiệu quả hoạt động kinh doanh, giảm thiểu sai sót trong quá trình xử lý đơn và nâng cao chất lượng dịch vụ.

Website được thiết kế theo mô hình  client–server , kết hợp với cơ sở dữ liệu để lưu trữ và xử lý dữ liệu. Hệ thống chú trọng đến các yếu tố như  tính bảo mật, độ ổn định, hiệu năng và khả năng mở rộng , đảm bảo đáp ứng tốt nhu cầu sử dụng thực tế và có thể phát triển trong tương lai, chẳng hạn như tích hợp thanh toán trực tuyến hoặc dịch vụ giao hàng.

## 3. Luồng màn hình

### 3.1 Sơ Đồ Kiến Trúc Tổng Thể

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

<<<<<<< Updated upstream
### 3.2 Mô tả màn hình

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

### 3.2 Các Thành Phần Chính

- **Frontend**: React/Vue/Angular
- **Backend**: Node.js/Python/Java
- **Database**: PostgreSQL/MySQL
- **Cache**: Redis
- **Message Queue**: RabbitMQ/Kafka
- **Cloud Storage**: AWS S3/Google Cloud Storage

### 3.3 Luồng Dữ Liệu Chính

*Mô tả cách dữ liệu chảy qua hệ thống*

---

## 4. Thiết Kế Cơ Sở Dữ Liệu

### 4.1 Schema Chính

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

### 4.2 Indexes

*Liệt kê các index quan trọng để tối ưu hiệu suất*

### 4.3 Các Ràng Buộc (Constraints)

*Mô tả primary key, foreign key, unique constraints*

---

## 5. Thiết Kế API

### 5.1 Endpoints Chính

#### Authentication

- `POST /api/auth/register` - Đăng ký người dùng
- `POST /api/auth/login` - Đăng nhập
- `POST /api/auth/logout` - Đăng xuất
- `POST /api/auth/refresh-token` - Làm mới token

#### Users

- `GET /api/users/{id}` - Lấy thông tin người dùng
- `PUT /api/users/{id}` - Cập nhật thông tin người dùng
- `DELETE /api/users/{id}` - Xóa người dùng

#### Restaurants

- `GET /api/restaurants` - Liệt kê nhà hàng
- `GET /api/restaurants/{id}` - Chi tiết nhà hàng
- `POST /api/restaurants` - Tạo nhà hàng mới
- `PUT /api/restaurants/{id}` - Cập nhật nhà hàng
- `DELETE /api/restaurants/{id}` - Xóa nhà hàng

#### Orders

- `GET /api/orders` - Liệt kê đơn hàng
- `POST /api/orders` - Tạo đơn hàng
- `GET /api/orders/{id}` - Chi tiết đơn hàng
- `PUT /api/orders/{id}` - Cập nhật trạng thái đơn hàng
- `DELETE /api/orders/{id}` - Hủy đơn hàng

### 5.2 Request/Response Format

*Mô tả định dạng JSON, pagination, error handling*

### 5.3 Authentication & Authorization

*Mô tả JWT tokens, roles, permissions*

---

## 6. Bảo Mật

### 6.1 Xác Thực & Phép Cấp

- JWT tokens
- Role-Based Access Control (RBAC)
- OAuth 2.0 (nếu cần)

### 6.2 Mã Hóa Dữ Liệu

- Mã hóa mật khẩu: bcrypt/argon2
- HTTPS/TLS cho truyền tải dữ liệu
- Mã hóa dữ liệu nhạy cảm trong database

### 6.3 Bảo Vệ Khỏi Tấn Công

- SQL Injection prevention (Prepared Statements)
- XSS (Cross-Site Scripting) prevention
- CSRF (Cross-Site Request Forgery) protection
- Rate limiting
- Input validation & sanitization

### 6.4 Quản Lý Session

*Mô tả session timeout, session storage*

---

## 7. Hiệu Suất & Tối Ưu Hóa

### 7.1 Caching Strategy

- Redis cho caching data thường dùng
- Caching nhà hàng, menu items
- Browser caching cho static assets

### 7.2 Database Optimization

- Query optimization
- Proper indexing
- Connection pooling
- Read replicas nếu cần

### 7.3 Frontend Optimization

- Code splitting
- Lazy loading
- Image optimization
- Minification & compression

### 7.4 Load Balancing

*Mô tả cách phân tán tải*

---

## 8. Kiểm Thử

### 8.1 Unit Testing

- Kiểm thử từng function
- Test coverage: 80%+

### 8.2 Integration Testing

- Kiểm thử API endpoints
- Kiểm thử database interactions

### 8.3 End-to-End Testing

- Kiểm thử toàn bộ luồng người dùng

### 8.4 Performance Testing

- Load testing
- Stress testing
- Benchmark testing

### 8.5 Security Testing

- Penetration testing
- Vulnerability scanning
- OWASP Top 10 checks

---

## 9. Triển Khai & Vận Hành

### 9.1 Môi Trường Triển Khai

- **Development**: Máy local
- **Staging**: Test environment
- **Production**: Live environment

### 9.2 CI/CD Pipeline

- Automated testing
- Automated deployment
- Version control strategy (Git flow)

### 9.3 Monitoring & Logging

- Application monitoring
- Server monitoring
- Log aggregation (ELK stack)
- Error tracking (Sentry)

### 9.4 Backup & Disaster Recovery

- Regular database backups
- Backup strategy
- Recovery procedures

### 9.5 Versioning & Release Management

- Semantic versioning
- Release notes
- Rollback procedures

---

## Phụ Lục

### A. Tài Liệu Tham Khảo

*Liệt kê các tài liệu, thư viện, tools được sử dụng*

### B. Quyết Định Thiết Kế

*Ghi lại các quyết định quan trọng và lý do*

### C. Các Vấn Đề Đã Biết

*Liệt kê các hạn chế hoặc vấn đề hiện tại*

### D. Kế Hoạch Phát Triển Tương Lai

*Các tính năng sắp thêm vào*
