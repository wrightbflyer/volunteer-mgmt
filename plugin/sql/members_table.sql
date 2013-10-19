CREATE TABLE `members` (
  `id` char(36) NOT NULL,
  `FirstName` varchar(256) NOT NULL,
  `LastName` varchar(256) DEFAULT NULL,
  `SpouseName` varchar(256) DEFAULT NULL,
  `MemberType` varchar(64) NOT NULL,
  `MemberSince` datetime DEFAULT NULL,
  `RenewalDate` datetime DEFAULT NULL,
  `Address` char(256) DEFAULT NULL,
  `City` char(64) DEFAULT NULL,
  `State` varchar(64) DEFAULT NULL,
  `Zip` varchar(32) DEFAULT NULL,
  `Country` varchar(64) DEFAULT NULL,
  `HomePhone` varchar(32) DEFAULT NULL,
  `MobilePhone` varchar(32) DEFAULT NULL,
  `Email` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

