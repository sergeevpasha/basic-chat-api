FROM php:8.4-fpm

ARG UNAME=admin
ARG UGROUP=admin
ARG UID=1000
ARG GID=1000

RUN groupadd -g $GID $UGROUP && \
    useradd -u $UID -g $UGROUP -ms /bin/bash $UNAME && \
    usermod -aG sudo $UNAME

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    cron \
    zip \
    unzip \
    libpq-dev \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_pgsql sockets pcntl

RUN echo "* * * * * www-data php /var/www/artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel \
    && chmod 0644 /etc/cron.d/laravel \
    && crontab /etc/cron.d/laravel

RUN mkdir -p /var/log/supervisor

COPY docker/php/supervisor/reverb.conf /etc/supervisor/conf.d/reverb.conf

COPY ./src /var/www

WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage

RUN composer install

EXPOSE 9000 8080

CMD php-fpm & supervisord -n
