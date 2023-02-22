-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 12, 2020 at 08:37 PM
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

CREATE TABLE IF NOT EXISTS `busi_games` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `game_name` varchar(255) NOT NULL,
  `game_intial_amt` int(11) NOT NULL,
  `game_active` enum('Y','N') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `busi_games`
--

INSERT INTO `busi_games` (`ID`, `game_name`, `game_intial_amt`, `game_active`) VALUES
(1, 'Game1', 20000, 'Y'),
(2, 'Game2', 40000, 'Y'),
(3, 'Game3', 45000, 'Y'),
(4, 'Game4', 50000, 'Y'),
(5, 'Game5', 45500, 'Y'),
(6, 'Game6', 20000, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `busi_players`
--

CREATE TABLE IF NOT EXISTS `busi_players` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(255) NOT NULL,
  `player_color` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `busi_players`
--

INSERT INTO `busi_players` (`ID`, `player_name`, `player_color`) VALUES
(1, 'Rinku', 'Red'),
(2, 'Yash', 'Blue'),
(3, 'Aakash', 'Yellow'),
(4, 'Reepu', '#990000'),
(5, 'Dimpy', '#660066');

-- --------------------------------------------------------

--
-- Table structure for table `busi_running_game`
--

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
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `busi_running_game`
--

INSERT INTO `busi_running_game` (`ID`, `game_id`, `player_id`, `ticket_id`, `transaction`, `transaction_amt`, `purpose`, `ticket_house_num`, `ticket_owner`, `active_row`, `tran_for_ticket`, `dice_roll`) VALUES
(1, 1, 1, 1, '', 0, 'None', 0, 'No', 'Yes', 1, 0),
(2, 1, 2, 1, '', 0, 'None', 0, 'No', 'Yes', 1, 0),
(3, 1, 3, 1, '', 0, 'None', 0, 'No', 'Yes', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `busi_tickets`
--

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
(1, 'Go', 'Home', 2500, 0, '#BC8F8F'),
(2, 'Dubai', 'Ticket', 3000, 1500, '#FFD700'),
(3, 'Com. Chest', 'Lottery', 0, 0, '#BC8F8F'),
(4, 'Railways', 'Ticket', 9500, 4750, '#000000'),
(5, 'Sri Lanka', 'Ticket', 3000, 1500, '#6495ED'),
(6, 'Income Tax', 'Tax', 200, 0, '#BC8F8F'),
(7, 'Bangladesh', 'Ticket', 2000, 1000, '#6495ED'),
(8, 'Water Works', 'Ticket', 3200, 1600, '#000000'),
(9, 'Japan', 'Ticket', 4000, 2000, '#6495ED'),
(10, 'New Zealand', 'Ticket', 4500, 2250, '#FFFF00'),
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
(28, 'Australia', 'Ticket', 5000, 2500, '#FFFF00'),
(29, 'Russia', 'Ticket', 5500, 2750, '#FFD700'),
(30, 'Chance', 'Lottery', 0, 0, '#BC8F8F'),
(31, 'Canada', 'Ticket', 4000, 2000, '#FFD700'),
(32, 'Wealth Tax', 'Tax', 200, 0, '#BC8F8F'),
(33, 'London', 'Ticket', 7000, 3500, '#000080'),
(34, 'India', 'Ticket', 6500, 3250, '#000080'),
(35, 'WaterWays', 'Ticket', 5500, 2750, '#000000'),
(36, 'USA', 'Ticket', 9000, 4500, '#000080');
