# Sử dụng PHP 8.2 với FPM
FROM php:8.2-fpm

RUN apt-get update
RUN apt install zlib1g-dev libpng-dev libjpeg-dev libzip-dev libgd-dev -y libcurl4-openssl-dev pkg-config libssl-dev

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath zip gd ctype

# Cài Composer từ image chính thức của Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc là thư mục gốc của Laravel trong container
WORKDIR /var/www

# Sao chép toàn bộ project Laravel vào container
COPY . .

# Cài đặt các package Laravel bằng Composer
RUN composer install --no-dev --optimize-autoloader

# Expose cổng 9000 (hoặc cổng mà bạn muốn, nhưng Railway sẽ override cổng đó)
EXPOSE 9000

# Chạy PHP-FPM khi container khởi động, dùng cổng từ biến môi trường PORT nếu có, không có dùng cổng 8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]


