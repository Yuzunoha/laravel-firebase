define develop-c
	docker-compose -f docker-compose-develop.yml exec -T php bash -c
endef

define deploy-c
	docker-compose -f docker-compose-deploy.yml exec -T php bash -c
endef

define nginxproxy-c
	docker-compose -f docker-compose-nginxproxy.yml exec -T php bash -c
endef

develop-up:
	docker-compose -f docker-compose-develop.yml up -d
develop-ps:
	docker-compose -f docker-compose-develop.yml ps
develop-down:
	docker-compose -f docker-compose-develop.yml down
develop-bash:
	docker-compose -f docker-compose-develop.yml exec php bash
develop-init:
	echo DOCKER_UID=`id -u` > .env
	docker-compose -f docker-compose-develop.yml build --no-cache
	docker-compose -f docker-compose-develop.yml up -d
	$(develop-c) 'composer install'
	$(develop-c) 'touch database/database.sqlite'
	$(develop-c) 'chmod 777 -R storage bootstrap/cache database'
	echo 'dbコンテナの起動を60秒待つ';
	sleep 60
	$(develop-c) 'php artisan migrate:refresh --seed'

up:
	docker-compose -f docker-compose-deploy.yml up -d
ps:
	docker-compose -f docker-compose-deploy.yml ps
down:
	docker-compose -f docker-compose-deploy.yml down
bash:
	docker-compose -f docker-compose-deploy.yml exec php bash
init:
	docker-compose -f docker-compose-deploy.yml build --no-cache
	docker-compose -f docker-compose-deploy.yml up -d
	$(deploy-c) 'composer install'
	$(deploy-c) 'touch database/database.sqlite'
	$(deploy-c) 'chmod 777 -R storage bootstrap/cache database'
	echo 'dbコンテナの起動を60秒待つ';
	sleep 60
	$(deploy-c) 'php artisan migrate:refresh --seed'
phpunit:
	$(deploy-c) 'vendor/bin/phpunit'
mysql:
	docker-compose -f docker-compose-deploy.yml exec mysql bash -c 'mysql -u root -proot docker_db'

nginxproxy-up:
	docker-compose -f docker-compose-nginxproxy.yml up -d
nginxproxy-ps:
	docker-compose -f docker-compose-nginxproxy.yml ps
nginxproxy-down:
	docker-compose -f docker-compose-nginxproxy.yml down
nginxproxy-bash:
	docker-compose -f docker-compose-nginxproxy.yml exec php bash
nginxproxy-init:
	docker-compose -f docker-compose-nginxproxy.yml build --no-cache
	docker-compose -f docker-compose-nginxproxy.yml up -d
	$(nginxproxy-c) 'composer install'
	$(nginxproxy-c) 'touch database/database.sqlite'
	$(nginxproxy-c) 'chmod 777 -R storage bootstrap/cache database'
	echo 'dbコンテナの起動を60秒待つ';
	sleep 60
	$(nginxproxy-c) 'php artisan migrate:refresh --seed'
