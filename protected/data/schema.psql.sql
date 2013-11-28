SET search_path TO itikka;

DROP TABLE itikka.tbl_user;
CREATE TABLE itikka.tbl_user (
    id serial UNIQUE PRIMARY KEY,
    username VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL
);

INSERT INTO tbl_user (username, password, email) VALUES ('test', 'pass', 'test1@example.com');
