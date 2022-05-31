FROM php:8.1.3

RUN useradd -ms /bin/bash developer

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt update && apt-get install -y telnet net-tools htop vim iputils-ping bash git

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update
RUN apt install symfony-cli -y

#https://stackoverflow.com/questions/57183109/how-to-enable-phps-mysql-extensions-in-docker-container
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

#https://stackoverflow.com/questions/31369867/how-to-install-php-redis-extension-using-the-official-php-docker-image-approach
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

ADD entrypoint.sh /
RUN chmod +x /entrypoint.sh



USER developer
WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]

