-- ---------------------------------------------------------------
-- Upgrade: Text Quiz (/text-quiz) gets its own tables.
--   text_questions         — question text + optional image
--   text_question_options  — 4 text answers, one marked correct
-- Also removes the short-lived questions.type column (text questions
-- no longer live in `questions`) and adds sample questions.
-- Run once:  mysql -u root ecommerce_quiz_db < database/upgrade_text_questions.sql
-- ---------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `text_questions` (
  `id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prompt`              TEXT         NOT NULL,
  `answer_card_text`    TEXT         NULL COMMENT 'optional explanation',
  `question_image`      VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'path or https URL, optional',
  `question_image_code` TEXT         NULL COMMENT 'canvas drawing JS (optional)',
  `active`              TINYINT(1)   NOT NULL DEFAULT 1,
  `sort`                INT          NOT NULL DEFAULT 0,
  `created_at`          DATETIME     NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME     NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`active`, `sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- `sort` is the option index the quiz page submits, so keep it 0-based.
CREATE TABLE IF NOT EXISTS `text_question_options` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `label`       VARCHAR(255) NOT NULL DEFAULT '',
  `is_correct`  TINYINT(1)   NOT NULL DEFAULT 0,
  `sort`        INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_question` (`question_id`, `sort`),
  CONSTRAINT `fk_text_options_question` FOREIGN KEY (`question_id`)
    REFERENCES `text_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `questions` DROP COLUMN IF EXISTS `type`;

-- ---------------------------------------------------------------
-- Sample text questions (INSERT IGNORE — re-running is safe)
-- ---------------------------------------------------------------
INSERT IGNORE INTO `text_questions` (`id`, `prompt`, `answer_card_text`, `active`, `sort`) VALUES
(1,  'In IALA Region A, what colour is a port hand lateral mark?', 'IALA Region A\\nPort hand lateral mark: red, can topmark', 1, 1),
(2,  'In IALA Region A, what is the topmark of a starboard hand lateral mark?', 'IALA Region A\\nStarboard hand lateral mark: single green cone, point up', 1, 2),
(3,  'In IALA Region B, what colour is a port hand lateral mark?', 'IALA Region B\\nPort hand lateral mark: green, can topmark', 1, 3),
(4,  'What is the topmark of a North cardinal mark?', 'North cardinal mark\\nTopmark: two black cones, points up', 1, 4),
(5,  'What is the topmark of a South cardinal mark?', 'South cardinal mark\\nTopmark: two black cones, points down', 1, 5),
(6,  'What is the topmark of an East cardinal mark?', 'East cardinal mark\\nTopmark: two black cones, base to base', 1, 6),
(7,  'What is the topmark of a West cardinal mark?', 'West cardinal mark\\nTopmark: two black cones, point to point', 1, 7),
(8,  'What is the topmark of an Isolated danger mark?', 'Isolated danger mark\\nTopmark: two black balls', 1, 8),
(9,  'What does a Safe water mark look like?', 'Safe water mark\\nRed and white vertical stripes, single red ball topmark', 1, 9),
(10, 'What colour is a Special mark?', 'Special mark\\nYellow, with a yellow X topmark', 1, 10),
(11, 'On which side of a North cardinal mark is the safe water?', 'North cardinal mark\\nSafe water lies to the north of the mark', 1, 11),
(12, 'What colour light does a Special mark show?', 'Special mark\\nLight: yellow', 1, 12);

INSERT IGNORE INTO `text_question_options` (`id`, `question_id`, `label`, `is_correct`, `sort`) VALUES
(1,  1,  'Green', 0, 0),
(2,  1,  'Red', 1, 1),
(3,  1,  'Yellow', 0, 2),
(4,  1,  'Black and yellow', 0, 3),
(5,  2,  'A single red can', 0, 0),
(6,  2,  'Two black balls', 0, 1),
(7,  2,  'A single green cone, point up', 1, 2),
(8,  2,  'A yellow X', 0, 3),
(9,  3,  'Green', 1, 0),
(10, 3,  'Red', 0, 1),
(11, 3,  'Yellow', 0, 2),
(12, 3,  'Black and red', 0, 3),
(13, 4,  'Two black cones, points down', 0, 0),
(14, 4,  'Two black cones, base to base', 0, 1),
(15, 4,  'Two black cones, point to point', 0, 2),
(16, 4,  'Two black cones, points up', 1, 3),
(17, 5,  'Two black cones, points down', 1, 0),
(18, 5,  'Two black cones, points up', 0, 1),
(19, 5,  'Two black cones, point to point', 0, 2),
(20, 5,  'Two black cones, base to base', 0, 3),
(21, 6,  'Two black cones, point to point', 0, 0),
(22, 6,  'Two black cones, base to base', 1, 1),
(23, 6,  'Two black cones, points up', 0, 2),
(24, 6,  'Two black cones, points down', 0, 3),
(25, 7,  'Two black cones, points up', 0, 0),
(26, 7,  'Two black cones, base to base', 0, 1),
(27, 7,  'Two black cones, point to point', 1, 2),
(28, 7,  'Two black cones, points down', 0, 3),
(29, 8,  'A single red ball', 0, 0),
(30, 8,  'A yellow X', 0, 1),
(31, 8,  'A single green cone', 0, 2),
(32, 8,  'Two black balls', 1, 3),
(33, 9,  'Red and white vertical stripes, single red ball topmark', 1, 0),
(34, 9,  'Black with a red band, two black balls topmark', 0, 1),
(35, 9,  'Yellow with a yellow X topmark', 0, 2),
(36, 9,  'Red with a red can topmark', 0, 3),
(37, 10, 'Red', 0, 0),
(38, 10, 'Green', 0, 1),
(39, 10, 'Black', 0, 2),
(40, 10, 'Yellow', 1, 3),
(41, 11, 'North', 1, 0),
(42, 11, 'South', 0, 1),
(43, 11, 'East', 0, 2),
(44, 11, 'West', 0, 3),
(45, 12, 'White', 0, 0),
(46, 12, 'Yellow', 1, 1),
(47, 12, 'Red', 0, 2),
(48, 12, 'Green', 0, 3);
