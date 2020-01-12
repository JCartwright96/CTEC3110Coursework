DROP TABLE IF EXISTS `user_data`;
DROP TABLE IF EXISTS `message_data`;


--
-- Table structure for table `user_data`
--
CREATE TABLE `user_data` (
  `auto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_unicode_ci,
  `password` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`auto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;--

--
-- Table structure for table `message_data`
--
CREATE TABLE `message_data` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone_number` bigint(15) NOT NULL,
  `switch_01` BOOLEAN NOT NULL,
  `switch_02` BOOLEAN NOT NULL,
  `switch_03` BOOLEAN NOT NULL,
  `switch_04` BOOLEAN NOT NULL,
  `fan` ENUM('forward', 'reverse') NOT NULL,
  `heater` int(3) NOT NULL,
  `keypad` int(1) NOT NULL,
  `receivedtime` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`message_id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Create the user account
--
GRANT SELECT, INSERT ON m2m.user_data TO 'm2m'@localhost IDENTIFIED BY 'm2m';
GRANT SELECT, INSERT ON m2m.message_data TO 'm2m'@localhost IDENTIFIED BY 'm2m';



FLUSH PRIVILEGES;
