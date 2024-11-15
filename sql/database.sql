
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    major VARCHAR(100) NOT NULL
);
CREATE  TABLE USERS(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(250) NOT NULL,
    password VARCHAR(250) NOT ,
    token VARCHAR(255)
);

INSERT INTO USERS (id, username, password, token)
VALUES (default, 'Mahaliana', SHA2('562', 256), NULL);