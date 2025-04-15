# Symfony Logs Parser

Includes:

- PHP 8.2 (FPM)
- Nginx
- MariaDB 10.6+
- Symfony 7.2
- Custom ports (HTTP: `8088`, DB: `3307`)

---

## Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Quick Start

```bash
# Clone the repo
git clone https://github.com/your-name/your-repo.git
cd your-repo

# Start the environment and build containers
make up

# Install PHP dependencies via Composer
make install

# Create the database
make console args=doctrine:database:create

# Run migrations
make console args=doctrine:migrations:migrate

# Start the message consuming
docker compose exec php bin/console messenger:consume async -vv

# Parse the logs file
docker compose exec php bin/console app:import-logs /var/logs/logs.log
```

Then open http://localhost:8088/count in your browser to see the number of log messages.
You can also use such filter as:
- `/count?serviceNames[]=`
- `/count?statusCode=`
- `/count?startDate=`
- `/count?endDate=`