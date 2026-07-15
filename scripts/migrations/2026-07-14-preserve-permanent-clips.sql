ALTER TABLE `userurl`
  MODIFY `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  MODIFY `expires_at` datetime(6) NULL;

DROP EVENT IF EXISTS `clean_expired`;
CREATE EVENT `clean_expired`
  ON SCHEDULE EVERY 1 HOUR
  ON COMPLETION PRESERVE ENABLE
  DO DELETE FROM `userurl`
    WHERE `expires_at` IS NOT NULL AND `expires_at` <= UTC_TIMESTAMP(6);
