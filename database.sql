CREATE DATABASE shorturl_db;

USE shorturl_db;

CREATE TABLE urls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  short_url VARCHAR(10) NOT NULL,
  long_url TEXT NOT NULL,
  click_count INT DEFAULT 0
);

CREATE TABLE click_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  short_url VARCHAR(10) NOT NULL,
  click_count INT DEFAULT 0
);
