# FrankenPHP 이미지를 기반으로 시작
FROM dunglas/frankenphp:php8.2

# FrankenPHP에 내장된 스크립트를 사용하여 pdo_mysql 확장 설치
RUN install-php-extensions pdo_mysql