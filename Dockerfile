# Usar la imagen base de Ubuntu 
FROM ubuntu:latest

# Actualizar repositorios e instalar paquetes necesarios
RUN DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get install -y \
    tzdata \
    apache2 \
    php \
    mysql-server \
    curl \
    git \
    nodejs \
    npm

# Instalar Composer (para Laravel)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Laravel
RUN composer global require laravel/installer

# Instalar Vue.js
RUN npm install -g @vue/cli

# Configurar MySQL 
RUN service mysql start && \
    mysql -e "CREATE DATABASE farmacia;" && \
    mysql -e "CREATE USER 'root'@'localhost' IDENTIFIED BY 'password';" && \
    mysql -e "GRANT ALL PRIVILEGES ON farmacia.* TO 'root'@'localhost';" && \
    mysql -e "FLUSH PRIVILEGES;"

# Copiar proyecto desde GitHub 
RUN git https://github.com/canteroaa/Farmacia.git /var/www/html/laravel_project

# Establecer el directorio de trabajo
WORKDIR /var/www/html/laravel_project

# Exponer el puerto 80 (Apache)
EXPOSE 80

# Iniciar Apache en el arranque
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

