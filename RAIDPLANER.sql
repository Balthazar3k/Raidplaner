INSERT INTO `prefix_config` (`schl`, `typ`, `kat`, `frage`, `wert`, `pos`) VALUES 
('show_img_raidgruppen', 'r2', 'Raidplaner', 'Sollen Raidgruppen Bilder im Raidplaner Angezeigt werden?', '0', 0),
('show_details_raidgruppen', 'r2', 'Raidplaner', 'Sollen die Raidgruppen Details Angezeigt werden im Raidplaner?', '1', 0),
('meps_raid', 'input', 'Raidplaner', 'Wieviele Raids sollen angezeigt werden?', '10', 0),
('meps_araid', 'input', 'Raidplaner', 'Wieviele Raids sollen im Admin Bereich angezeigt werden', '15', 0),
('realm', 'input', 'Raidplaner', 'Realm (WoW Server)', 'Khaz''goroth', 0),
('mams', 'input', 'Raidplaner', 'Mindest Anmelde zeit ( bsp. 1std vor Raid )?', '2', 0),
('domain', 'input', 'Raidplaner', 'Domain ( http://www.musterman.de/unterordner )?', 'http://www.balthazar3k.de/raids/', 0),
('char_rang_edit', 'input', 'Raidplaner', 'Ab welchen rang darf er andere Chars rang &auml;nderungen vornehmen?', '9', 0),
('maxchars', 'input', 'Raidplaner', 'Wieviel Chars darf ein User besitzen?', '4', 0),
('addchar', 'grecht', 'Raidplaner', 'Ab welchem Rang darf der User Chars Anlegen?', '-4', 0),
('isRaidKalender', 'r2', 'Raidplaner', 'Muss ein Char Raidkalender Eintr&auml;ge haben um am Raid Teil zu nehmen?', '0', 0),
('isRaidSkillung', 'r2', 'Raidplaner', 'Muss ein Char seine Skillung eintragen um am Raid Teilnehmen zu d&uuml;rfen?', '1', 0),
('nextlastraid', 'r2', 'Raidplaner', 'Sollen soviele Last Raids Angezeigt werden wie Next Raids? (Ansonsten 5 Last Raids)', '0', 0),
('bewerbung', 'textarea', 'Raidplaner', 'Bewerbung''s Msg', 'Um dich bei uns Bewerben zu K&ouml;nnen musst du dich [url={domain}index.php?user-regist]Regestrieren[/url]!\r\n\r\nWenn du Jedoch schon Rang Member hast kannst du dir einen Char auf der Chars Seite Erstellen. [url={domain}index.php?chars-newchar]Char Erstellen[/url]', 0),
('pzBalkenStyle', 'input', 'Raidplaner', 'Style f&uuml;r den Pronzent Balken', 'background-color: #00CC00; font-size: 9px;', 0),
('canSeeStamm', 'r2', 'Raidplaner', 'Solle "Alle" User die Stamm Gruppen und Stamm Raids sehen?', '0', 0),
('version', 'input', 'Raidplaner', 'Version vom Raidplaner, bitte nichts verstellen wenn Sie keine ahnung davon haben! (Damit werden Updates gesteuert)', '11', '0');

CREATE TABLE IF NOT EXISTS `prefix_modules` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `url` varchar(20) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `gshow` tinyint(1) NOT NULL default '0',
  `ashow` tinyint(1) NOT NULL default '0',
  `fright` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='powered by ilch.de' AUTO_INCREMENT=812 ;

INSERT INTO `prefix_modules` (`id`, `url`, `name`, `gshow`, `ashow`, `fright`) VALUES 
(807, 'raidinzen', 'R:Instanzen', 0, 0, 1),
(803, 'chars', 'R:Chars', 0, 0, 1),
(804, 'raiddkps', 'R:DKP''s', 0, 0, 1),
(805, 'raidgruppen', 'R:DKP Gruppen', 0, 0, 1),
(806, 'raidbosse', 'R:Bosse', 0, 0, 1),
(802, 'dkp', 'R:DKP', 0, 0, 1),
(801, 'raid', 'R:Raidplaner', 0, 0, 1),
(800, 'raidindex', 'R:Startseite', 1, 1, 1),
(808, 'raidrang', 'R:R&auml;nge', 0, 0, 1),
(809, 'raidconfig', 'R:Config', 0, 0, 1),
(810, 'CharsEditKlassen', 'R:Char Edit nur gleiche Klassen', 0, 0, 0);

