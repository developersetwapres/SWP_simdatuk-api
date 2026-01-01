# Use an official PHP 8.3 FPM with Alpine Linux as a parent image
FROM php:8.3-fpm

# Set the working directory to /var/www/html
WORKDIR /app

RUN apt-get update && apt-get install -y \
  bash \
  libzip-dev \
  unzip \
  git \
  curl \
  nginx \
  supervisor \
  vim \
  && rm -rf /var/lib/apt/lists/*

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
  pdo_mysql \
  session \
  tokenizer \
  xml \
  gd \
  zip \
  @composer

# Copy the rest of the application code to the container
COPY . .

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install application dependencies
RUN composer install --no-scripts --no-autoloader

# Generate the optimized autoload files
RUN composer clear-cache && composer dump-autoload --no-scripts --optimize

# Make symlink to from storage path to public path
RUN php artisan storage:link

# Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/local.conf /etc/nginx/conf.d

# Supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Change ownership for application code
RUN chown -R www-data:www-data /app

# Start Supervisord
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Expose port 8080 for Nginx
EXPOSE 8080

# Health check command
HEALTHCHECK --interval=300s --timeout=60s CMD curl -f http://127.0.0.1/api || exit 1
