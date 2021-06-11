--Author: Tom Zielinski
--Date: 10/22/2020
--Webd3202
--lab01_users
CREATE EXTENSION IF NOT EXISTS pgcrypto;

DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq START 1000;

-- DROP'ping tables clear out any existing data
DROP TABLE IF EXISTS users;

-- CREATE the table, note that id has to be unique, and you must have a name
CREATE TABLE users(
	id INT PRIMARY KEY DEFAULT nextval('users_id_seq'),
	email_address VARCHAR(255) UNIQUE,
	first_name VARCHAR(128),
	last_name VARCHAR(128),
	password VARCHAR(255) NOT NULL,
	enrol_date TIMESTAMP,
	last_access TIMESTAMP,
    enable BOOLEAN,
    type VARCHAR(2)
);

GRANT ALL ON users TO faculty;

INSERT INTO users(email_address, first_name, last_name, password, enrol_date, last_access, enable, type) 
    VALUES('jdoe@durhamcollege.ca', 'John', 'Doe', crypt('testpass', gen_salt('bf')), '2020-9-9 10:30:11', '2020-09-09 16:49:30', 'true', 's');

INSERT INTO users(email_address, first_name, last_name, password, enrol_date, last_access, enable, type) 
    VALUES('tom.zielinski@durhamcollege.ca', 'Tom', 'Zielinski', crypt('password123', gen_salt('bf')), '2020-9-9 10:45:42', '2020-09-09 16:49:30', 'true', 's');

INSERT INTO users(email_address, first_name, last_name, password, enrol_date, last_access, enable, type) 
    VALUES('ChantalHo@gmail.com', 'Chantal', 'ho', crypt('password123', gen_salt('bf')), '2020-9-9 12:48:7', '2020-09-09 10:50:58', 'true', 's');

INSERT INTO users(email_address, first_name, last_name, password, enrol_date, last_access, enable, type) 
    VALUES('JCollins@gmail.com', 'James', 'Collins', crypt('password123', gen_salt('bf')), '2020-10-23 12:48:7', '2020-10-23 10:50:58', 'true', 'a');

SELECT * FROM users;