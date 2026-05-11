# FrankenPHP 이미지를 기반으로 시작
FROM dunglas/frankenphp:php8.3

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsodium-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install bcmath gd mysqli pdo_mysql sodium soap curl fileinfo mbstring shmop intl
RUN docker-php-ext-configure intl
