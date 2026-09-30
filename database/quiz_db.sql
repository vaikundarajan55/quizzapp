-- ---------------------------------------------------------------
-- IALA Buoyage Quiz — database schema + seed data
-- Import:  mysql -u root < database/quiz_db.sql
--   or phpMyAdmin → Import → choose this file
-- ---------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `ecommerce_quiz_db`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecommerce_quiz_db`;

-- Questions --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `questions` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prompt`           TEXT         NOT NULL,
  `answer_label`     VARCHAR(255) NOT NULL DEFAULT '',
  `answer_card_text` TEXT         NULL,
  `question_image`   VARCHAR(255) NOT NULL DEFAULT '',
  `question_image_code` TEXT     NULL COMMENT 'canvas drawing JS (optional)',
  `active`           TINYINT(1)   NOT NULL DEFAULT 1,
  `sort`             INT          NOT NULL DEFAULT 0,
  `created_at`       DATETIME     NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME     NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active_sort` (`active`, `sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Answer options (drag items) per question ---------------------------
-- `sort` is the option index the quiz page submits, so keep it 0-based.
CREATE TABLE IF NOT EXISTS `question_options` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `label`       VARCHAR(255) NOT NULL DEFAULT '',
  `file`        VARCHAR(255) NOT NULL DEFAULT '',
  `code`        TEXT         NULL COMMENT 'canvas drawing JS (optional)',
  `is_correct`  TINYINT(1)   NOT NULL DEFAULT 0,
  `sort`        INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_question` (`question_id`, `sort`),
  CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`)
    REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quiz attempts / results ------------------------------------------
