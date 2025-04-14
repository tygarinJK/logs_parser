# Symfony Logs Parser

Includes:

- PHP 8.2 (FPM)
- Nginx
- MariaDB 10.6+
- Symfony 7.2
- Custom ports (HTTP: `8088`, DB: `3307`)
- One-command startup with `Makefile`

---

## Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [GNU Make](https://www.gnu.org/software/make/)
- [Symfony CLI (optional)](https://symfony.com/download)

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
```

Then open http://localhost:8088 in your browser.