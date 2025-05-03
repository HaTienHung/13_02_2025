# 🛒 My E-commerce Project

A full-stack e-commerce web application with user roles, cart, product management, and inventory tracking.

---

## 🔗 Demo

-   **Frontend**: [https://my-new-project-three-phi.vercel.app](https://my-new-project-three-phi.vercel.app)
-   **API Docs (Swagger)**: [https://13022025-production.up.railway.app/docs](https://13022025-production.up.railway.app/docs)
-   👉 Mở [Swagger Editor](https://editor.swagger.io), chọn "File > Import URL", rồi dán link JSON vào để test API.

---

## 👤 Tài khoản demo

| Role  | Email             | Mật khẩu |
| ----- | ----------------- | -------- |
| Admin | admin01@gmail.com | 12345678 |
| User  | user01@gmail.com  | 12345678 |

---

## 🧩 Tính năng chính

-   ✅ Đăng nhập / Đăng ký
-   ✅ Phân quyền người dùng: **Admin / User**
-   ✅ Giỏ hàng (thêm, xoá, sửa số lượng)
-   ✅ Quản lý:
    -   Sản phẩm
    -   Đơn hàng
    -   Tồn kho (inventory)
-   ✅ Tìm kiếm, phân trang và lọc sản phẩm
-   ✅ Swagger API để test trực tiếp

---

## 🛠️ Công nghệ sử dụng

-   **Frontend**: Next.js, Redux, TailwindCSS
-   **Backend**: Laravel 11, MySQL, REST API
-   **Authentication**: JWT
-   **Deployment**: Vercel (FE), Railway (BE)
-   **API Documentation**: Swagger (OpenAPI 3)

---

## 📦 Kiến trúc hệ thống

-   Tách riêng **FE** và **BE**
-   BE theo kiến trúc: `Repository Pattern` + `Service Layer`
-   FE sử dụng `App Router` + `SSR + CSR`
-   Có phân quyền qua middleware
-   Giỏ hàng lưu theo user, convert thành đơn hàng khi đặt

---

## 📎 Ghi chú

> Ứng dụng có thể hơi chậm vì phải cold start , mong anh/chị thông cảm .
