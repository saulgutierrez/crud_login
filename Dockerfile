FROM php:8.1-apache-bullseye

# Instala paquetes adicionales necesarios para PHP y Python
RUN apt-get update && apt-get install -y \
    apache2 \
    python3 \
    python3-pip \
    libapache2-mod-php \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite si usas .htaccess
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html/

# Copiar los archivos del proyecto al contenedor
COPY . .

# Expone el puerto 80 para Apache
EXPOSE 80

# Comando de inicio del servidor Apache
CMD ["apachectl", "-D", "FOREGROUND"]
