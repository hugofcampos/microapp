# Microapp

Requirements

- [Docker](https://www.docker.com/)
- [Docker-compose](https://docs.docker.com/compose/)

Just run:

```bash
$ docker-compose build
$ docker-compose up -d
```

Then, you need to set the application up.

Installing app dependencies

```bash
docker exec web bash  -c "composer install"
```

Running app

```bash
docker exec web bash  -c "php -S 0.0.0.0:8000 ./web/index.php"
```

Access http://localhost:8000
