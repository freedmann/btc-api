FROM nginx
add docker/conf/vhost.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html/public
