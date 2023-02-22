-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2020 at 02:02 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `home_business`
--

-- --------------------------------------------------------

--
-- Table structure for table `busi_games`
--

DROP TABLE IF EXISTS `busi_games`;
CREATE TABLE IF NOT EXISTS `busi_games` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `game_name` varchar(255) NOT NULL,
  `game_intial_amt` int(11) NOT NULL,
  `game_active` enum('Y','N') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `busi_games`
--

INSERT INTO `busi_games` (`ID`, `game_name`, `game_intial_amt`, `game_active`) VALUES
(19, 'My New Game', 55000, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `busi_players`
--

DROP TABLE IF EXISTS `busi_players`;
CREATE TABLE IF NOT EXISTS `busi_players` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(255) NOT NULL,
  `player_color` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `busi_players`
--

INSERT INTO `busi_players` (`ID`, `player_name`, `player_color`) VALUES
(1, 'Rinku', '#92ff70'),
(2, 'Yash', '#45f7e9'),
(3, 'Aakash', '#e3f238'),
(4, 'Reepu', '#990000'),
(5, 'Dimpy', '#660066'),
(6, 'Rajveer', '#33E5FF'),
(7, 'Tipu', '#FFBE33');

-- --------------------------------------------------------

--
-- Table structure for table `busi_running_game`
--

DROP TABLE IF EXISTS `busi_running_game`;
CREATE TABLE IF NOT EXISTS `busi_running_game` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `player_id` tinyint(4) NOT NULL,
  `ticket_id` tinyint(4) NOT NULL,
  `transaction` enum('Credit','Debit','None') NOT NULL,
  `transaction_amt` int(11) NOT NULL,
  `purpose` enum('None','Buy Ticket','Rent Paid','Fine Paid','Mortgage','Prize','Got Rent') NOT NULL,
  `ticket_house_num` int(11) NOT NULL,
  `ticket_owner` enum('Yes','No') NOT NULL,
  `active_row` enum('Yes','No') NOT NULL,
  `tran_for_ticket` tinyint(4) NOT NULL,
  `dice_roll` tinyint(4) NOT NULL,
  `round_play` tinyint(4) NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=364 ;

--
-- Dumping data for table `busi_running_game`
--

INSERT INTO `busi_running_game` (`ID`, `game_id`, `player_id`, `ticket_id`, `transaction`, `transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`, `round_play`, `last_updated`) VALUES
(354, 19, 3, 20, 'Debit', 4500, 'Buy Ticket', 0, 'Yes', 'Yes', 20, 15, 2, '2020-04-26 03:27:46'),
(353, 19, 3, 5, 'Debit', 3000, 'Buy Ticket', 0, 'Yes', 'No', 5, 18, 2, '2020-04-26 03:27:46'),
(352, 19, 3, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 18, 2, '2020-04-26 03:27:46'),
(351, 19, 2, 30, 'Credit', 1000, 'Prize', 0, 'No', 'No', 30, 13, 3, '2020-04-26 03:28:40'),
(350, 19, 2, 17, 'Debit', 650, 'Rent Paid', 0, 'No', 'No', 17, 35, 3, '2020-04-26 03:28:40'),
(349, 19, 3, 23, 'Credit', 650, 'Got Rent', 0, 'Yes', 'No', 17, 0, 1, '2020-04-26 03:27:46'),
(348, 19, 2, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 35, 3, '2020-04-26 03:28:40'),
(347, 19, 2, 18, 'Debit', 350, 'Rent Paid', 0, 'No', 'No', 18, 35, 2, '2020-04-26 03:28:40'),
(346, 19, 3, 23, 'Credit', 350, 'Got Rent', 0, 'Yes', 'No', 18, 0, 0, '2020-04-26 03:27:46'),
(345, 19, 2, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 35, 2, '2020-04-26 03:28:40'),
(344, 19, 3, 23, 'Debit', 3500, 'Buy Ticket', 0, 'Yes', 'No', 23, 7, 2, '2020-04-26 03:27:46'),
(343, 19, 1, 22, 'Debit', 3000, 'Buy Ticket', 0, 'Yes', 'No', 22, 18, 2, '2020-04-26 03:28:10'),
(363, 19, 2, 11, 'Debit', 6000, 'Buy Ticket', 0, 'Yes', 'Yes', 11, 4, 5, '2020-04-26 03:28:40'),
(362, 19, 2, 7, 'Debit', 2000, 'Buy Ticket', 0, 'Yes', 'No', 7, 17, 5, '2020-04-26 03:28:40'),
(361, 19, 2, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 17, 5, '2020-04-26 03:28:40'),
(330, 19, 1, 34, 'Debit', 650, 'Rent Paid', 0, 'No', 'No', 34, 15, 0, '2020-04-26 03:28:10'),
(329, 19, 3, 34, 'Credit', 650, 'Got Rent', 0, 'Yes', 'No', 34, 0, 1, '2020-04-26 03:27:46'),
(328, 19, 2, 33, 'None', 0, 'None', 0, 'Yes', 'No', 33, 0, 0, '2020-04-26 03:28:40'),
(327, 19, 3, 34, 'Debit', 6500, 'Buy Ticket', 0, 'Yes', 'No', 34, 17, 1, '2020-04-26 03:27:46'),
(326, 19, 1, 19, 'Debit', 500, 'Fine Paid', 0, 'No', 'No', 19, 9, 0, '2020-04-26 03:28:10'),
(325, 19, 2, 33, 'Debit', 7000, 'Buy Ticket', 0, 'Yes', 'No', 33, 18, 0, '2020-04-26 03:28:40'),
(324, 19, 3, 17, 'Debit', 6500, 'Buy Ticket', 0, 'Yes', 'No', 17, 35, 1, '2020-04-26 03:27:46'),
(323, 19, 3, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 35, 1, '2020-04-26 03:27:46'),
(322, 19, 1, 10, 'Debit', 4500, 'Buy Ticket', 0, 'Yes', 'No', 10, 9, 0, '2020-04-26 03:28:10'),
(321, 19, 2, 15, 'Debit', 4000, 'Buy Ticket', 0, 'Yes', 'No', 15, 14, 0, '2020-04-26 03:28:40'),
(320, 19, 3, 18, 'Debit', 3500, 'Buy Ticket', 0, 'Yes', 'No', 18, 17, 0, '2020-04-26 03:27:46'),
(319, 19, 3, 1, 'None', 0, 'None', 0, 'No', 'No', 1, 0, 0, '2020-04-26 03:27:46'),
(282, 19, 2, 1, 'None', 0, 'None', 0, 'No', 'No', 1, 0, 0, '2020-04-26 03:28:40'),
(342, 19, 2, 19, 'Debit', 500, 'Fine Paid', 0, 'No', 'No', 19, 6, 1, '2020-04-26 03:28:40'),
(341, 19, 3, 16, 'Debit', 3500, 'Buy Ticket', 0, 'Yes', 'No', 16, 15, 2, '2020-04-26 03:27:46'),
(340, 19, 3, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 15, 2, '2020-04-26 03:27:46'),
(339, 19, 1, 4, 'Debit', 9500, 'Buy Ticket', 0, 'Yes', 'No', 4, 14, 2, '2020-04-26 03:28:10'),
(338, 19, 1, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 14, 2, '2020-04-26 03:28:10'),
(337, 19, 1, 26, 'Debit', 4500, 'Buy Ticket', 0, 'Yes', 'No', 26, 13, 1, '2020-04-26 03:28:10'),
(336, 19, 1, 13, 'Debit', 250, 'Rent Paid', 0, 'No', 'No', 13, 15, 1, '2020-04-26 03:28:10'),
(335, 19, 2, 13, 'Credit', 250, 'Got Rent', 0, 'Yes', 'No', 13, 0, 1, '2020-04-26 03:28:40'),
(334, 19, 1, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 15, 1, '2020-04-26 03:28:10'),
(333, 19, 3, 1, 'None', 0, 'None', 0, 'No', 'No', 1, 3, 2, '2020-04-26 03:27:46'),
(332, 19, 2, 13, 'Debit', 2500, 'Buy Ticket', 0, 'Yes', 'No', 13, 16, 1, '2020-04-26 03:28:40'),
(331, 19, 2, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 16, 1, '2020-04-26 03:28:40'),
(356, 19, 2, 8, 'Debit', 3200, 'Buy Ticket', 0, 'Yes', 'No', 8, 14, 4, '2020-04-26 03:28:40'),
(355, 19, 2, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 14, 4, '2020-04-26 03:28:40'),
(281, 19, 1, 1, 'None', 0, 'None', 0, 'No', 'No', 1, 0, 0, '2020-04-26 03:28:10'),
(360, 19, 2, 26, 'Debit', 450, 'Rent Paid', 0, 'No', 'No', 26, 18, 4, '2020-04-26 03:28:40'),
(359, 19, 1, 9, 'Credit', 450, 'Got Rent', 0, 'Yes', 'Yes', 26, 0, 1, '2020-04-26 03:28:10'),
(358, 19, 1, 9, 'Debit', 4000, 'Buy Ticket', 0, 'Yes', 'No', 9, 23, 3, '2020-04-26 03:28:10'),
(357, 19, 1, 1, 'Credit', 2500, 'Prize', 0, 'No', 'No', 1, 23, 3, '2020-04-26 03:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `busi_tickets`
--

DROP TABLE IF EXISTS `busi_tickets`;
CREATE TABLE IF NOT EXISTS `busi_tickets` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(255) NOT NULL,
  `ticket_type` enum('Home','Ticket','Lottery','Tax','Fine') NOT NULL,
  `ticket_price` int(11) NOT NULL,
  `ticket_mortgage` int(11) NOT NULL,
  `ticket_color` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `busi_tickets`
--

INSERT INTO `busi_tickets` (`ID`, `ticket_name`, `ticket_type`, `ticket_price`, `ticket_mortgage`, `ticket_color`) VALUES
(1, 'Home', 'Home', 2500, 0, '#BC8F8F'),
(2, 'Dubai', 'Ticket', 3000, 1500, '#FFD700'),
(3, 'Com. Chest', 'Lottery', 0, 0, '#BC8F8F'),
(4, 'Railways', 'Ticket', 9500, 4750, '#000000'),
(5, 'Sri Lanka', 'Ticket', 3000, 1500, '#6495ED'),
(6, 'Income Tax', 'Tax', 200, 0, '#BC8F8F'),
(7, 'Bangladesh', 'Ticket', 2000, 1000, '#6495ED'),
(8, 'Water Works', 'Ticket', 3200, 1600, '#000000'),
(9, 'Japan', 'Ticket', 4000, 2000, '#6495ED'),
(10, 'New Zealand', 'Ticket', 4500, 2250, '#b3b002'),
(11, 'Germany', 'Ticket', 6000, 3000, '#FFB6C1'),
(12, 'France', 'Ticket', 5000, 2500, '#FFB6C1'),
(13, 'Solar Energy', 'Ticket', 2500, 0, '#000000'),
(14, 'Chance', 'Lottery', 0, 0, '#BC8F8F'),
(15, 'Spain', 'Ticket', 4000, 2000, '#32CD32'),
(16, 'Egypt', 'Ticket', 3500, 1750, '#32CD32'),
(17, 'Roadways', 'Ticket', 6500, 3250, '#000000'),
(18, 'Switzerland', 'Ticket', 3500, 2000, '#32CD32'),
(19, 'Jail', 'Fine', 500, 0, '#BC8F8F'),
(20, 'China', 'Ticket', 4500, 2250, '#FF0000'),
(21, 'Com. Chest', 'Lottery', 0, 0, '#BC8F8F'),
(22, 'Singapore', 'Ticket', 3000, 1500, '#FF0000'),
(23, 'Hong Kong', 'Ticket', 3500, 1750, '#FF0000'),
(24, 'Italy', 'Ticket', 3000, 1500, '#6B8E23'),
(25, 'Airways', 'Ticket', 10500, 5250, '#000000'),
(26, 'South Africa', 'Ticket', 4500, 2250, '#6B8E23'),
(27, 'Pakistan', 'Ticket', 3500, 1500, '#6B8E23'),
(28, 'Australia', 'Ticket', 5000, 2500, '#b3b002'),
(29, 'Russia', 'Ticket', 5500, 2750, '#FFD700'),
(30, 'Chance', 'Lottery', 0, 0, '#BC8F8F'),
(31, 'Canada', 'Ticket', 4000, 2000, '#FFD700'),
(32, 'Wealth Tax', 'Tax', 200, 0, '#BC8F8F'),
(33, 'London', 'Ticket', 7000, 3500, '#000080'),
(34, 'India', 'Ticket', 6500, 3250, '#000080'),
(35, 'WaterWays', 'Ticket', 5500, 2750, '#000000'),
(36, 'USA', 'Ticket', 9000, 4500, '#000080');
