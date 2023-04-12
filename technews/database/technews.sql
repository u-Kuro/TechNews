-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2023 at 11:56 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `technews`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `post` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `post`) VALUES
(34, 'Mobile Phones & Tablets', 2),
(31, 'Computer Hardware', 2),
(32, 'Software', 2),
(33, 'Cybersecurity', 2);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `post_date` varchar(50) NOT NULL,
  `author` int(11) NOT NULL,
  `post_img` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `title`, `description`, `category`, `post_date`, `author`, `post_img`) VALUES
(36, 'First Post', '                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ultrices tempus eros, non porta risus cursus a. Pellentesque tempor justo at lectus faucibus mattis. Vestibulum interdum turpis orci, dapibus gravida lacus egestas id. Nunc quis egestas leo. Morbi eget pretium nulla, elementum placerat lacus. Phasellus fringilla mauris a mi scelerisque pretium. Vivamus lacus nisi, placerat ac mattis pharetra, tristique a urna. Aenean pharetra aliquet lacus, vitae tempor est tempus et. Sed sed nisi eleifend, tempus tortor ut, convallis massa. In mollis nisl a orci fermentum venenatis vel vitae turpis. Vivamus fermentum massa nibh, nec blandit est mattis iaculis.                ', '34', '19 Jan, 2020', 27, '1681186473-iphone.jpg'),
(37, 'Second Post', '                Maecenas turpis sapien, finibus nec augue a, commodo feugiat lectus. Nam feugiat, magna et vulputate varius, ligula dui placerat lorem, eu hendrerit magna mauris ut lectus. Suspendisse mattis diam est, rutrum ullamcorper eros congue sed. Nunc gravida sem nunc, et egestas quam sodales eget. Aliquam convallis varius dapibus. Nam ornare risus in quam condimentum, quis tempor nisi mattis. Cras id metus ut diam aliquet commodo. Curabitur quis sapien vitae massa tincidunt iaculis.                ', '31', '19 Jan, 2020', 27, '1681186408-gpu.jpg'),
(38, 'Third Post', '                Sed tincidunt sem vehicula, posuere est at, dapibus erat. Integer nec iaculis magna. Maecenas egestas sed odio sit amet maximus. Morbi viverra nisi euismod, convallis mi vitae, pretium quam. Sed hendrerit purus tortor, et cursus erat convallis eu. Integer quis consectetur arcu. Vivamus rutrum mollis volutpat.                ', '32', '19 Jan, 2020', 27, '1681186368-google.jpg'),
(39, 'Fourth Post', '                Pellentesque consectetur, turpis sit amet ullamcorper tristique, est massa consectetur ex, eget dapibus sapien augue eu turpis. Phasellus molestie euismod ultrices. Donec lorem lorem, volutpat vitae tincidunt quis, fringilla eu mauris. Morbi ac ipsum blandit, volutpat quam vitae, efficitur sem. Mauris a nunc nec dolor condimentum congue. Cras iaculis, ex rhoncus laoreet interdum, libero orci euismod risus, ut porta sem arcu ac lorem. Mauris lacinia efficitur ligula sed porta. Nullam a leo non risus ultricies cursus. Mauris scelerisque congue ipsum vel bibendum.                ', '34', '19 Jan, 2020', 27, '1681186312-phones.jpg'),
(40, 'Fifth Post', '                Cras ullamcorper metus velit, in cursus lorem finibus eu. Pellentesque in risus sed diam pulvinar rhoncus sed in libero. Curabitur orci ipsum, convallis id bibendum sit amet, pretium sit amet massa. Mauris fermentum fermentum diam, et porttitor diam blandit a. Quisque tempor ante ut ligula convallis porta. Nulla nec ante mattis, auctor velit in, efficitur massa. Etiam aliquam massa vel sapien vulputate, ut congue est fringilla. Nunc non eros consequat, venenatis ligula eget, imperdiet risus. Maecenas ultrices purus et dolor pharetra rhoncus. Vestibulum congue augue ultricies leo cursus sollicitudin. Duis sollicitudin semper lectus, et tempus purus. Nam eleifend ante vitae nibh ultricies finibus. Vestibulum sollicitudin odio facilisis ex varius, et accumsan ipsum auctor. Nam non malesuada purus, et vestibulum libero. Phasellus gravida eu mi at vulputate.                ', '32', '19 Jan, 2020', 27, '1681186252-software.jpg'),
(44, 'Cybersecurity 1012', '                                                                      This is a cybersecurity post                                                                                                                   ', '33', '27 Apr, 2020', 27, 'cybersecurity.jpg'),
(42, 'Testing Recent Post ', '                Suspendisse sed ultrices tortor. In imperdiet sem fringilla, ultricies nunc non, condimentum nunc. Praesent ac sollicitudin enim, commodo pellentesque nunc. Integer bibendum sollicitudin augue in sagittis. Proin scelerisque lacus maximus mauris ornare semper. Aliquam mi ante, euismod vitae ligula quis, fermentum tincidunt arcu. Etiam elementum sed nisi et scelerisque. Integer aliquet venenatis aliquam. Proin tempor dui sed dui pulvinar facilisis. Etiam imperdiet molestie iaculis.                ', '31', '21 Jan, 2020', 27, '1681186165-hardware.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `websitename` varchar(60) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `footerdesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `websitename`, `logo`, `footerdesc`) VALUES
(1, 'Technology News', 'tech-news-smaller.png', '© Copyright 2023 Technology News | Powered by IT120P');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `username`, `password`, `role`) VALUES
(39, 'user', 'user', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(27, 'admin', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `post_id` (`post_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
