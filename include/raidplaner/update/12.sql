INSERT INTO `prefix_raid_zeit` (`id`, `zeit`) VALUES
(1, 'Mo 15:00-16:00'),
(2, 'Fr 16:00-22:00');

CREATE TABLE IF NOT EXISTS `prefix_raid_zeiten` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default 0,
  `zid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
