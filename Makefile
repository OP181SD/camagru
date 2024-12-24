# Variables
DOCKER_COMPOSE = docker-compose

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

rebuild:
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up --build -d

logs:
	$(DOCKER_COMPOSE) logs -f

ps:
	$(DOCKER_COMPOSE) ps

clean:
	$(DOCKER_COMPOSE) down -v --rmi all --remove-orphans

restart:
	$(DOCKER_COMPOSE) restart

.PHONY: up down rebuild logs ps clean restart

rebuild-web:
	$(DOCKER_COMPOSE) stop web
	$(DOCKER_COMPOSE) up --build -d web