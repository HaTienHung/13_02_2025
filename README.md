# 🛒 My E-commerce Project

Ứng dụng web thương mại điện tử full-stack với các tính năng giỏ hàng , quản lí đơn hàng , sản phẩm và theo dõi tồn kho.

---

## 🔗 Demo

-   **Frontend**: [My E-commerce Project - Frontend](https://my-new-project-three-phi.vercel.app) (Trang sản phẩm)
-   **Frontend**: [My E-commerce Project - Frontend](https://my-new-project-three-phi.vercel.app/cms/dashboard) (Trang quản lí)
-   **API Docs (Swagger)**: https://13022025-production.up.railway.app/docs
-   👉 Mở [Swagger Editor](https://editor.swagger.io), chọn "File > Import URL", rồi dán link JSON vào để test API.

---

## 👤 Tài khoản demo

| **Role**  | **Email**         | **Mật khẩu** |
| --------- | ----------------- | ------------ |
| **Admin** | admin01@gmail.com | 12345678     |
| **User**  | user01@gmail.com  | 12345678     |

---

## 🧩 Tính năng chính

-   ✅ **Đăng nhập / Đăng ký**
-   ✅ **Phân quyền người dùng**: **Admin / User**
-   ✅ **Giỏ hàng**: Thêm, xoá, và sửa số lượng sản phẩm trong giỏ.
-   ✅ **Đặt hàng**: Người dùng đặt hàng từ giỏ hàng.
-   ✅ **Quản lý**:
    -   **Sản phẩm** (Thêm , sửa , xoá sản phẩm)
    -   **Đơn hàng** (Cập nhật trạng thái đơn hàng)
    -   **Danh mục** (Thêm , sửa , xoá , đơn hàng)
    -   **Tồn kho** (Theo dõi tồn kho , lịch sử nhập/xuất)
-   ✅ **Tìm kiếm**, **Phân trang**, và **Lọc sản phẩm**
-   ✅ **Swagger API**: Được tích hợp để test API trực tiếp

---

## 🛠️ Công nghệ sử dụng

-   **Frontend**: Next.js, Redux, TailwindCSS
-   **Backend**: Laravel 11, MySQL, REST API
-   **Authentication**: JWT
-   **Deployment**: Vercel (Frontend), Railway (Backend)
-   **API Documentation**: Swagger (OpenAPI 3)

---

## 📦 Kiến trúc hệ thống

-   **Frontend** và **Backend** được tách biệt rõ ràng
-   Backend sử dụng kiến trúc **Repository Pattern** kết hợp **Service Layer**
-   Frontend sử dụng **App Router** với **SSR (Server Side Rendering)** và **CSR (Client Side Rendering)**
-   Có **middleware** cho việc phân quyền người dùng
-   **Giỏ hàng** lưu trữ theo user, và được **convert thành đơn hàng** khi người dùng đặt hàng

---

## 📎 Ghi chú

> Ứng dụng có thể hơi chậm do phải cold start. Mong anh/chị thông cảm.
> """
