DROP TABLE `prefix_raid_zeit`, `prefix_raid_zeitgruppen`, `prefix_raid_zeitgruppen_chars`;

ALTER TABLE `prefix_raid_chars`
  CHANGE `s1` `s1` INT NOT NULL, 
  CHANGE `s2` `s2` INT NOT NULL,
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
  DROP `aufnahmestop`,
  ADD `style` TEXT NOT NULL;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

INSERT INTO `prefix_raid_classification` (`id`, `class_id`, `name`, `search`) VALUES
(1, 1, 'Waffen', 0),
(2, 1, 'Furor', 0),
(3, 1, 'Schutz', 0),
(4, 2, 'Heilig', 0),
(5, 2, 'Schutz', 0),
(6, 2, 'Vergeltung', 0),
(7, 3, 'Tierherrschaft', 0),
(8, 3, 'Treffsicherheit', 0),
(9, 3, 'Überleben', 0),
(10, 4, 'Mäucheln', 0),
(11, 4, 'Kampf', 0),
(12, 4, 'Täuschung', 0),
(13, 5, 'Disziplin', 0),
(14, 5, 'Heilig', 0),
(15, 5, 'Schatten', 0),
(16, 6, 'Blut', 0),
(17, 6, 'Frost', 0),
(18, 6, 'Unheilig', 0),
(19, 7, 'Elementar', 0),
(20, 7, 'Verstärkung', 0),
(21, 7, 'Wiederherstellung', 0),
(22, 8, 'Arcan', 0),
(23, 8, 'Feuer', 0),
(24, 8, 'Eis', 0),
(25, 9, 'Gebrechen', 0),
(26, 9, 'Dämonologie', 0),
(27, 9, 'Zerstörung', 0),
(28, 10, 'Braumeister', 0),
(29, 10, 'Nebelwirker', 0),
(30, 10, 'Windläufer', 0),
(31, 11, 'Gleichgewicht', 0),
(32, 11, 'Wilder Kampf', 0),
(33, 11, 'Wiederherstellung', 0);

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