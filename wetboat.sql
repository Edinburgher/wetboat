DROP DATABASE IF EXISTS wetboat;

CREATE DATABASE IF NOT EXISTS wetboat;

USE wetboat;

DROP TABLE IF EXISTS measurements;

DROP TABLE IF EXISTS users;

DROP TABLE IF EXISTS user_coords;

DROP TABLE IF EXISTS settings;

CREATE TABLE measurements
(
  time_measured     DATETIME PRIMARY KEY NOT NULL,
  temperature_air   DECIMAL(5, 2),
  -- decimal(5,2) --> 3 digits before comma, 2 after comma
  temperature_water DECIMAL(5, 2),
  speed_wind        DECIMAL(5, 2),
  speed_boat        DECIMAL(5, 2),
  pressure_air		DECIMAL(8, 2),
  humidity_air		DECIMAL(5, 2),
  lat_boat          DECIMAL(9, 6),
  lon_boat          DECIMAL(9, 6)
)
  ENGINE = innodb;

CREATE TABLE users
(
  id              INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  username        VARCHAR(40),
  hashed_password VARCHAR(128)
)
  ENGINE = innodb;

CREATE TABLE user_coords
(
  id       INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  lat_user DECIMAL(9, 6),
  lon_user DECIMAL(9, 6)
)
  ENGINE = innodb;

CREATE TABLE settings
(
  delay INT
)
  ENGINE = innodb;

DELETE FROM measurements;

DELETE FROM users;

DELETE FROM user_coords;

DELETE FROM settings;

INSERT INTO measurements (time_measured, temperature_air, temperature_water, pressure_air, humidity_air, speed_wind, speed_boat, lat_boat, lon_boat)
VALUES (Now(), 23.23, 19.54,1200, 39, 5.34, 6.57, 47.789431, 13.051115);

SELECT *
FROM measurements;

-- default admin user: 
-- username: admin
-- password: admin
INSERT INTO users (username, hashed_password)
VALUES ('admin', '$2y$10$nJNW8JZJKl67E27q8Mkv/.dhXo.l1dWX/X8yT7jyA2G2l3DL2KTV6');

SELECT *
FROM users;

INSERT INTO user_coords (lat_user, lon_user)
VALUES (47.787655, 13.037071);

INSERT INTO user_coords (lat_user, lon_user)
VALUES (47.787779, 13.037932);

INSERT INTO user_coords (lat_user, lon_user)
VALUES (47.787247, 13.038556);

INSERT INTO user_coords (lat_user, lon_user)
VALUES (47.786835, 13.038043);

INSERT INTO user_coords (lat_user, lon_user)
VALUES (47.787187, 13.036772);

SELECT *
FROM user_coords;

INSERT INTO settings (delay)
VALUES (5); -- default delay

SELECT *
FROM settings;

DROP VIEW IF EXISTS v_newest_measurement;

CREATE VIEW v_newest_measurement
AS
  SELECT *
  FROM measurements
  WHERE time_measured = (SELECT Max(time_measured)
                         FROM measurements);

SELECT *
FROM v_newest_measurement;

SELECT *
FROM (SELECT *
      FROM measurements
      ORDER BY time_measured DESC
      LIMIT 5) test
ORDER BY time_measured;