CREATE TABLE IF NOT EXISTS `prefix_raid_anmeldung` (
  `id` int(11) NOT NULL auto_increment,
  `rid` int(11) NOT NULL default '0',
  `grp` int(11) NOT NULL default '0',
  `char` int(11) NOT NULL default '0',
  `user` int(11) NOT NULL default '0',
  `kom` varchar(255) NOT NULL default '',
  `stat` int(11) NOT NULL default '1',
  `timestamp` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_bosscounter` (
  `id` int(11) NOT NULL auto_increment,
  `bid` int(11) NOT NULL default '0',
  `grpid` int(11) NOT NULL default '0',
  `rid` int(11) NOT NULL default '0',
  `iid` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_bosse` (
  `id` int(11) NOT NULL auto_increment,
  `inzen` int(11) NOT NULL default '0',
  `bosse` varchar(255) NOT NULL default '',
  `taktik` longtext NOT NULL,
  `img` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_chars` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '1',
  `name` varchar(255) NOT NULL default 'Name',
  `klassen` int(11) NOT NULL default '1',
  `rassen` int(11) NOT NULL default '1',
  `level` int(11) NOT NULL default '1',
  `skillgruppe` int(1) NOT NULL default '0',
  `s1` VARCHAR(255) NOT NULL default '0',
  `s2` VARCHAR(255) NOT NULL default '0',
  `alter` int(2) NOT NULL default '0',
  `warum` text NOT NULL,
  `punkte` int(11) NOT NULL default '1',
  `gruppen` int(11) NOT NULL default '1',
  `rang` int(11) NOT NULL default '1',
  `stammgrp` int(11) NOT NULL default '0',
  `realm` varchar(255) NOT NULL default 'Realm',
  `regist` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_dkp` (
  `id` int(11) NOT NULL auto_increment,
  `rid` int(11) NOT NULL default '0',
  `dkpgrp` int(11) NOT NULL default '0',
  `cid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `pm` char(1) character set latin1 NOT NULL default '',
  `dkp` int(11) NOT NULL default '0',
  `info` varchar(255) character set latin1 NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_dkps` (
  `id` int(11) NOT NULL auto_increment,
  `inzen` int(11) NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `dkp` int(11) NOT NULL default '0',
  `pm` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_grpsize` (
  `id` int(11) NOT NULL auto_increment,
  `grpsize` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `prefix_raid_grpsize` (`id`, `grpsize`) VALUES
(1, '5'),
(2, '10'),
(3, '20'),
(4, '25'),
(5, '40');

CREATE TABLE IF NOT EXISTS `prefix_raid_gruppen` (
  `id` int(11) NOT NULL auto_increment,
  `gruppen` varchar(255) NOT NULL default '',
  `stammgrp` int(11) NOT NULL default '0',
  `regeln` longtext NOT NULL,
  `img` varchar(255) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_info` (
  `id` int(11) NOT NULL auto_increment,
  `info` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `prefix_raid_info` (`id`, `info`) VALUES
(1, 'Raid'),
(2, 'Normal'),
(4, 'Heroisch'),
(5, 'PVP');

CREATE TABLE IF NOT EXISTS `prefix_raid_inzen` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `grpsize` int(11) NOT NULL default '0',
  `img` varchar(255) NOT NULL default '',
  `info` int(11) NOT NULL default '0',
  `maxbosse` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `prefix_raid_kalender` (
  `cid` smallint(6) NOT NULL default '0',
  `zid` smallint(6) NOT NULL default '0',
  `wid` smallint(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_raid_klassen` (
  `id` int(11) NOT NULL auto_increment,
  `klassen` varchar(255) NOT NULL default '',
  `s1b` varchar(50) NOT NULL default '',
  `s2b` varchar(50) NOT NULL default '',
  `s3b` varchar(50) NOT NULL default '',
  `aufnahmestop` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

INSERT INTO `prefix_raid_klassen` (`id`, `klassen`, `s1b`, `s2b`, `s3b`, `aufnahmestop`) VALUES
(2, 'Schurke', 'M&auml;ucheln', 'Kampf', 'T&auml;uschung', 1),
(3, 'Krieger', 'Waffen', 'Furor', 'Schutz', 1),
(4, 'Magier', 'Arcan', 'Feuer', 'Eis', 1),
(5, 'Priester', 'Disziplin', 'Heilig', 'Schatten', 1),
(6, 'Paladin', 'Heilig', 'Schutz', 'Vergeltung', 1),
(7, 'Schamane', 'Elementar', 'Verst&auml;rkung', 'Wiederherstellung', 1),
(8, 'Jaeger', 'Tierherrschaft', 'Treffsicherheit', '&uuml;berleben', 1),
(9, 'Hexenmeister', 'Gebrechen', 'D&auml;monologie', 'Zerst&ouml;rung', 1),
(10, 'Druide', 'Gleichgewicht', 'Wilder Kampf', 'Wiederherstellung', 1),
(11, 'Todesritter', 'Blut', 'Frost', 'Unheilig', 1);


CREATE TABLE IF NOT EXISTS `prefix_raid_loot` (
  `id` int(11) NOT NULL auto_increment,
  `loot` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `prefix_raid_loot` (`id`, `loot`) VALUES
(1, 'DKP'),
(2, 'W&uuml;rfeln');

CREATE TABLE IF NOT EXISTS `prefix_raid_raid` (
  `id` int(11) NOT NULL auto_increment,
  `statusmsg` int(11) NOT NULL default '1',
  `leader` int(11) NOT NULL default '1',
  `gruppen` int(11) NOT NULL default '1',
  `stammgrp` int(2) NOT NULL default '0',
  `inzen` int(11) NOT NULL default '1',
  `treff` varchar(255) NOT NULL default '',
  `loot` int(11) NOT NULL default '1',
  `inv` int(11) NOT NULL default '0',
  `pull` int(11) NOT NULL default '0',
  `ende` int(11) NOT NULL default '0',
  `invsperre` smallint(2) NOT NULL default '0',
  `txt` text NOT NULL,
  `erstellt` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `von` int(2) default NULL,
  `bosskey` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_rang` (
  `id` int(11) NOT NULL auto_increment,
  `rang` varchar(255) NOT NULL default '',
  `info` varchar(255) NOT NULL default 'Keine Angaben',
  `module` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

INSERT INTO `prefix_raid_rang` (`id`, `rang`, `info`, `module`) VALUES
(1, 'Bewerber', 'Diesen Rang bekommt man wenn man sich mit dem Bewerbungs Formular Bewirbt.', ''),
(2, 'Warte Rang', 'Wartet auf Rang&auml;nderung.', ''),
(3, 'LEER', 'Selbst Definieren', ''),
(4, 'LEER', 'Selbst Definieren', ''),
(5, 'LEER', 'Selbst Definieren', ''),
(6, 'Twink', 'Zweit Chars', ''),
(7, 'Raidpartner', 'Mit Raider die nicht in der Gilde sind.', ''),
(8, 'Mitglied', 'Miltgied in der Gilde.', ''),
(9, 'Ehren Mitglied', 'Ehren Mitglieder in der Gilde.', ''),
(10, 'Raidleiter', 'Raids erstellen, DKP verteilen von den Raids die er erstellt hat.', '800,801,802'),
(11, 'Super Raidleiter', 'D&uuml;rfen "ALLE" Raids Bearbeiten und Erstellen, DKP Verteilen in allen Raids', '800,801,802'),
(12, 'Klassensprecher', 'Kann Chars Bearbeiten!', '800,803,810'),
(13, 'Offizier', 'Hat alle Rechte wie ein Gildenmeister. Kann nur den rang vom Gildenmeister nicht &Auml;ndern.', '800,801,802,803,804,805,806,807,809'),
(14, 'Gildenmeister', 'Gildenmeister sagt wohl alles.', '800,801,802,803,804,805,806,807,808,809');

CREATE TABLE IF NOT EXISTS `prefix_raid_rassen` (
  `id` int(11) NOT NULL auto_increment,
  `rassen` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

INSERT INTO `prefix_raid_rassen` (`id`, `rassen`) VALUES
(1, 'Orks'),
(2, 'Untote'),
(3, 'Trolle'),
(4, 'Tauren'),
(5, 'Blutelfen'),
(6, 'Goblins');

CREATE TABLE IF NOT EXISTS `prefix_raid_stammgrp` (
  `id` int(11) NOT NULL auto_increment,
  `stammgrp` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prefix_raid_stammrechte` (
  `cid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `eid` int(11) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `prefix_raid_statusmsg` (
  `id` int(11) NOT NULL auto_increment,
  `sid` int(11) NOT NULL default '0',
  `statusmsg` varchar(255) NOT NULL default '',
  `color` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

INSERT INTO `prefix_raid_statusmsg` (`id`, `sid`, `statusmsg`, `color`) VALUES
(1, 1, 'Aktiv', 'green'),
(2, 1, 'Beendet', 'blue'),
(3, 1, 'Abgesagt', 'red'),
(4, 1, 'Abgebrochen', 'orange'),
(5, 2, 'User: Dabei', 'orange'),
(6, 2, 'User: Ersatz', 'blue'),
(8, 2, 'User: Absagen', 'red'),
(13, 3, 'Raid: Ersatz', 'blue'),
(12, 3, 'Raid: Zusage', 'green'),
(14, 3, 'Raid: Absage', 'red'),
(15, 3, 'Raid: Strafe', 'darkred'),
(17, 1, 'Ausstehend', 'red'),
(16, 3, 'Bearbeitung', 'darkorange');

CREATE TABLE IF NOT EXISTS `prefix_raid_zeit` (
  `id` int(11) NOT NULL auto_increment,
  `zeit` varchar(5) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO `prefix_raid_zeit` (`id`, `zeit`) VALUES
(1, 'Mo 15:00-16:00'),
(2, 'Fr 16:00-22:00');

CREATE TABLE IF NOT EXISTS `prefix_raid_zeiten` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default 0,
  `zid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
