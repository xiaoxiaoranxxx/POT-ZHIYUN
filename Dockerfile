FROM php:7.4-apache

RUN echo "deb http://mirrors.aliyun.com/debian buster main non-free contrib" > /etc/apt/sources.list && \
    echo "deb-src http://mirrors.aliyun.com/debian buster main non-free contrib" >> /etc/apt/sources.list
RUN apt update

RUN echo "[PHP]" >> /usr/local/etc/php/php.ini && \
    echo "expose_php = Off" >> /usr/local/etc/php/php.ini
RUN sed -i -e 's/ServerTokens OS/ServerTokens Prod/g' /etc/apache2/conf-enabled/security.conf && \
    sed -i -e 's/ServerSignature On/ServerSignature Off/g' /etc/apache2/conf-enabled/security.conf
RUN a2enmod rewrite
ENV DEBIAN_FRONTEND=noninteractive
RUN apt install -y --allow-downgrades zlib1g=1:1.2.11.dfsg-1+deb10u1
RUN apt install -y libwebp-dev libjpeg-dev libpng-dev libfreetype6-dev
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-configure gd --with-webp=/usr/include/webp --with-jpeg=/usr/include --with-freetype=/usr/include/freetype2/ && docker-php-ext-install gd
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-enabled/000-default.conf
COPY . /var/www/html/

RUN mkdir /var/www/html/runtime/ && chmod -R 777 /var/www/html/runtime/