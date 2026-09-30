-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2026 at 12:17 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_quiz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `name`, `password_hash`, `last_login_at`, `created_at`) VALUES
(1, 'admin', 'Administrator', '$2y$10$bSGPGd4nzqY7uXhwwglDjuE8iOOvp4dGTv2m2TyzIOm01eCYLfS2G', '2026-09-29 15:26:19', '2026-09-29 16:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `prompt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `answer_card_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `question_image_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `prompt`, `answer_label`, `answer_card_text`, `question_image`, `question_image_code`, `active`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark.', 'IALA Region A – Lateral Port Hand Mark (Red Can)', '\"IALA Region A\"\\nLateral Port hand Mark\\nTop mark Single red can', 'assets/questions/q1.png', NULL, 1, 1, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(2, 'Drag the correct top mark onto the IALA Region A Lateral Starboard Hand Mark.', 'IALA Region A – Lateral Starboard Hand Mark (Green Cone)', '\"IALA Region A\"\\nLateral Starboard hand Mark\\nTop mark Single green cone, pointing upward', 'assets/questions/q2.png', NULL, 1, 2, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(3, 'Drag the correct top mark onto the IALA Region B Lateral Port Hand Mark.', 'IALA Region B – Lateral Port Hand Mark (Green Can)', '\"IALA Region B\"\\nLateral Port hand Mark\\nTop mark Single green can', 'assets/questions/q3.png', NULL, 1, 3, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(4, 'Drag the correct top mark onto the IALA Region B Lateral Starboard Hand Mark.', 'IALA Region B – Lateral Starboard Hand Mark (Red Cone)', '\"IALA Region B\"\\nLateral Starboard hand Mark\\nTop mark Single red cone, pointing upward', 'assets/questions/q4.png', NULL, 1, 4, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(5, 'Drag the correct top mark onto the Safe Water Mark.', 'Safe Water Mark – Red & White Vertical Stripes, Red Ball Topmark', 'Safe Water Mark\\nTop mark: Single red ball', 'assets/questions/q5.png', NULL, 1, 5, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(6, 'Drag the correct top mark onto the Isolated Danger Mark.', 'Isolated Danger Mark – Two Black Balls Topmark', 'Isolated Danger Mark\\nTop mark: Two black balls', 'assets/questions/q6.png', NULL, 1, 6, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(7, 'Drag the correct top mark onto the North Cardinal Mark.', 'North Cardinal Mark – Two cones points up', 'North Cardinal Mark\\nTop mark: Two cones, points upward', 'assets/questions/q7.png', NULL, 1, 7, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(8, 'Drag the correct top mark onto the South Cardinal Mark.', 'South Cardinal Mark – Two cones points down', 'South Cardinal Mark\\nTop mark: Two cones, points downward', 'assets/questions/q8.png', NULL, 1, 8, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(9, 'Drag the correct top mark onto the East Cardinal Mark.', 'East Cardinal Mark – Two cones base to base', 'East Cardinal Mark\\nTop mark: Two cones, base to base', 'assets/questions/q9.png', NULL, 1, 9, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(10, 'Drag the correct top mark onto the West Cardinal Mark.', 'West Cardinal Mark – Two cones point to point', 'West Cardinal Mark\\nTop mark: Two cones, point to point', 'assets/questions/q10.png', NULL, 1, 10, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(11, 'Drag the correct top mark onto the Special Mark.', 'Special Mark – Yellow X topmark', 'Special Mark\\nTop mark: X shape (yellow)', 'assets/questions/q11.png', NULL, 1, 11, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(12, 'Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark (Light)', 'IALA Region A Lateral Port Hand Mark with light', '\"IALA Region A\"\\nLateral Port hand Mark\\nTop mark Single red can\\nLight: Red flashing', 'assets/questions/q12.png', NULL, 1, 12, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(13, 'Drag the correct top mark onto this cardinal mark.', 'Cardinal Mark identification', 'Cardinal Mark\\nIdentify by topmark and light pattern', 'assets/questions/q13.png', NULL, 1, 13, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(14, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q14.png', NULL, 1, 14, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(15, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q15.png', NULL, 1, 15, '2026-09-29 14:04:16', '2026-09-29 14:04:16'),
(16, 'Drag the correct top mark onto this mark.', 'Mark identification', 'Mark identification\\nMatch topmark to buoy type', 'assets/questions/q16.png', NULL, 1, 16, '2026-09-29 14:04:16', '2026-09-29 14:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `label`, `file`, `code`, `is_correct`, `sort`) VALUES
(1, 1, 'Red Cone', 'assets/options/q1_a.png', NULL, 0, 0),
(2, 1, 'Green Can', 'assets/options/q1_b.png', NULL, 0, 1),
(3, 1, 'Red Can', 'assets/options/q1_c.png', NULL, 1, 2),
(4, 1, 'Green Cone', 'assets/options/q1_d.png', NULL, 0, 3),
(5, 2, 'Green Can', 'assets/options/q2_a.png', NULL, 0, 0),
(6, 2, 'Red Can', 'assets/options/q2_b.png', NULL, 0, 1),
(7, 2, 'Green Cone', 'assets/options/q2_c.png', NULL, 1, 2),
(8, 2, 'Red Cone', 'assets/options/q2_d.png', NULL, 0, 3),
(9, 3, 'Green Cone', 'assets/options/q3_a.png', NULL, 0, 0),
(10, 3, 'Red Can', 'assets/options/q3_b.png', NULL, 0, 1),
(11, 3, 'Green Can', 'assets/options/q3_c.png', NULL, 1, 2),
(12, 3, 'Red Cone', 'assets/options/q3_d.png', NULL, 0, 3),
(13, 4, 'Red Can', 'assets/options/q4_a.png', NULL, 0, 0),
(14, 4, 'Green Cone', 'assets/options/q4_b.png', NULL, 0, 1),
(15, 4, 'Red Cone', 'assets/options/q4_c.png', NULL, 1, 2),
(16, 4, 'Green Can', 'assets/options/q4_d.png', NULL, 0, 3),
(17, 5, 'Red Ball', 'assets/options/q5_a.png', NULL, 1, 0),
(18, 5, 'Green Cone', 'assets/options/q5_b.png', NULL, 0, 1),
(19, 5, 'Red Can', 'assets/options/q5_c.png', NULL, 0, 2),
(20, 5, 'X Shape', 'assets/options/q5_d.png', NULL, 0, 3),
(21, 6, 'Two Black Balls', 'assets/options/q6_a.png', NULL, 1, 0),
(22, 6, 'Red Ball', 'assets/options/q6_b.png', NULL, 0, 1),
(23, 6, 'Yellow Cone', 'assets/options/q6_c.png', NULL, 0, 2),
(24, 6, 'X Shape', 'assets/options/q6_d.png', NULL, 0, 3),
(25, 7, 'Two cones up', 'assets/options/q7_a.png', NULL, 1, 0),
(26, 7, 'Two cones down', 'assets/options/q7_b.png', NULL, 0, 1),
(27, 7, 'Cones base to base', 'assets/options/q7_c.png', NULL, 0, 2),
(28, 7, 'Cones point to point', 'assets/options/q7_d.png', NULL, 0, 3),
(29, 8, 'Two cones down', 'assets/options/q8_a.png', NULL, 1, 0),
(30, 8, 'Two cones up', 'assets/options/q8_b.png', NULL, 0, 1),
(31, 8, 'Cones base to base', 'assets/options/q8_c.png', NULL, 0, 2),
(32, 8, 'Cones point to point', 'assets/options/q8_d.png', NULL, 0, 3),
(33, 9, 'Cones base to base', 'assets/options/q9_a.png', NULL, 1, 0),
(34, 9, 'Two cones up', 'assets/options/q9_b.png', NULL, 0, 1),
(35, 9, 'Two cones down', 'assets/options/q9_c.png', NULL, 0, 2),
(36, 9, 'Cones point to point', 'assets/options/q9_d.png', NULL, 0, 3),
(37, 10, 'Cones point to point', 'assets/options/q10_a.png', NULL, 1, 0),
(38, 10, 'Two cones up', 'assets/options/q10_b.png', NULL, 0, 1),
(39, 10, 'Two cones down', 'assets/options/q10_c.png', NULL, 0, 2),
(40, 10, 'Cones base to base', 'assets/options/q10_d.png', NULL, 0, 3),
(41, 11, 'X Shape', 'assets/options/q11_a.png', NULL, 1, 0),
(42, 11, 'Red Ball', 'assets/options/q11_b.png', NULL, 0, 1),
(43, 11, 'Two Black Balls', 'assets/options/q11_c.png', NULL, 0, 2),
(44, 11, 'Green Cone', 'assets/options/q11_d.png', NULL, 0, 3),
(45, 12, 'Red Can', 'assets/options/q12_a.png', NULL, 1, 0),
(46, 12, 'Green Can', 'assets/options/q12_b.png', NULL, 0, 1),
(47, 12, 'Red Cone', 'assets/options/q12_c.png', NULL, 0, 2),
(48, 12, 'Green Cone', 'assets/options/q12_d.png', NULL, 0, 3),
(49, 13, 'Two cones up', 'assets/options/q13_a.png', NULL, 1, 0),
(50, 13, 'Two cones down', 'assets/options/q13_b.png', NULL, 0, 1),
(51, 13, 'Cones base to base', 'assets/options/q13_c.png', NULL, 0, 2),
(52, 13, 'Cones point to point', 'assets/options/q13_d.png', NULL, 0, 3),
(53, 14, 'Option A', 'assets/options/q14_a.png', NULL, 1, 0),
(54, 14, 'Option B', 'assets/options/q14_b.png', NULL, 0, 1),
(55, 14, 'Option C', 'assets/options/q14_c.png', NULL, 0, 2),
(56, 14, 'Option D', 'assets/options/q14_d.png', NULL, 0, 3),
(57, 15, 'Option A', 'assets/options/q15_a.png', NULL, 0, 0),
(58, 15, 'Option B', 'assets/options/q15_b.png', NULL, 1, 1),
(59, 15, 'Option C', 'assets/options/q15_c.png', NULL, 0, 2),
(60, 15, 'Option D', 'assets/options/q15_d.png', NULL, 0, 3),
(61, 16, 'Option A', 'assets/options/q16_a.png', NULL, 0, 0),
(62, 16, 'Option B', 'assets/options/q16_b.png', NULL, 0, 1),
(63, 16, 'Option C', 'assets/options/q16_c.png', NULL, 1, 2),
(64, 16, 'Option D', 'assets/options/q16_d.png', NULL, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` char(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `correct` int(11) NOT NULL DEFAULT 0,
  `score` decimal(5,1) NOT NULL DEFAULT 0.0,
  `elapsed` int(11) NOT NULL DEFAULT 0 COMMENT 'seconds',
  `details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON: per-question breakdown',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `token`, `total`, `correct`, `score`, `elapsed`, `details`, `created_at`) VALUES
(2, 'fff1585dddea0633', 16, 1, '6.3', 17, '[{\"id\":1,\"prompt\":\"Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark.\",\"answerLabel\":\"IALA Region A \\u2013 Lateral Port Hand Mark (Red Can)\",\"answered\":true,\"optionIndex\":\"\\\"\",\"correct\":false,\"correctLabel\":\"Red Can\"},{\"id\":2,\"prompt\":\"Drag the correct top mark onto the IALA Region A Lateral Starboard Hand Mark.\",\"answerLabel\":\"IALA Region A \\u2013 Lateral Starboard Hand Mark (Green Cone)\",\"answered\":true,\"optionIndex\":\"1\",\"correct\":false,\"correctLabel\":\"Green Cone\"},{\"id\":3,\"prompt\":\"Drag the correct top mark onto the IALA Region B Lateral Port Hand Mark.\",\"answerLabel\":\"IALA Region B \\u2013 Lateral Port Hand Mark (Green Can)\",\"answered\":true,\"optionIndex\":\"\\\"\",\"correct\":false,\"correctLabel\":\"Green Can\"},{\"id\":4,\"prompt\":\"Drag the correct top mark onto the IALA Region B Lateral Starboard Hand Mark.\",\"answerLabel\":\"IALA Region B \\u2013 Lateral Starboard Hand Mark (Red Cone)\",\"answered\":true,\"optionIndex\":\":\",\"correct\":false,\"correctLabel\":\"Red Cone\"},{\"id\":5,\"prompt\":\"Drag the correct top mark onto the Safe Water Mark.\",\"answerLabel\":\"Safe Water Mark \\u2013 Red & White Vertical Stripes, Red Ball Topmark\",\"answered\":true,\"optionIndex\":\"2\",\"correct\":false,\"correctLabel\":\"Red Ball\"},{\"id\":6,\"prompt\":\"Drag the correct top mark onto the Isolated Danger Mark.\",\"answerLabel\":\"Isolated Danger Mark \\u2013 Two Black Balls Topmark\",\"answered\":true,\"optionIndex\":\"}\",\"correct\":true,\"correctLabel\":\"Two Black Balls\"},{\"id\":7,\"prompt\":\"Drag the correct top mark onto the North Cardinal Mark.\",\"answerLabel\":\"North Cardinal Mark \\u2013 Two cones points up\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Two cones up\"},{\"id\":8,\"prompt\":\"Drag the correct top mark onto the South Cardinal Mark.\",\"answerLabel\":\"South Cardinal Mark \\u2013 Two cones points down\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Two cones down\"},{\"id\":9,\"prompt\":\"Drag the correct top mark onto the East Cardinal Mark.\",\"answerLabel\":\"East Cardinal Mark \\u2013 Two cones base to base\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Cones base to base\"},{\"id\":10,\"prompt\":\"Drag the correct top mark onto the West Cardinal Mark.\",\"answerLabel\":\"West Cardinal Mark \\u2013 Two cones point to point\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Cones point to point\"},{\"id\":11,\"prompt\":\"Drag the correct top mark onto the Special Mark.\",\"answerLabel\":\"Special Mark \\u2013 Yellow X topmark\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"X Shape\"},{\"id\":12,\"prompt\":\"Drag the correct top mark onto the IALA Region A Lateral Port Hand Mark (Light)\",\"answerLabel\":\"IALA Region A Lateral Port Hand Mark with light\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Red Can\"},{\"id\":13,\"prompt\":\"Drag the correct top mark onto this cardinal mark.\",\"answerLabel\":\"Cardinal Mark identification\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Two cones up\"},{\"id\":14,\"prompt\":\"Drag the correct top mark onto this mark.\",\"answerLabel\":\"Mark identification\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Option A\"},{\"id\":15,\"prompt\":\"Drag the correct top mark onto this mark.\",\"answerLabel\":\"Mark identification\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Option B\"},{\"id\":16,\"prompt\":\"Drag the correct top mark onto this mark.\",\"answerLabel\":\"Mark identification\",\"answered\":false,\"optionIndex\":null,\"correct\":false,\"correctLabel\":\"Option C\"}]', '2026-09-29 15:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('allow_retry', 'true'),
('bg_type', '\"ocean\"'),
('passing_score', '70'),
('questions_per_quiz', '0'),
('quiz_subtitle', '\"IALA Buoyage System Quiz\"'),
('quiz_title', '\"Pickup & Drop the Correct Top Mark\"'),
('show_answer_card', 'false'),
('theme_color', '\"#6c5ce7\"'),
('voice_enabled', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `text_questions`
--

CREATE TABLE `text_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `prompt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_card_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'optional explanation',
  `question_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'path or https URL, optional',
  `question_image_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'canvas drawing JS (optional)',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `text_questions`
--

INSERT INTO `text_questions` (`id`, `prompt`, `answer_card_text`, `question_image`, `question_image_code`, `active`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'In IALA Region A, what colour is a port hand lateral mark?', 'IALA Region A\\nPort hand lateral mark: red, can topmark', '', NULL, 1, 1, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(2, 'In IALA Region A, what is the topmark of a starboard hand lateral mark?', 'IALA Region A\\nStarboard hand lateral mark: single green cone, point up', '', NULL, 1, 2, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(3, 'In IALA Region B, what colour is a port hand lateral mark?', 'IALA Region B\\nPort hand lateral mark: green, can topmark', '', NULL, 1, 3, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(4, 'What is the topmark of a North cardinal mark?', 'North cardinal mark\\nTopmark: two black cones, points up', '', NULL, 1, 4, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(5, 'What is the topmark of a South cardinal mark?', 'South cardinal mark\\nTopmark: two black cones, points down', '', NULL, 1, 5, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(6, 'What is the topmark of an East cardinal mark?', 'East cardinal mark\\nTopmark: two black cones, base to base', '', NULL, 1, 6, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(7, 'What is the topmark of a West cardinal mark?', 'West cardinal mark\\nTopmark: two black cones, point to point', '', NULL, 1, 7, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(8, 'What is the topmark of an Isolated danger mark?', 'Isolated danger mark\\nTopmark: two black balls', '', NULL, 1, 8, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(9, 'What does a Safe water mark look like?', 'Safe water mark\\nRed and white vertical stripes, single red ball topmark', '', NULL, 1, 9, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(10, 'What colour is a Special mark?', 'Special mark\\nYellow, with a yellow X topmark', '', NULL, 1, 10, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(11, 'On which side of a North cardinal mark is the safe water?', 'North cardinal mark\\nSafe water lies to the north of the mark', '', NULL, 1, 11, '2026-09-29 21:53:51', '2026-09-29 21:53:51'),
(12, 'What colour light does a Special mark show?', 'Special mark\\nLight: yellow', '', NULL, 1, 12, '2026-09-29 21:53:51', '2026-09-29 21:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `text_question_options`
--

CREATE TABLE `text_question_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `text_question_options`
--

INSERT INTO `text_question_options` (`id`, `question_id`, `label`, `is_correct`, `sort`) VALUES
(1, 1, 'Green', 0, 0),
(2, 1, 'Red', 1, 1),
(3, 1, 'Yellow', 0, 2),
(4, 1, 'Black and yellow', 0, 3),
(5, 2, 'A single red can', 0, 0),
(6, 2, 'Two black balls', 0, 1),
(7, 2, 'A single green cone, point up', 1, 2),
(8, 2, 'A yellow X', 0, 3),
(9, 3, 'Green', 1, 0),
(10, 3, 'Red', 0, 1),
(11, 3, 'Yellow', 0, 2),
(12, 3, 'Black and red', 0, 3),
(13, 4, 'Two black cones, points down', 0, 0),
(14, 4, 'Two black cones, base to base', 0, 1),
(15, 4, 'Two black cones, point to point', 0, 2),
(16, 4, 'Two black cones, points up', 1, 3),
(17, 5, 'Two black cones, points down', 1, 0),
(18, 5, 'Two black cones, points up', 0, 1),
(19, 5, 'Two black cones, point to point', 0, 2),
(20, 5, 'Two black cones, base to base', 0, 3),
(21, 6, 'Two black cones, point to point', 0, 0),
(22, 6, 'Two black cones, base to base', 1, 1),
(23, 6, 'Two black cones, points up', 0, 2),
(24, 6, 'Two black cones, points down', 0, 3),
(25, 7, 'Two black cones, points up', 0, 0),
(26, 7, 'Two black cones, base to base', 0, 1),
(27, 7, 'Two black cones, point to point', 1, 2),
(28, 7, 'Two black cones, points down', 0, 3),
(29, 8, 'A single red ball', 0, 0),
(30, 8, 'A yellow X', 0, 1),
(31, 8, 'A single green cone', 0, 2),
(32, 8, 'Two black balls', 1, 3),
(33, 9, 'Red and white vertical stripes, single red ball topmark', 1, 0),
(34, 9, 'Black with a red band, two black balls topmark', 0, 1),
(35, 9, 'Yellow with a yellow X topmark', 0, 2),
(36, 9, 'Red with a red can topmark', 0, 3),
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_username` (`username`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active_sort` (`active`,`sort`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_question` (`question_id`,`sort`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_token` (`token`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `text_questions`
--
ALTER TABLE `text_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active_sort` (`active`,`sort`);

--
-- Indexes for table `text_question_options`
--
ALTER TABLE `text_question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_question` (`question_id`,`sort`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `text_questions`
--
ALTER TABLE `text_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `text_question_options`
--
ALTER TABLE `text_question_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `text_question_options`
--
ALTER TABLE `text_question_options`
  ADD CONSTRAINT `fk_text_options_question` FOREIGN KEY (`question_id`) REFERENCES `text_questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
