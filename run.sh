#!/bin/bash

user_id=$(id -u)
group_id=$(id -g)

USER_ID=${user_id} GROUP_ID=${group_id} docker-compose build
USER_ID=${user_id} GROUP_ID=${group_id} docker-compose up -d
USER_ID=${user_id} GROUP_ID=${group_id} docker-compose exec php composer install
