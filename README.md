
# Revenue calculator
A simple application to show how to work in SOLID principle
Implement one extendable Threshold

@todo
Allow using dynamic threshold via api request
Test coverage and cover app in 100%

# HOW TO RUN APP VIA DOCKER
1. GET DOCKER ON: https://docs.docker.com/get-docker/
2. Make sure port 6785 is unoccupied. (You can change that on command bellow)
3. You can run simple server by typing that command
`docker run -it --rm --name php-application --workdir /var/www/html -p 6785:6785 -v "$PWD":/var/www/html php:7.4-fpm-alpine php -S 0.0.0.0:6785`
`docker exec -it php-application php composer.phar install`
4. You can execute any command via:
`docker exec -it php-application`

# Framework. 
I don't use any php (except codeception)
This project should show that I understand how to write advanced application logic

#API DOC:
Postman export: Recruitment task.postman_collection.json

URL: http://localhost:6785/ (routing not implemented)
METHOD: POST
POST PARAMS:
   - player[] (array)
	    - id (integer)
	    - amount (float)
      - currency (string)

SUCCESS RESPONSE (200)
   - status (integer)
   - response (array)
	   - player[] (array)
	   - id (integer)
	   - revenueAmount (float)
	   - rewardAmount (float)
	   - currency (string)

ERROR RESPONSE (4xx, 5xx):
 - status (integer)
 - message (string)
 