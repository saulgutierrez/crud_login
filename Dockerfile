# Usa una imagen base con PHP 8.2 y Apache
FROM php:8.2-apache

# Instala paquetes adicionales necesarios para PHP y Python
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
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
