FROM php:8.2-apache

# Copiar los archivos a la carpeta pública de Apache
COPY . /var/www/html/

# Configurar Apache para que use el puerto que Render le asigne dinámicamente
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Exponer el puerto
EXPOSE 8080
