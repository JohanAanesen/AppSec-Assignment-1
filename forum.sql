DROP SCHEMA

IF EXISTS forum;
	CREATE SCHEMA forum CHAR SET = utf8;

USE forum;

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) UNIQUE NOT NULL,
  PRIMARY KEY (`categoryId`)
);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) UNIQUE NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateJoined` date NOT NULL,
  `loginAttempts` int(11) NOT NULL,
  PRIMARY KEY (`userId`)
);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `topic`
--

CREATE TABLE `topic` (
  `topicId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `editTimestamp` datetime NOT NULL,
  PRIMARY KEY (`topicId`),
  FOREIGN KEY (`categoryId`) REFERENCES category(categoryId),
  FOREIGN KEY (`userId`) REFERENCES user(userId)
);


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `reply`
--

CREATE TABLE `reply` (
  `replyId` int(11) NOT NULL AUTO_INCREMENT,
  `topicId` int(11) NOT NULL ,
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `editTimestamp` datetime NOT NULL,
  PRIMARY KEY (`replyId`),
  FOREIGN KEY (`userId`) REFERENCES user(userId),
  FOREIGN KEY (`topicId`) REFERENCES topic(topicId)
);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `userrole`
--

CREATE TABLE `userrole` (
  `userId` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  FOREIGN KEY (`userId`) REFERENCES user(userId)
);
-- ----------------------------------------------------------


INSERT INTO `user` (`username`, `email`, `password`, `dateJoined`, `loginAttempts`) VALUES
('kongebra', 'sveindani@gmail.com', '$2y$10$qmC84pBRA2564Y/GDIkuxuOm4AxTCSpc.DV8ni1z.1JUkDF43Aevy', '2018-09-07', 0),
('johndoe', 'john@doe.com', '$2y$10$qp1ak86zuYc3vapd9nBGNOFMrQWy/hqV4pQBxXWZ72UJMgRB7y4eC', '2018-09-10', 0),
('admin', 'admin@localhost.com', '$2y$10$REm2dY9Pj8s.GaJTUI2NAOJ04CaaZJUOoX016FvqFBd61YlocG7A6', '2018-09-10', 0),
('olanordmann', 'ola@nordmann.no', '$2y$10$Nb1kJ.JwcyBhIw1doIuy5ebI9E/7T3qdVaQI7DYPJE6.QJAzfnjdy', '2018-09-11', 0),
('johan', 'johan@hotmail.no', '$2y$10$XPnAtvgzY9ZLFjrF1ZnYG.7NyobxL6HLhZlNBnZhpPvP1zNA5LuMC', '2018-10-09', 0);

INSERT INTO `category` (`title`) VALUE ('Kittenmittens');

INSERT INTO `userrole` (userId, role) VALUES
(1, 0),
(2, 0),
(3, 1),
(4, 0),
(5, 1);
