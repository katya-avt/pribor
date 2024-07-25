FROM composer:latest

WORKDIR /var/www/pribor

ENTRYPOINT ["composer", "--ignore-platform-reqs"]
