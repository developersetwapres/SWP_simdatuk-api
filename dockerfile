# Use an official PHP 8.3 FPM with Alpine Linux as a parent image
FROM php:8.3-fpm-alpine

# Set the working directory to /var/www/html
WORKDIR /app

# Install system dependencies
RUN apk --update --no-cache add \
  bash \
  libzip-dev \
  unzip \
  git \
  curl \
  nginx \
  supervisor

# Install PHP extensions
RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
  ctype \
  curl \
  dom \
  fileinfo \
  filter \
  hash \
  mbstring \
  openssl \
  pcre \
  pdo \
  session \
  tokenizer \
  xml \
  @composer

# Copy the rest of the application code to the container
COPY . .

# Copy .env file
COPY .env .env

# Change ownership for application code
RUN chown -R www-data:www-data /app

# Install application dependencies
RUN composer install --no-scripts --no-autoloader

# Generate the optimized autoload files
RUN composer clear-cache && composer dump-autoload --no-scripts --optimize

# Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# # Start Supervisord
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Expose port 80 for Nginx
EXPOSE 80

# Health check command
HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://127.0.0.1:9000/ping || exit 1