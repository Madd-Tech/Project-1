FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx nodejs npm git
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 8080
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"