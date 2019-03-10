-- Create database

CREATE DATABASE qubiz_test;

-- and use...

USE qubiz_test;

-- Create table for data
CREATE TABLE product (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  description varchar(255) NOT NULL,
  code varchar(10) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  discontinued_at datetime DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores product data';
