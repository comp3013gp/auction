CREATE DATABASE auction_site
  DEFAULT COLLATE utf8_general_ci;
USE auction_site;

CREATE TABLE user (
  user_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  user_type enum('buyer','seller') NOT NULL,
  name varchar(16) NOT NULL,
  email_address varchar(255) NOT NULL,
  password varchar(16) NOT NULL,
  created_at timestamp NOT NULL DEFAULT 0,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY(user_id),
  INDEX(name, email_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE rating (
  rating_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(10) unsigned NOT NULL,
  rating enum('0','1','2','3','4','5') NOT NULL,
  comment varchar(255) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT 0,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY(rating_id),
  FOREIGN KEY(user_id)
    REFERENCES user(user_id),
  INDEX(rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE category (
  category_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,

  PRIMARY KEY(category_id),
  UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE item (
  item_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  owner_id int(10) unsigned NOT NULL,
  category_id int(10) unsigned NOT NULL,
  name varchar(50) NOT NULL,
  description varchar(255) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT 0,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY(item_id),
  FOREIGN KEY(owner_id)
    REFERENCES user(user_id),
  FOREIGN KEY(category_id)
    REFERENCES category(category_id),
  UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE auction (
  auction_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  seller_id int(10) unsigned NOT NULL,
  item_id int(10) unsigned NOT NULL,
  start_price decimal(10,2) NOT NULL,
  current_price decimal(10,2) NOT NULL,
  reserve_price decimal(10,2) NOT NULL,
  end_date datetime NOT NULL,
  view_count int(10) unsigned NOT NULL DEFAULT '0',
  created_at timestamp NOT NULL DEFAULT 0,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY(auction_id),
  FOREIGN KEY(seller_id)
    REFERENCES user(user_id),
  FOREIGN KEY(item_id)
    REFERENCES item(item_id),
  INDEX(end_date),
  INDEX(view_count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE bid (
  bid_id int(10) unsigned not null AUTO_INCREMENT,
  bidder_id int(10) unsigned NOT NULL,
  auction_id int(10) unsigned NOT NULL,
  price decimal(10,2) NOT NULL,
  created_at timestamp NOT NULL DEFAULT 0,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY(bid_id),
  FOREIGN KEY(bidder_id)
    REFERENCES user(user_id),
  FOREIGN KEY(auction_id)
    REFERENCES auction(auction_id),
  INDEX(price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

USE mysql;

GRANT ALL PRIVILEGES ON *.* TO 'comp3013-gp'@'localhost' IDENTIFIED BY PASSWORD '*85FBE1F41A28D0D54A8489CD4FBE0FEBA476DDB4' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON `auction_site`.* TO 'comp3013-gp'@'localhost' WITH GRANT OPTION;
