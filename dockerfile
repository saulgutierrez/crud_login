# Usa una imagen base con Apache y PHP
FROM php:8.1-apache

# Instala paquetes adicionales necesarios para PHP y Python
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    && rm -rf /var/lib/apt/lists/*

# Copia los archivos del proyecto PHP al directorio de Apache
COPY . /var/www/html/

# Expone el puerto 80 para el servidor web
EXPOSE 80

# Comando de inicio del servidor Apache
CMD ["apache2-foreground"]