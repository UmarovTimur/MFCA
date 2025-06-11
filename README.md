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
└── wp-content/
    ├── themes/
    │   └── sydney.2.13/
    │       └── [theme files]
    │
    └── plugins/
        └── [plugin files]
```

## Setup Instructions

1. Make sure your theme and plugin files are in the correct directories (`wp-content/themes/sydney.2.13` and `wp-content/plugins`).

2. Run the setup script:

   ```
   chmod +x setup.sh
   ./setup.sh
   ```

3. Access your WordPress site at `http://localhost:8080`

4. Access phpMyAdmin at `http://localhost:8081`
   - Server: `db`
   - Username: `wp_user` (or `root`)
   - Password: `wp_password` (or `root_password`)

## Environment Variables

You can modify the `.env` file to change database credentials and other settings.

## Notes

- The WordPress data and database are persisted in Docker volumes
- The theme and plugins directories are mounted as volumes from your local filesystem
- All passwords are stored in the `.env` file for security

## Stopping the Containers

To stop the containers, run:

```
docker-compose down
```

To completely remove the containers and volumes (this will delete all data):

```
docker-compose down -v
```
