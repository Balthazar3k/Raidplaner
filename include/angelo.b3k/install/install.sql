INSERT INTO `prefix_config` (`schl`, `typ`, `kat`, `frage`, `wert`, `pos`) VALUES
('currency', 'input', 'Shop', 'Welche Währung', '&euro;', 0);

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
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `prefix_shop_units` (`unit_id`, `unit_short`, `unit_unit`) VALUES
(1, 'stk.', 'Stück'),
(2, 'kg', 'Kilo'),
(3, 'l', 'Liter'),
(4, 'k', 'Kiste');
