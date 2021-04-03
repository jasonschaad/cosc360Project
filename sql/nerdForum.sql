-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 03, 2021 at 02:28 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nerdForum`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`) VALUES
(1, 'Marvel Cinematic Universe'),
(2, 'World of Warcraft'),
(3, 'Pokemon Go'),
(4, 'Star Wars');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postId` int(11) NOT NULL,
  `postDate` datetime NOT NULL,
  `title` varchar(100) NOT NULL,
  `postContent` text NOT NULL,
  `postUserId` int(11) NOT NULL,
  `postCategoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postId`, `postDate`, `title`, `postContent`, `postUserId`, `postCategoryId`) VALUES
(1, '2021-04-02 16:03:51', 'Who would win if Thor and The Hulk fought to the death?', 'I don\'t know who would win, what do y\'all think?', 1, 1),
(2, '2021-04-02 16:06:22', 'Jar Jar Binks is definitely a Jedi', 'it\'s all an act. He is secretly a jedi. There is no way JAR JAR BINKS can jump 20 feet without using the force.', 2, 4),
(3, '2021-04-02 16:07:11', 'Fireball go brrrrrr', 'Phat fireball crit. Enjoy gamers. ', 3, 2),
(4, '2021-04-02 16:08:12', 'SHINY!!!! YOOOOO LETS GOO!!!!', 'woohoo!! shiny gengar!!', 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `replyId` int(11) NOT NULL,
  `content` text NOT NULL,
  `replyDate` datetime NOT NULL,
  `replyUserId` int(11) NOT NULL,
  `replyPostId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`replyId`, `content`, `replyDate`, `replyUserId`, `replyPostId`) VALUES
(1, 'Thor would definitely Win, Mjolnir goes too hard.', '2021-04-02 16:11:46', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `securityLevel` int(11) NOT NULL COMMENT 'denotes a user''s security level. 2 == admin, 1 == user 0 == not a user',
  `creationDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `password`, `firstName`, `lastName`, `email`, `securityLevel`, `creationDate`) VALUES
(1, 'testuser1', 'password', 'test1', 'user1', 'test1@gmail.vom', 2, '2021-04-02 15:51:55'),
(2, 'testuser2', 'password', 'test2', 'user2', 'test2@gmail.com', 2, '2021-04-02 15:54:44'),
(3, 'testuser3', 'password', 'test3', 'user3', 'test3@gmail.com', 2, '2021-04-02 15:55:38'),
(4, 'testuser4', 'password', 'test4', 'user4', 'test4@gmail.com', 2, '2021-04-02 15:56:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postId`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`replyId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `replyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
