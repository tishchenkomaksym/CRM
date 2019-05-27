FROM registry.devzone.net/test-check/test-check-nginx:nginx
RUN mkdir -p /application
COPY . /application
RUN chown -R nginx:nginx /application
RUN ["chmod", "+x", "/docker-entrypoint.sh"]
