FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

COPY crontab /etc/crontabs/root

RUN touch /var/log/cron.log

CMD ["/usr/sbin/crond", "-f", "-d", "0"]
