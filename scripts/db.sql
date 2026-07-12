DROP TABLE IF EXISTS `userurl`;
DROP TABLE IF EXISTS `issued_clip_codes`;
DROP TABLE IF EXISTS `clip_metrics`;

/* Active clip destinations */
CREATE TABLE `userurl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usr` char(5) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` datetime(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userurl_usr_unique` (`usr`),
  KEY `userurl_expires_at` (`expires_at`),
  CONSTRAINT `userurl_usr_format` CHECK (`usr` REGEXP '^[A-Za-z0-9]{5}$' AND LOWER(`usr`) NOT IN ('admin', 'about', 'login', 'tests'))
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/* Accounts table */
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_subject_unique` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=0;

### Setting up MySQL cron jobs
/* Delete expired clips (runs every hour) */
DROP EVENT IF EXISTS `clean_expired`;
CREATE EVENT `clean_expired` ON SCHEDULE EVERY 1 HOUR ON COMPLETION PRESERVE ENABLE DO DELETE FROM userurl WHERE expires_at <= UTC_TIMESTAMP(6);
