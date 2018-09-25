/* ONLY USED FOR TESTING */
DROP TABLE IF EXISTS user;

/* Creates new 'user' table */
CREATE TABLE IF NOT EXISTS user (
  id int NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  datetime_joined DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

/* Triggers username and email to be in lowercase */
CREATE TRIGGER user_username_lower_case BEFORE INSERT ON user
FOR EACH ROW
SET
NEW.username = LOWER(NEW.username),
NEW.email = LOWER(NEW.email);

/* Some dummy data */
INSERT INTO user (username, password, email)
  VALUES ("adMIN", "password", "admin@Example.com");
INSERT INTO user (username, password, email)
  VALUES ("konGEbra", "password", "KONG@Example.com");