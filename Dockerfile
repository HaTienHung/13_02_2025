# Sử dụng PHP 8.2 với FPM
FROM php:8.2-fpm

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install pdo pdo_mysql

# Cài Composer từ image chính thức của Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc là thư mục gốc của Laravel trong container
WORKDIR /var/www

# Sao chép toàn bộ project Laravel vào container
COPY . .

# Cấp quyền ghi cho thư mục storage & bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Cài đặt các package Laravel bằng Composer
RUN composer install --no-dev --optimize-autoloader

# Expose cổng 9000 để container PHP-FPM có thể nhận request từ Nginx
EXPOSE 9000

# Chạy PHP-FPM khi container khởi động
CMD ["php-fpm"]
