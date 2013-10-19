CREATE TABLE `members` (
  `id` char(36) NOT NULL,
  `Firstname` varchar(155) NOT NULL,
  `Lastname` varchar(155) DEFAULT NULL,
  `MembershipType` varchar(45) NOT NULL,
  `RenewalDate` datetime DEFAULT NULL,
  `City` char(2) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  `Zip` varchar(5) DEFAULT NULL,
  `Country` varchar(155) DEFAULT NULL,
  `HomePhone` varchar(45) DEFAULT NULL,
  `MobilePhone` varchar(45) DEFAULT NULL,
  `Email` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

