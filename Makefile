# Variables
DOCKER_COMPOSE = docker compose

# Commandes
.PHONY: build up down restart logs clean restart-web

build:
	@echo "Building Docker containers..."
	$(DOCKER_COMPOSE) build

up:
	@echo "Starting Docker containers in detached mode..."
	$(DOCKER_COMPOSE) up -d

down:
	@echo "Stopping Docker containers..."
	$(DOCKER_COMPOSE) down

restart:
	@echo "Restarting all Docker containers..."
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d

# Redémarrer uniquement le service 'web'
restart-web:
	@echo "Restarting the 'web' service..."
	$(DOCKER_COMPOSE) restart web

logs:
	@echo "Displaying logs for Docker containers..."
	$(DOCKER_COMPOSE) logs -f

clean:
	@echo "Cleaning up unused Docker resources..."
	docker system prune -f
