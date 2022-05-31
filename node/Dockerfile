FROM node:17

ADD entrypoint.sh /
RUN chmod +x /entrypoint.sh

USER node

WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]