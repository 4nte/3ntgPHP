

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `entg`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTask`(IN `n_title` VARCHAR(32), IN `n_description` TEXT, IN `n_started` DATETIME)
BEGIN
DECLARE duplicate boolean;

SET duplicate = 1;
SET duplicate = !(SELECT status FROM tasks where title = n_title AND status = 0 limit 1);

if duplicate=false or duplicate is NULL  then
INSERT INTO tasks (title,description,started) VALUES (n_title,n_description,n_started);
end if;
select duplicate;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `title` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `started` datetime NOT NULL,
  `finished` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

