create database bobs;

use bobs;

CREATE TABLE how_found_bob
( id CHAR(1) NOT NULL PRIMARY KEY,
  how VARCHAR(255) NOT NULL
);

CREATE TABLE orders
( OrderNum INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Date DATETIME NOT NULL,
  TireQty INT NOT NULL,
  OilQty INT NOT NULL,
  SparkQty INT NOT NULL,
  HowFindBobId CHAR(1) NOT NULL,
  Notes VARCHAR(255),

  FOREIGN KEY (HowFindBobId) REFERENCES how_found_bob(id)
);

INSERT INTO how_found_bob VALUES
  ('a', 'I\'m a regular customer'),
  ('b', 'TV advertising'),
  ('c', 'Phone directory'),
  ('d', 'Word of mouth');
