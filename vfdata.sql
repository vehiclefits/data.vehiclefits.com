SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `vfdata_uploads` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `uploaded` datetime NOT NULL,
  `user_id` int(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `error` int(3) NOT NULL,
  `size` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `vfdata_user` (
  `id` int(18) NOT NULL AUTO_INCREMENT,
  `website` varchar(100) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `api_token` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
