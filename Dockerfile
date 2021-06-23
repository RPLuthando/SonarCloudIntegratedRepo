#start with our base image (the foundation) - version 7.1.5
FROM php:7.4-apache-buster

#install all the system dependencies and enable PHP modules 
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      libonig-dev \
      libzip-dev \
      git \
      zip \
      unzip \
      ssl-cert \
    && rm -r /var/lib/apt/lists/* \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
      intl \
      mbstring \
      pcntl \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      zip \
      opcache

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#set our application folder as an environment variable
ENV APP_HOME /var/www/html

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

ENV APACHE_DOCUMENT_ROOT='/var/www/html/public'

COPY deployment_scripts/configs/apache-ssl-redirect-enabled.conf /etc/apache2/sites-enabled/000-default.conf
COPY deployment_scripts/docker_startup.sh /bin/start_server.sh
#change the web_root to laravel /var/www/html/public folder
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-enabled/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i '/<Directory ${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride all/' /etc/apache2/apache2.conf
# enable apache module rewrite
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2enmod headers
RUN a2ensite default-ssl
#copy source files and change ownership
RUN chown www-data:www-data $APP_HOME
COPY --chown=www-data:www-data . $APP_HOME

# install all PHP dependencies
RUN composer install --no-interaction
EXPOSE 80
EXPOSE 443
ENTRYPOINT [ "/bin/start_server.sh" ]
CMD ["apache2-foreground"]