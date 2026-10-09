-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 02:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scenic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `destination`
--

CREATE TABLE `destination` (
  `Dest_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `gps` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `avg_rating` decimal(3,2) DEFAULT 0.00,
  `Peace_ind` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destination`
--

INSERT INTO `destination` (`Dest_ID`, `Name`, `gps`, `Description`, `avg_rating`, `Peace_ind`, `image`) VALUES
(1, 'Tokyo', '35.6762, 139.6503', 'A vibrant Japanese city famous for technology, culture, food and entertainment.', 4.80, 'Very Safe', NULL),
(2, 'Kyoto', '35.0116, 135.7681', 'Historic Japanese city known for temples, shrines and traditional culture.', 4.70, 'Very Safe', NULL),
(3, 'Paris', '48.8566, 2.3522', 'The capital of France, famous for the Eiffel Tower, museums and historic landmarks.', 4.60, 'Safe', 'dest_6a990994cc0ef6.75562273.webp'),
(4, 'New York', '40.741895,-73.989308', 'A bustling global metropolis famous for iconic skyscrapers, diverse culture, world-class dining, and endless entertainment.', 5.00, 'Neutral', 'dest_6a99098a255f11.57045954.webp'),
(5, 'kiik', '40.741895,-73.989308', 'ikki', 0.00, 'ikik', 'dest_6a992176e4f7a0.88324787.webp');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feed_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Rating` decimal(2,1) NOT NULL,
  `media_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feed_id`, `date`, `Rating`, `media_id`, `user_id`) VALUES
(1, '2026-09-02 19:41:57', 1.9, 6, 1),
(2, '2026-09-02 19:59:43', 2.0, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_media`
--

CREATE TABLE `gallery_media` (
  `Media_ID` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `up_d` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_media`
--

INSERT INTO `gallery_media` (`Media_ID`, `image`, `up_d`, `user_id`) VALUES
(6, 'gallery_6a987b80bfc1d2.19177011.jpg', '2026-09-02 19:39:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `postcard`
--

CREATE TABLE `postcard` (
  `postcard_id` int(11) NOT NULL,
  `suggestions` varchar(255) DEFAULT NULL,
  `up_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Text` text NOT NULL,
  `end_d` date DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Active',
  `guide_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postcard`
--

INSERT INTO `postcard` (`postcard_id`, `suggestions`, `up_date`, `Text`, `end_d`, `status`, `guide_id`, `user_id`) VALUES
(1, 'Do visit Local Market', '2026-09-03 09:05:38', 'I visited Tokyo.\r\nIt was awesome', '2027-06-06', 'Active', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `seasonal_advisory`
--

CREATE TABLE `seasonal_advisory` (
  `Advis_id` int(11) NOT NULL,
  `Bst_month` varchar(50) NOT NULL,
  `Reason` text NOT NULL,
  `Dest_id` int(11) NOT NULL,
  `trans_cost` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seasonal_advisory`
--

INSERT INTO `seasonal_advisory` (`Advis_id`, `Bst_month`, `Reason`, `Dest_id`, `trans_cost`) VALUES
(1, 'March - April', 'Excellent weather and cherry blossom season.', 1, 450.00),
(2, 'October - November', 'Pleasant temperatures and beautiful autumn scenery.', 1, 400.00),
(3, 'March - May', 'Comfortable weather and beautiful spring scenery.', 2, 420.00),
(4, 'April - June', 'Good weather for sightseeing and outdoor activities.', 3, 500.00),
(5, 'Summer.', '', 4, 0.00),
(11, 'Summer', 'vibrant', 1, 150.00),
(12, 'Summer.', 'Vibrant', 1, 150.00),
(13, 'june', 'ok', 2, 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket`
--

CREATE TABLE `support_ticket` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Open',
  `cr_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_ticket`
--

INSERT INTO `support_ticket` (`ticket_id`, `user_id`, `Description`, `Status`, `cr_date`) VALUES
(4, 2, '...', 'Resolved', '2026-09-02 14:28:44'),
(5, 2, 'j', 'Open', '2026-09-02 20:18:55'),
(6, 2, 'u', 'Resolved', '2026-09-03 06:42:40'),
(7, 2, 'jj', 'Resolved', '2026-09-03 09:46:54');

-- --------------------------------------------------------

--
-- Table structure for table `tour_guide`
--

CREATE TABLE `tour_guide` (
  `guide_id` int(11) NOT NULL,
  `guide_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `Phy_guideFlag` tinyint(1) DEFAULT 0,
  `hourly_rate` decimal(10,2) DEFAULT 0.00,
  `Vir_guideFlag` tinyint(1) DEFAULT 0,
  `Pod_URL` varchar(255) DEFAULT NULL,
  `offline_map` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_guide`
--

INSERT INTO `tour_guide` (`guide_id`, `guide_name`, `contact_number`, `Phy_guideFlag`, `hourly_rate`, `Vir_guideFlag`, `Pod_URL`, `offline_map`) VALUES
(1, 'Osamu Dazai', '+1-555-0192', 1, 45.00, 1, 'https://podcast.com/osamu-dazai', NULL),
(2, 'Roronoa Zoro', '+1-555-0143', 1, 30.00, 0, NULL, NULL),
(3, 'Lelouch Lamperouge', '+1-555-0188', 0, 15.00, 1, 'https://podcast.com/lelouch-lamperouge', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `travel_plan`
--

CREATE TABLE `travel_plan` (
  `plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Dest_ID` int(11) DEFAULT NULL,
  `plan_title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `guide_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_plan`
--

INSERT INTO `travel_plan` (`plan_id`, `user_id`, `Dest_ID`, `plan_title`, `start_date`, `end_date`, `budget`, `guide_id`) VALUES
(4, 2, 5, 'jj', '2026-09-02', '2026-09-03', 350.00, NULL),
(5, 1, 5, 'jj', '2026-09-02', '2026-10-15', 66.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `join_d` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('tourist','admin') DEFAULT 'tourist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `pass`, `join_d`, `type`) VALUES
(1, 'Prangan', 'prangan.barua@g.bracu.ac.bd', '$2y$10$5O0Y99E0joEtn2H6Ayrce.MWzgoDxrvroeRcjBpciHIm.r3wSM482', '2026-09-02 14:25:32', 'admin'),
(2, 't', 't@gmail.com', '$2y$10$rytD1h7flCVsIcCKks3zlOpm6mdpgp2pdpWzA1/HESr/E4LiW70Du', '2026-09-02 14:28:29', 'tourist');

-- --------------------------------------------------------

--
-- Table structure for table `user_trpref`
--

CREATE TABLE `user_trpref` (
  `user_id` int(11) NOT NULL,
  `Travel_plan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_trpref`
--

INSERT INTO `user_trpref` (`user_id`, `Travel_plan`) VALUES
(1, 'Natural Beauty');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `destination`
--
ALTER TABLE `destination`
  ADD PRIMARY KEY (`Dest_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feed_id`),
  ADD KEY `feedback_media_fk` (`media_id`),
  ADD KEY `feedback_user_fk` (`user_id`);

--
-- Indexes for table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD PRIMARY KEY (`Media_ID`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `postcard`
--
ALTER TABLE `postcard`
  ADD PRIMARY KEY (`postcard_id`),
  ADD KEY `postcard_user_fk` (`user_id`),
  ADD KEY `postcard_guide_fk` (`guide_id`);

--
-- Indexes for table `seasonal_advisory`
--
ALTER TABLE `seasonal_advisory`
  ADD PRIMARY KEY (`Advis_id`),
  ADD KEY `Dest_id` (`Dest_id`);

--
-- Indexes for table `support_ticket`
--
ALTER TABLE `support_ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `support_ticket_user_fk` (`user_id`);

--
-- Indexes for table `tour_guide`
--
ALTER TABLE `tour_guide`
  ADD PRIMARY KEY (`guide_id`);

--
-- Indexes for table `travel_plan`
--
ALTER TABLE `travel_plan`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `Dest_ID` (`Dest_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_trpref`
--
ALTER TABLE `user_trpref`
  ADD PRIMARY KEY (`user_id`,`Travel_plan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `destination`
--
ALTER TABLE `destination`
  MODIFY `Dest_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery_media`
--
ALTER TABLE `gallery_media`
  MODIFY `Media_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `postcard`
--
ALTER TABLE `postcard`
  MODIFY `postcard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seasonal_advisory`
--
ALTER TABLE `seasonal_advisory`
  MODIFY `Advis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `support_ticket`
--
ALTER TABLE `support_ticket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tour_guide`
--
ALTER TABLE `tour_guide`
  MODIFY `guide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `travel_plan`
--
ALTER TABLE `travel_plan`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_media_fk` FOREIGN KEY (`media_id`) REFERENCES `gallery_media` (`Media_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD CONSTRAINT `gallery_media_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `postcard`
--
ALTER TABLE `postcard`
  ADD CONSTRAINT `postcard_guide_fk` FOREIGN KEY (`guide_id`) REFERENCES `tour_guide` (`guide_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `postcard_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `seasonal_advisory`
--
ALTER TABLE `seasonal_advisory`
  ADD CONSTRAINT `seasonal_advisory_fk` FOREIGN KEY (`Dest_id`) REFERENCES `destination` (`Dest_ID`) ON DELETE CASCADE;

--
-- Constraints for table `support_ticket`
--
ALTER TABLE `support_ticket`
  ADD CONSTRAINT `support_ticket_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_plan`
--
ALTER TABLE `travel_plan`
  ADD CONSTRAINT `travel_plan_destination_fk` FOREIGN KEY (`Dest_ID`) REFERENCES `destination` (`Dest_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `travel_plan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_plan_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `tour_guide` (`guide_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_trpref`
--
ALTER TABLE `user_trpref`
  ADD CONSTRAINT `user_trpref_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
