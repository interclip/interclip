USE iclip;

/* Clips table */
DROP TABLE IF EXISTS `userurl`;
CREATE TABLE `userurl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Accounts table */
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `email` varchar(128) NOT NULL,
  `role` varchar(64) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0;

/* Add the mocked admin account as an admin on the site */
INSERT INTO accounts VALUES('admin@example.org', 'staff', NULL);


### Setting up MySQL cron jobs
/* Delete expired clips (runs every hour) */
CREATE EVENT `clean_expired` ON SCHEDULE EVERY 1 HOUR STARTS '2021-02-01 13:39:14' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM userurl WHERE expires < CURDATE();

/* Cleans the DB hits table with events older than 5 hours */
CREATE EVENT `clean_hits` ON SCHEDULE EVERY 1 DAY STARTS '2014-01-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `hits` where `date` < (CURRENT_TIMESTAMP - 18000);