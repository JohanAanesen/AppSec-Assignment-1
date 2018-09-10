-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 11. Sep, 2018 01:55 AM
-- Server-versjon: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `reply`
--

CREATE TABLE `reply` (
  `replyId` int(11) NOT NULL,
  `topicId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `editTimestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `topic`
--

CREATE TABLE `topic` (
  `topicId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `editTimestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateJoined` date NOT NULL,
  `loginAttempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `user`
--

INSERT INTO `user` (`userId`, `username`, `email`, `password`, `dateJoined`, `loginAttempts`) VALUES
(1, 'kongebra', 'sveindani@gmail.com', '$2y$10$qmC84pBRA2564Y/GDIkuxuOm4AxTCSpc.DV8ni1z.1JUkDF43Aevy', '2018-09-07', 0),
(2, 'johndoe', 'john@doe.com', '$2y$10$qp1ak86zuYc3vapd9nBGNOFMrQWy/hqV4pQBxXWZ72UJMgRB7y4eC', '2018-09-10', 0),
(3, 'admin', 'admin@localhost.com', '$2y$10$REm2dY9Pj8s.GaJTUI2NAOJ04CaaZJUOoX016FvqFBd61YlocG7A6', '2018-09-10', 0),
(4, 'olanordmann', 'ola@nordmann.no', '$2y$10$Nb1kJ.JwcyBhIw1doIuy5ebI9E/7T3qdVaQI7DYPJE6.QJAzfnjdy', '2018-09-11', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `userrole`
--

CREATE TABLE `userrole` (
  `userId` int(11) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`replyId`),
  ADD KEY `userId_reply` (`userId`),
  ADD KEY `topicId_reply` (`topicId`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`topicId`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `userId_topic` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `userrole`
--
ALTER TABLE `userrole`
  ADD KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `replyId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `topicId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `topicId_reply` FOREIGN KEY (`topicId`) REFERENCES `topic` (`topicId`),
  ADD CONSTRAINT `userId_reply` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Begrensninger for tabell `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `categoryId` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`),
  ADD CONSTRAINT `userId_topic` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Begrensninger for tabell `userrole`
--
ALTER TABLE `userrole`
  ADD CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
