ALTER TABLE `prefix_raid_raid` 
    DROP `stammgrp`, 
    DROP `treff`,
    DROP `invsperre`,
    CHANGE `statusmsg` `status` INT(11) NOT NULL,
    CHANGE `erstellt` `created` TIMESTAMP NOT NULL, 
    CHANGE `bosskey` `series` INT(11) NOT NULL,
    CHANGE `von` `creator` INT(11) NOT NULL,
    CHANGE `gruppen` `group` INT(11) NOT NULL,
    CHANGE `inzen` `dungeon` INT(11) NOT NULL,
    CHANGE `ende` `end` INT(11) NOT NULL,
    ADD `updated` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `txt`,
    ADD `title` VARCHAR(128) NOT NULL AFTER `group`,
    ADD `weekdays` TEXT NOT NULL ;
