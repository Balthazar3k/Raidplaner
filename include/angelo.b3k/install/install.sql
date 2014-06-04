INSERT INTO `prefix_config` (`schl`, `typ`, `kat`, `frage`, `wert`, `pos`) VALUES
('currency', 'input', 'Shop', 'Welche Währung', '&euro;', 0);

INSERT INTO `prefix_config` (`schl`, `typ`, `kat`, `frage`, `wert`, `pos`) VALUES
('shop_order_email', 'input', 'Shop', 'Shop Absender eMail', 'your@shop.de', 0),
('email_noreplay', 'input', 'Shop', 'Automatisch generierte eMail', 'noreplay@shop.de', 0),
('min_purchasing_price', 'input', 'Shop', 'Minimum Preis für eine Lieferung', '15.00', 0);

INSERT INTO `prefix_modules` (`url`, `name`, `gshow`, `ashow`, `fright`) VALUES
('shop', 'Shop', 1, 1, 1),
('shop-category', 'Shop - Category', 1, 1, 1),
('shop-article', 'Shop - Artikel', 1, 1, 1);

CREATE TABLE IF NOT EXISTS `prefix_shop_articles` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_category` int(11) NOT NULL,
  `article_image` text NOT NULL,
  `article_unit` int(11) NOT NULL,
  `article_amount` int(11) NOT NULL,
  `article_number` int(11) DEFAULT NULL,
  `article_name` text NOT NULL,
  `article_description` text NOT NULL,
  `article_netprice` decimal(14,2) NOT NULL,
  `article_tax` int(14) NOT NULL,
  `article_discount` int(14) NOT NULL,
  `article_append` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;

CREATE TABLE IF NOT EXISTS `prefix_shop_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_sub` int(11) NOT NULL DEFAULT '0',
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `category_image` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_shop_units` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_short` varchar(10) NOT NULL,
  `unit_unit` varchar(64) NOT NULL,
  `unit_text` text NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `prefix_shop_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_uid` int(11) NOT NULL,
  `address_company` varchar(200) NOT NULL,
  `address_first_name` varchar(200) NOT NULL,
  `address_last_name` varchar(200) NOT NULL,
  `address_street` varchar(200) NOT NULL,
  `address_street_nr` int(11) NOT NULL,
  `address_zipcode` int(11) NOT NULL,
  `address_place` varchar(200) NOT NULL,
  `address_mobil` varchar(30) NOT NULL,
  `address_phone` varchar(30) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_shop_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_type` int(11) NOT NULL,
  `order_user` int(11) NOT NULL,
  `order_address` int(11) NOT NULL,
  `order_payment` int(1) NOT NULL,
  `order_price` decimal(14,2) NOT NULL,
  `order_process` int(11) NOT NULL DEFAULT '0',
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;

CREATE TABLE IF NOT EXISTS `prefix_shop_order_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_amount` int(11) NOT NULL,
  `user_price` decimal(14,2) NOT NULL,
  `article_id` int(11) NOT NULL,
  `article_amount` int(11) NOT NULL,
  `article_price` decimal(14,2) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_process` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `prefix_shop_units` (`unit_id`, `unit_short`, `unit_unit`, `unit_text`) VALUES
(1, 'stk.', 'Stück', 'das Stück'),
(2, 'kg', 'Kilo', 'das Kilo'),
(3, 'l', 'Liter', 'der Liter'),
(4, 'k', 'Kiste', 'die Kiste');