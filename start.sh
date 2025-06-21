#!/bin/bash

cd /var/www

# Copy .env if not present
if [ ! -f ".env" ]; then
  echo "Creating .env file..."
  cp .env.example .env
fi

# Set DB credentials (optional – usually already in .env)
sed -i "s/DB_DATABASE=.*/DB_DATABASE=finance/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=root/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=secret/" .env
sed -i "s/DB_HOST=.*/DB_HOST=mysql/" .env

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until nc -z mysql 3306; do
  sleep 2
done

# Install dependencies if not already
if [ ! -d "vendor" ]; then
  composer install
fi

# Generate key (only if not already set)
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000

