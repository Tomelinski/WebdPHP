--Author: Tom Zielinski
--Date: 10/22/2020
--Webd3202
--lab02 calls

DROP SEQUENCE IF EXISTS calls_id_seq;
CREATE SEQUENCE calls_id_seq START 1;

-- DROP'ping tables clear out any existing data
DROP TABLE IF EXISTS calls;

-- CREATE the table, note that id has to be unique, and you must have a name
CREATE TABLE calls(
	callID INT PRIMARY KEY DEFAULT nextval('calls_id_seq'),
    clientID INT REFERENCES clients(clientID) NOT NULL,
    userID INT REFERENCES users(id) NOT NULL,
    callTime TIMESTAMP
);

GRANT ALL ON calls TO faculty;

INSERT INTO calls(clientID, userID, callTime) 
    VALUES(1, 1003,  '2020-10-23 11:52:30');

INSERT INTO calls(clientID, userID, callTime) 
    VALUES(2, 1003,  '2020-10-23 11:52:30');

SELECT * FROM calls;