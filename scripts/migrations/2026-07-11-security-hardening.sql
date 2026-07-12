DROP PROCEDURE IF EXISTS `assert_unique_clip_codes`;
DELIMITER //
CREATE PROCEDURE `assert_unique_clip_codes`()
BEGIN
  IF EXISTS (
    SELECT 1
    FROM `userurl`
    WHERE `usr` NOT REGEXP '^[A-Za-z0-9]{5}$'
       OR LOWER(`usr`) IN ('admin', 'about', 'login', 'tests')
  ) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Invalid clip codes must be resolved before migration';
  END IF;

  IF EXISTS (
    SELECT 1
    FROM `userurl`
    GROUP BY LOWER(`usr`)
    HAVING COUNT(*) > 1
  ) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Duplicate clip codes must be resolved before migration';
  END IF;

  IF EXISTS (
    SELECT 1
    FROM `userurl`
    WHERE `expires` IS NULL OR OCTET_LENGTH(`url`) > 2048
  ) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Missing expirations or oversized URLs must be resolved before migration';
  END IF;
END//
DELIMITER ;

CALL `assert_unique_clip_codes`();
DROP PROCEDURE `assert_unique_clip_codes`;

UPDATE `userurl` SET `usr` = LOWER(`usr`);

ALTER TABLE `userurl`
  ADD COLUMN `expires_at` datetime(6) NULL AFTER `expires`;

UPDATE `userurl`
SET `expires_at` = LEAST(
  CAST(CONCAT(`expires`, ' 23:59:59.999999') AS DATETIME(6)),
  UTC_TIMESTAMP(6) + INTERVAL 48 HOUR
);

ALTER TABLE `userurl`
  MODIFY `usr` char(5) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  MODIFY `url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  MODIFY `expires_at` datetime(6) NOT NULL,
  DROP COLUMN `expires`,
  ADD UNIQUE KEY `userurl_usr_unique` (`usr`),
  ADD KEY `userurl_expires_at` (`expires_at`),
  ADD CONSTRAINT `userurl_usr_format` CHECK (`usr` REGEXP '^[A-Za-z0-9]{5}$' AND LOWER(`usr`) NOT IN ('admin', 'about', 'login', 'tests'));

ALTER TABLE `accounts`
  ADD COLUMN `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL AFTER `id`,
  MODIFY `email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  ADD UNIQUE KEY `accounts_subject_unique` (`subject`);

-- Assign each legitimate staff row its verified Auth0 `sub` before activating
-- the application. Staff rows without a subject intentionally fail closed.

DROP EVENT IF EXISTS `clean_expired`;
CREATE EVENT `clean_expired`
  ON SCHEDULE EVERY 1 HOUR
  ON COMPLETION PRESERVE ENABLE
  DO DELETE FROM `userurl` WHERE `expires_at` <= UTC_TIMESTAMP(6);
