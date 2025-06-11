#!/bin/bash

# Generate secure salt keys for WordPress
generate_wp_keys() {
    echo "Generating secure WordPress keys..."
    curl -s https://api.wordpress.org/secret-key/1.1/salt/ > wp-keys.txt
    sed -i '/put your unique phrase here/d' wp-config.php
    sed -i "/Authentication Unique Keys and Salts/r wp-keys.txt" wp-config.php
    rm wp-keys.txt
    echo "Keys generated successfully!"
}

# Create necessary directories if they don't exist
mkdir -p wp-content/themes/sydney.2.13
mkdir -p wp-content/plugins

# Generate WordPress keys
generate_wp_keys

# Start containers
echo "Starting Docker containers..."
docker-compose up -d

echo "Setup complete! Your WordPress site is now available at http://localhost:8080"
echo "phpMyAdmin is available at http://localhost:8081"
echo "- Use the following credentials for phpMyAdmin:"
echo "  - Server: db"
echo "  - Username: wp_user (or root)"
echo "  - Password: wp_password (or root_password)"