DROP DATABASE IF EXISTS mvclogin;

CREATE DATABASE mvclogin;

USE mvclogin;

CREATE USER 'mvcuser'@'%' IDENTIFIED BY 'jairah'; GRANT ALL PRIVILEGES ON `mvclogin`.* TO 'mvcuser'@'%';

-- postgres --
-- CREATE DATABASE blog;
-- ALTER DATABASE blog SET timezone to 'Asia/Manila';
-- CREATE USER blogger01 WITH PASSWORD 'blogger01';
-- GRANT ALL PRIVILEGES ON DATABASE blog to blogger01;

-- command to login with username: `psql -h localhost -U blogger01 -d blog`
