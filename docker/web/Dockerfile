FROM webdevops/php-nginx-dev:7.4

WORKDIR /app
ENV COMPOSER_MEMORY_LIMIT=-1
RUN echo 'cd_integrator' > /etc/hostname

RUN apt-get update
RUN apt install -y iputils-ping \
    nano \
    vim \
    htop \
    librabbitmq-dev

RUN apt install -y zlib1g-dev chromium
RUN composer global require hirak/prestissimo
RUN docker-php-ext-enable amqp
