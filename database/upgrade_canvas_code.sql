-- ---------------------------------------------------------------
-- Upgrade: store canvas drawing code for question / option images.
-- Run once on databases created before this change:
--   mysql -u root ecommerce_quiz_db < database/upgrade_canvas_code.sql
-- ---------------------------------------------------------------
ALTER TABLE `questions`
  ADD COLUMN IF NOT EXISTS `question_image_code` TEXT NULL AFTER `question_image`;

ALTER TABLE `question_options`
  ADD COLUMN IF NOT EXISTS `code` TEXT NULL AFTER `file`;
