CREATE TABLE IF NOT EXISTS `zadaci` (
  `title` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `started` datetime NOT NULL,
  `finished` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;