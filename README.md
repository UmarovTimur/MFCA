# WordPress Docker Setup

This Docker configuration allows you to run your WordPress site with the Sydney 2.13 theme and your custom plugins, along with MySQL database and phpMyAdmin for database management.

## Prerequisites

- Docker and Docker Compose installed on your system
- Your Sydney 2.13 theme files in `wp-content/themes/sydney.2.13`
- Your plugin files in `wp-content/plugins`

## Directory Structure

```
project-root/
│
├── docker-compose.yml
├── Dockerfile
├── .env
├── wp-config.php
├── setup.sh
│
└── sydney.2.13/
│    └── [theme files]
│
└── plugins/
│   └── [plugin files]
│
└── uploads/
│   └── [media]
│
```
