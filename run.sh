#!/bin/bash
docker kill sti_project
docker rm sti_project
docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018
docker exec -u root sti_project service nginx start
docker exec -u root sti_project service php5-fpm start
firefox localhost:8080/message.php &
