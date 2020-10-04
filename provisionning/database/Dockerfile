# provisionning/database/Dockerfile

FROM mariadb:latest
COPY init.sql /docker-entrypoint-initdb.d/
CMD ["mysqld"]
EXPOSE 3306