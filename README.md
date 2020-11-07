# reward-plan
A simple application to show how to work in SOLID principle

# HOW TO RUN APP VIA DOCKER
1. GET DOCKER ON: https://docs.docker.com/get-docker/
2. Make sure port 6785 is unoccupied. (You can change that on command bellow)
3. You can run simple server by typing that command
`docker run -it --rm --name php-application --workdir /var/www/html -p 6785:6785 -v "$PWD":/var/www/html php:7.4-fpm-alpine php -S 0.0.0.0:6785`
4. You can execute any command via:
`docker exec -it php-application`