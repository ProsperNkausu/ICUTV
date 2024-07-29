-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2024 at 11:51 PM
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
-- Database: `icutv`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `Email`, `password`) VALUES
(1, 'admin', 'admin@icutv.com', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `advertisment`
--

CREATE TABLE `advertisment` (
  `ad_id` int(11) NOT NULL,
  `ad_image` varchar(250) NOT NULL,
  `ad_link` varchar(250) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advertisment`
--

INSERT INTO `advertisment` (`ad_id`, `ad_image`, `ad_link`, `date`) VALUES
(2, 'uploads/advert.jpg', 'https://www.linkedin.com/posts/icuzambia_attention-icu-students-have-you-entered-activity-7218649405191573507-CMi-?utm_source=share&utm_medium=member_desktop', '2024-07-29 03:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `c_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`c_id`, `category_name`) VALUES
(1, 'Business'),
(2, 'Innovation'),
(3, 'Sports'),
(4, 'Travel'),
(5, 'News'),
(6, 'Culture'),
(7, 'Education');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `cc_id` int(11) NOT NULL,
  `user` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `news_title` varchar(250) NOT NULL,
  `n_id` int(11) NOT NULL,
  `comment` varchar(250) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`cc_id`, `user`, `author`, `news_title`, `n_id`, `comment`, `date`) VALUES
(4, 'Prosper', 'Prosper', 'ENDING YOUR EXAMS', 1, 'we await for the results now', '2024-07-26'),
(5, 'jahman', 'jahman', 'Schools have opened for returning students', 5, 'testing', '2024-07-26'),
(8, 'Prosper', '', 'ENDING YOUR EXAMS', 1, 'Admin: we will notify you once they are out ', '2024-07-26'),
(9, 'David Nkhoma', 'David Nkhoma', 'welcome', 6, 'thank you', '2024-07-28'),
(10, 'Cephas', 'Cephas', 'Graduation ', 7, 'It was quite a wonderful day to celebrate and witness the graduating students', '2024-07-28');

-- --------------------------------------------------------

--
-- Table structure for table `highlight`
--

CREATE TABLE `highlight` (
  `h_id` int(11) NOT NULL,
  `highlight_1` varchar(250) NOT NULL,
  `highlight_2` varchar(250) NOT NULL,
  `highlight_3` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `highlight`
--

INSERT INTO `highlight` (`h_id`, `highlight_1`, `highlight_2`, `highlight_3`) VALUES
(3, 'Bank of Zambia Dispels Cheque Phase-Out Rumors.', 'JUNE 2024 EXAMINATIONS RESULTS ARE BEING UPLOADED GRADUALLY', 'Call for Papers: 2024 International Multi-Disciplinary Conference');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'prosper@gmail.com', '2024-07-28 05:34:25'),
(2, 'jarman@gmail.com', '2024-07-28 22:08:05'),
(3, 'jash@gmail.com', '2024-07-29 00:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `news_post`
--

CREATE TABLE `news_post` (
  `n_id` int(11) NOT NULL,
  `news_image` varchar(250) NOT NULL,
  `newsImage2` varchar(250) NOT NULL,
  `newsImage3` varchar(250) NOT NULL,
  `news_title` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `news_article` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_post`
--

INSERT INTO `news_post` (`n_id`, `news_image`, `newsImage2`, `newsImage3`, `news_title`, `category`, `news_article`, `author`, `date`) VALUES
(1, 'uploads/10.jpg', 'uploads/11.jpg', 'uploads/12.jpg', 'END OF JUNE EXAMS', '1', 'When you finish your exam, ensure all your particulars are correctly filled in before handing in your answer script. Remember to raise your hand, and an invigilator will collect your script and allow you to leave. No re-entry is permitted once you le', 'Admin', '2024-07-23'),
(5, 'uploads/6.jpg', 'uploads/8.jpg', 'uploads/17.jpg', 'Schools open', '7', 'Returning students will be opening on the 15th on July 2024 ', 'Admin', '2024-07-24'),
(6, 'uploads/23.jpg', 'uploads/22.jpg', 'uploads/18.jpg', 'Orientation day', '1', 'welcome', 'Admin', '2024-07-24'),
(8, 'uploads/graduacation.jpeg', 'uploads/grand master1.jpeg', 'uploads/grad.jpg', 'Graduation day', '7', 'we celebrate the information and communications university\'s 3rd graduation ceremony which will be held at the Mulungushi conference center.', 'Admin', '2024-07-29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `Fullname` varchar(250) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `Fullname`, `Email`, `Password`, `date`) VALUES
(5, 'Steven Chitalu', 'Steven@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', '2024-07-29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisment`
--
ALTER TABLE `advertisment`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`cc_id`);

--
-- Indexes for table `highlight`
--
ALTER TABLE `highlight`
  ADD PRIMARY KEY (`h_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_post`
--
ALTER TABLE `news_post`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `advertisment`
--
ALTER TABLE `advertisment`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `cc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `highlight`
--
ALTER TABLE `highlight`
  MODIFY `h_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news_post`
--
ALTER TABLE `news_post`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
