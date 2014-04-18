DROP TABLE `prefix_raid_zeit`, `prefix_raid_zeitgruppen`, `prefix_raid_zeitgruppen_chars`;

ALTER TABLE `prefix_raid_chars`
  DROP `s3`,
  DROP `alter`,
  DROP `rlname`,
  DROP `mberuf`,
  DROP `mskill`,
  DROP `sberuf`,
  DROP `sskill`,
  DROP `pvp`,
  DROP `raiden`,
  DROP `punkte`,
  DROP `teamspeak`;

CREATE TABLE IF NOT EXISTS `prefix_raid_zeit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weekday` varchar(15) NOT NULL,
  `start` varchar(5) NOT NULL DEFAULT '00:00',
  `inv` varchar(5) NOT NULL DEFAULT '00:00',
  `end` varchar(5) NOT NULL DEFAULT '00:00',
  `options` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_zeit_charakter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

UPDATE `prefix_config` SET `frage`='Bilder in der Gruppen übersicht anzeigen?' WHERE `schl` = 'show_img_raidgruppen';
UPDATE `prefix_config` SET `frage`='Details der Gruppen anzeigen (raidlist.php)' WHERE `schl` = 'show_details_raidgruppen';
UPDATE `prefix_config` SET `frage`='User brauchen Raidzeit angaben um sich Anmelden zu k&ouml;nnen?' WHERE `schl` = 'isRaidKalender';
UPDATE `prefix_config` SET `frage`='min. Anmeldezeit' WHERE `schl` = 'mams';

DELETE `prefix_config` WHERE `schl`='domain';
DELETE `prefix_config` WHERE `schl`='pzBalkenStyle';
DELETE `prefix_config` WHERE `schl`='nextlastraid';