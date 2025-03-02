.PHONY: up
up:
	@docker compose up -d

.PHONY: down
down:
	@docker compose down

.PHONY: rebuild
rebuild:
	@docker compose build --no-cache

.PHONY: bash
bash:
	@docker exec -it basic-chat-php bash

.PHONY: start
start:
	@docker compose up -d && docker exec -it basic-chat-php bash
