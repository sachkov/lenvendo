SHELL = /bin/bash
container_id := $$(docker ps | grep docker_app | cut -d" " -f1)

#команда должна быть запущена из контекста контейнера, иначе переменные окружения не видны и
# не получится подключится к БД
migration:
	docker exec -it $(container_id) php console make:migrations