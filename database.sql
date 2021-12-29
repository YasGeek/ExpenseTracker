-- DATABASE: expense

CREATE TABLE users(
    username VARCHAR(50),
    PRIMARY KEY(username),
    createdOn DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    firstname VARCHAR(30),
    lastname VARCHAR(30),
    password CHAR(80) NOT NULL
); 

CREATE TABLE expenses(
    id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(id),
    date DATE NOT NULL DEFAULT (CURRENT-DATE),
    amount FLOAT NOT NULL,
    description VARCHAR(100),
    type ENUM('income', 'expense') DEFAULT 'income',
    username VARCHAR(50) NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username)
);

-- password: 1234
INSERT INTO users (username, password) values ('user', '$2y$10$bplJKAGUwwW9GLH2gInw.uv.ueA83vTTwnEU.h6VnzGoX35Vk7GUu');