CREATE TABLE IF NOT EXISTS `quiz_results` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `token`      CHAR(16)     NOT NULL,
  `total`      INT          NOT NULL DEFAULT 0,
  `correct`    INT          NOT NULL DEFAULT 0,
  `score`      DECIMAL(5,1) NOT NULL DEFAULT 0,
  `elapsed`    INT          NOT NULL DEFAULT 0 COMMENT 'seconds',
  `details`    LONGTEXT     NULL COMMENT 'JSON: per-question breakdown',
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings (key/value, values JSON-encoded to keep bool/int types) --
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key`   VARCHAR(64) NOT NULL,
  `setting_value` TEXT        NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- Seed data (INSERT IGNORE — re-running the file is safe)
-- ---------------------------------------------------------------
INSERT IGNORE INTO `questions` (`id`, `prompt`, `answer_label`, `answer_card_text`, `question_image`, `active`, `sort`) VALUES
(1, 'Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark.', 'IALA Region A – Lateral Port Hand Mark (Red Can)', '"IALA Region A"\\nLateral Port hand Mark\\nTop mark Single red can', 'assets/questions/q1.png', 1, 1),
(2, 'Drag the correct top mark onto the IALA Region A Lateral Starboard Hand Mark.', 'IALA Region A – Lateral Starboard Hand Mark (Green Cone)', '"IALA Region A"\\nLateral Starboard hand Mark\\nTop mark Single green cone, pointing upward', 'assets/questions/q2.png', 1, 2),
(3, 'Drag the correct top mark onto the IALA Region B Lateral Port Hand Mark.', 'IALA Region B – Lateral Port Hand Mark (Green Can)', '"IALA Region B"\\nLateral Port hand Mark\\nTop mark Single green can', 'assets/questions/q3.png', 1, 3),
(4, 'Drag the correct top mark onto the IALA Region B Lateral Starboard Hand Mark.', 'IALA Region B – Lateral Starboard Hand Mark (Red Cone)', '"IALA Region B"\\nLateral Starboard hand Mark\\nTop mark Single red cone, pointing upward', 'assets/questions/q4.png', 1, 4),
(5, 'Drag the correct top mark onto the Safe Water Mark.', 'Safe Water Mark – Red & White Vertical Stripes, Red Ball Topmark', 'Safe Water Mark\\nTop mark: Single red ball', 'assets/questions/q5.png', 1, 5),
(6, 'Drag the correct top mark onto the Isolated Danger Mark.', 'Isolated Danger Mark – Two Black Balls Topmark', 'Isolated Danger Mark\\nTop mark: Two black balls', 'assets/questions/q6.png', 1, 6),
(7, 'Drag the correct top mark onto the North Cardinal Mark.', 'North Cardinal Mark – Two cones points up', 'North Cardinal Mark\\nTop mark: Two cones, points upward', 'assets/questions/q7.png', 1, 7),
(8, 'Drag the correct top mark onto the South Cardinal Mark.', 'South Cardinal Mark – Two cones points down', 'South Cardinal Mark\\nTop mark: Two cones, points downward', 'assets/questions/q8.png', 1, 8),
(9, 'Drag the correct top mark onto the East Cardinal Mark.', 'East Cardinal Mark – Two cones base to base', 'East Cardinal Mark\\nTop mark: Two cones, base to base', 'assets/questions/q9.png', 1, 9),
(10, 'Drag the correct top mark onto the West Cardinal Mark.', 'West Cardinal Mark – Two cones point to point', 'West Cardinal Mark\\nTop mark: Two cones, point to point', 'assets/questions/q10.png', 1, 10),
(11, 'Drag the correct top mark onto the Special Mark.', 'Special Mark – Yellow X topmark', 'Special Mark\\nTop mark: X shape (yellow)', 'assets/questions/q11.png', 1, 11),
(12, 'Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark (Light)', 'IALA Region A Lateral Port Hand Mark with light', '"IALA Region A"\\nLateral Port hand Mark\\nTop mark Single red can\\nLight: Red flashing', 'assets/questions/q12.png', 1, 12),
(13, 'Drag the correct top mark onto this cardinal mark.', 'Cardinal Mark identification', 'Cardinal Mark\\nIdentify by topmark and light pattern', 'assets/questions/q13.png', 1, 13),
(14, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q14.png', 1, 14),
(15, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q15.png', 1, 15),
(16, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q16.png', 1, 16);

INSERT IGNORE INTO `question_options` (`id`, `question_id`, `label`, `file`, `is_correct`, `sort`) VALUES
(1, 1, 'Red Cone', 'assets/options/q1_a.png', 0, 0),
(2, 1, 'Green Can', 'assets/options/q1_b.png', 0, 1),
(3, 1, 'Red Can', 'assets/options/q1_c.png', 1, 2),
(4, 1, 'Green Cone', 'assets/options/q1_d.png', 0, 3),
(5, 2, 'Green Can', 'assets/options/q2_a.png', 0, 0),
(6, 2, 'Red Can', 'assets/options/q2_b.png', 0, 1),
(7, 2, 'Green Cone', 'assets/options/q2_c.png', 1, 2),
(8, 2, 'Red Cone', 'assets/options/q2_d.png', 0, 3),
(9, 3, 'Green Cone', 'assets/options/q3_a.png', 0, 0),
(10, 3, 'Red Can', 'assets/options/q3_b.png', 0, 1),
(11, 3, 'Green Can', 'assets/options/q3_c.png', 1, 2),
(12, 3, 'Red Cone', 'assets/options/q3_d.png', 0, 3),
(13, 4, 'Red Can', 'assets/options/q4_a.png', 0, 0),
(14, 4, 'Green Cone', 'assets/options/q4_b.png', 0, 1),
(15, 4, 'Red Cone', 'assets/options/q4_c.png', 1, 2),
(16, 4, 'Green Can', 'assets/options/q4_d.png', 0, 3),
(17, 5, 'Red Ball', 'assets/options/q5_a.png', 1, 0),
(18, 5, 'Green Cone', 'assets/options/q5_b.png', 0, 1),
(19, 5, 'Red Can', 'assets/options/q5_c.png', 0, 2),
(20, 5, 'X Shape', 'assets/options/q5_d.png', 0, 3),
(21, 6, 'Two Black Balls', 'assets/options/q6_a.png', 1, 0),
(22, 6, 'Red Ball', 'assets/options/q6_b.png', 0, 1),
(23, 6, 'Yellow Cone', 'assets/options/q6_c.png', 0, 2),
(24, 6, 'X Shape', 'assets/options/q6_d.png', 0, 3),
(25, 7, 'Two cones up', 'assets/options/q7_a.png', 1, 0),
(26, 7, 'Two cones down', 'assets/options/q7_b.png', 0, 1),
(27, 7, 'Cones base to base', 'assets/options/q7_c.png', 0, 2),
(28, 7, 'Cones point to point', 'assets/options/q7_d.png', 0, 3),
(29, 8, 'Two cones down', 'assets/options/q8_a.png', 1, 0),
(30, 8, 'Two cones up', 'assets/options/q8_b.png', 0, 1),
(31, 8, 'Cones base to base', 'assets/options/q8_c.png', 0, 2),
(32, 8, 'Cones point to point', 'assets/options/q8_d.png', 0, 3),
(33, 9, 'Cones base to base', 'assets/options/q9_a.png', 1, 0),
(34, 9, 'Two cones up', 'assets/options/q9_b.png', 0, 1),
(35, 9, 'Two cones down', 'assets/options/q9_c.png', 0, 2),
(36, 9, 'Cones point to point', 'assets/options/q9_d.png', 0, 3),
(37, 10, 'Cones point to point', 'assets/options/q10_a.png', 1, 0),
(38, 10, 'Two cones up', 'assets/options/q10_b.png', 0, 1),
(39, 10, 'Two cones down', 'assets/options/q10_c.png', 0, 2),
(40, 10, 'Cones base to base', 'assets/options/q10_d.png', 0, 3),
(41, 11, 'X Shape', 'assets/options/q11_a.png', 1, 0),
(42, 11, 'Red Ball', 'assets/options/q11_b.png', 0, 1),
(43, 11, 'Two Black Balls', 'assets/options/q11_c.png', 0, 2),
(44, 11, 'Green Cone', 'assets/options/q11_d.png', 0, 3),
(45, 12, 'Red Can', 'assets/options/q12_a.png', 1, 0),
(46, 12, 'Green Can', 'assets/options/q12_b.png', 0, 1),
(47, 12, 'Red Cone', 'assets/options/q12_c.png', 0, 2),
(48, 12, 'Green Cone', 'assets/options/q12_d.png', 0, 3),
(49, 13, 'Two cones up', 'assets/options/q13_a.png', 1, 0),
(50, 13, 'Two cones down', 'assets/options/q13_b.png', 0, 1),
(51, 13, 'Cones base to base', 'assets/options/q13_c.png', 0, 2),
(52, 13, 'Cones point to point', 'assets/options/q13_d.png', 0, 3),
(53, 14, 'Option A', 'assets/options/q14_a.png', 1, 0),
(54, 14, 'Option B', 'assets/options/q14_b.png', 0, 1),
(55, 14, 'Option C', 'assets/options/q14_c.png', 0, 2),
(56, 14, 'Option D', 'assets/options/q14_d.png', 0, 3),
(57, 15, 'Option A', 'assets/options/q15_a.png', 0, 0),
(58, 15, 'Option B', 'assets/options/q15_b.png', 1, 1),
(59, 15, 'Option C', 'assets/options/q15_c.png', 0, 2),
(60, 15, 'Option D', 'assets/options/q15_d.png', 0, 3),
(61, 16, 'Option A', 'assets/options/q16_a.png', 0, 0),
(62, 16, 'Option B', 'assets/options/q16_b.png', 0, 1),
(63, 16, 'Option C', 'assets/options/q16_c.png', 1, 2),
(64, 16, 'Option D', 'assets/options/q16_d.png', 0, 3);

INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
('quiz_title',         '"Pickup & Drop the Correct Top Mark"'),
('quiz_subtitle',      '"IALA Buoyage System Quiz"'),
('voice_enabled',      'true'),
('show_answer_card',   'true'),
('allow_retry',        'true'),
('theme_color',        '"#6c5ce7"'),
('bg_type',            '"ocean"'),
('questions_per_quiz', '0'),
('passing_score',      '70');

-- ---------------------------------------------------------------
-- Admin users
-- Default login: admin / admin123  — change it after first login.
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)  NOT NULL,
  `name`          VARCHAR(100) NOT NULL DEFAULT '',
  `password_hash` VARCHAR(255) NOT NULL,
  `last_login_at` DATETIME     NULL,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `admins` (`id`, `username`, `name`, `password_hash`) VALUES
(1, 'admin', 'Administrator', '$2y$10$U559CTofpDEZEk8ag5mi7ufqTH7IqhsQ268jmpF8vi8MnP6elQO06');

-- ---------------------------------------------------------------
-- Text Quiz (/text-quiz): question text + optional image, 4 text answers
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
