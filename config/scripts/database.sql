CREATE TABLE puzzles
(
  id INT(11) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  seed INT(11) NOT NULL,
  difficulty INT(11) NOT NULL,
  data TEXT NOT NULL
);
CREATE TABLE user_puzzles
(
  id INT(11) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_id INT(11) unsigned NOT NULL,
  puzzle_id INT(11) unsigned NOT NULL,
  completed_at DATETIME NOT NULL,
  state TEXT,
  CONSTRAINT user_puzzles_users_id_fk FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT user_puzzles_puzzles_id_fk FOREIGN KEY (puzzle_id) REFERENCES puzzles (id)
);
CREATE INDEX user_puzzles_users_id_fk ON user_puzzles (user_id);
CREATE INDEX user_puzzles_puzzles_id_fk ON user_puzzles (puzzle_id);
CREATE TABLE users
(
  id INT(11) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) DEFAULT '' NOT NULL,
  email VARCHAR(255) DEFAULT '',
  password VARCHAR(60) DEFAULT '' NOT NULL
);
CREATE UNIQUE INDEX users_email_uindex ON users (email);
CREATE UNIQUE INDEX users_name_uindex ON users (name);
