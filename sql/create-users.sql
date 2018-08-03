USE mvclogin;

CREATE TABLE users (
	id						int						AUTO_INCREMENT PRIMARY KEY,
	name					varchar(50),
	email					varchar(255)	UNIQUE,
	password_hash	varchar(255)
);

-- postgres --
-- CREATE TABLE users (
-- 	id										SERIAL PRIMARY KEY,
-- 	name									VARCHAR(50),
-- 	email									VARCHAR(255),
-- 	password_hash 				VARCHAR(255),
-- 	password_reset_hash		VARCHAR(64),
-- 	password_reset_expiry TIMESTAMP,
-- 	activation_hash				VARCHAR(64),
-- 	is_actie							SMALLINT DEFAULT 0,
-- 	UNIQUE(activation_hash),
-- 	UNIQUE(email),
-- 	UNIQUE(password_reset_hash)
-- );

-- CREATE TABLE remembered_logins (
-- 	token_hash VARCHAR(64),
-- 	user_id INT REFERENCES users ON DELETE CASCADE ON UPDATE CASCADE,
-- 	expires_at TIMESTAMP,
-- 	PRIMARY KEY (token_hash)
-- );

