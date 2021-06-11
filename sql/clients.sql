--Author: Tom Zielinski
--Date: 10/22/2020
--Webd3202
--lab02 clients

DROP SEQUENCE IF EXISTS client_id_seq;
CREATE SEQUENCE client_id_seq START 1;

-- DROP'ping tables clear out any existing data
DROP TABLE IF EXISTS clients;

-- CREATE the table, note that id has to be unique, and you must have a name
CREATE TABLE clients(
	clientID INT PRIMARY KEY DEFAULT nextval('client_id_seq'),
    first_name VARCHAR(128),
    last_name VARCHAR(128),
    email VARCHAR(255) UNIQUE,
    phoneNumber VARCHAR(255),
    logo_path VARCHAR(255)
);

GRANT ALL ON clients TO faculty;

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Chris', 'Mathews', 'ChrisMathews@dcmail.ca', '9051231234', 'user.jpg');

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Alex', 'Johnson', 'AJohnson@dcmail.ca', '9059879876', 'user.jpg');

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Thomas', 'Chapman', 'tchapman@dcmail.ca', '9051231234', 'user.jpg');

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Calvin', 'May', 'cmay@dcmail.ca', '9059879876', 'user.jpg');

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Mathew', 'Zielinski', 'm.zielinski@dcmail.ca', '9051353579', 'user.jpg');

INSERT INTO clients(first_name, last_name, email, phoneNumber, logo_path) 
    VALUES('Alex', 'xela', 'axela@dcmail.ca', '9055435678', 'user.jpg');

    SELECT * FROM clients;