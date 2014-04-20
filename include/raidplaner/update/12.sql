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

ALTER TABLE `prefix_raid_klassen`
  DROP `s1b`,
  DROP `s2b`,
  DROP `s3b`,
  DROP `aufnahmestop`;

ALTER TABLE `prefix_raid_dkps`
  DROP `pm`;

ALTER TABLE `prefix_raid_inzen` ADD `small` VARCHAR(10) NOT NULL AFTER `id`;

INSERT INTO `prefix_raid_dkps` (`inzen`, `name`, `dkp`) VALUES
(0, 'Anwesenheit', 125),
(0, 'Raid Start', 50),
(0, 'Bosskill', 50),
(0, 'Bosskill, first Try', 75),
(0, 'Raid Ende', 125),
(0, 'Item', -350);

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

CREATE TABLE IF NOT EXISTS `prefix_raid_classification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `search` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

UPDATE `prefix_config` SET `frage`='Bilder in der Gruppen übersicht anzeigen?' WHERE `schl` = 'show_img_raidgruppen';
UPDATE `prefix_config` SET `frage`='Details der Gruppen anzeigen (raidlist.php)' WHERE `schl` = 'show_details_raidgruppen';
UPDATE `prefix_config` SET `frage`='User brauchen Raidzeit angaben um sich Anmelden zu k&ouml;nnen?' WHERE `schl` = 'isRaidKalender';
UPDATE `prefix_config` SET `frage`='min. Anmeldezeit' WHERE `schl` = 'mams';

DELETE `prefix_config` WHERE `schl`='domain';
DELETE `prefix_config` WHERE `schl`='pzBalkenStyle';
DELETE `prefix_config` WHERE `schl`='nextlastraid';

/*
SELECT 
    a.id, a.class_id, a.name, a.search,
    b.klassen as class_name
FROM ic1_raid_classification AS a
    LEFT JOIN ic1_raid_klassen AS b ON a.class_id = b.id
*/