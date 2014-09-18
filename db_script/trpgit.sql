-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 18, 2014 at 03:16 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trpgit`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_users`
--

CREATE TABLE `access_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `perm` longtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `access_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `ad_commission`
--

CREATE TABLE `ad_commission` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `akey` varchar(20) DEFAULT NULL,
  `date_purchase` datetime NOT NULL,
  `okey` varchar(20) NOT NULL,
  `oitem` int(11) NOT NULL,
  `commission` float NOT NULL DEFAULT '0',
  `price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `mkey` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ad_commission`
--


-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `akey` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `label` varchar(255) CHARACTER SET utf8 NOT NULL,
  `required` int(11) NOT NULL DEFAULT '0',
  `display_type` int(11) NOT NULL DEFAULT '1' COMMENT '0:Text field; 1:Select box; 2:Radio buttons; 3:Checkboxes; 4:textarea',
  `weight` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `attri_type` int(11) NOT NULL DEFAULT '1' COMMENT '1:product 2:Venue Location',
  PRIMARY KEY (`akey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attributes`
--


-- --------------------------------------------------------

--
-- Table structure for table `attrioptions`
--

CREATE TABLE `attrioptions` (
  `okey` varchar(20) CHARACTER SET utf8 NOT NULL,
  `akey` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(20,2) NOT NULL DEFAULT '0.00',
  `price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`okey`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attrioptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `banners_home`
--

CREATE TABLE `banners_home` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `url` text NOT NULL,
  `weight` int(5) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `banners_home`
--

INSERT INTO `banners_home` VALUES(9, 'b8ea940a96a8591c95441095b11d4fd5.png', '', -5, 1);
INSERT INTO `banners_home` VALUES(15, 'ce7945cd4eb08e40c97e0c886728dc5e.PNG', '', 0, 1);
INSERT INTO `banners_home` VALUES(10, '10bb439681546ff1eb428af1771bb86d.jpg', '', -1, 1);
INSERT INTO `banners_home` VALUES(11, '1f46cafe2e935d96cc5dc328945885d7.png', '', -4, 1);
INSERT INTO `banners_home` VALUES(12, '8b5556a52ec3d6980ccbac1a9dc7e586.png', '', -3, 1);
INSERT INTO `banners_home` VALUES(14, '181c1665688741200540a2c9e28f1119.PNG', '', -2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `benefits`
--

CREATE TABLE `benefits` (
  `benefits_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL COMMENT 'this app id related with bank product new applciation table',
  `applicent_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'this is the ID of the parent ERO',
  `benefits_item` varchar(100) NOT NULL,
  `benefits_price` double(10,2) NOT NULL,
  `benefits_item_desc` varchar(100) NOT NULL,
  `benefits_img_source` varchar(20) NOT NULL,
  `create_date` int(11) NOT NULL,
  `benefits_status` int(2) NOT NULL COMMENT '0=Pending,1=active, 2=canceled',
  `payment_status` int(2) NOT NULL COMMENT '1=paid, 2 = unpaid',
  `author_id` int(11) NOT NULL COMMENT 'who creating this benefits',
  PRIMARY KEY (`benefits_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `benefits`
--

INSERT INTO `benefits` VALUES(1, NULL, 35, 144, 'Lifestyle Package', 49.00, 'Discount Savings on Lifestyle Needs', 'icon-food', 1409806033, 0, 0, 144);
INSERT INTO `benefits` VALUES(4, 1, 101, 144, 'Lifestyle Package', 120.00, 'Discount Savings on Lifestyle Needs', 'icon-food', 1409812446, 1, 0, 144);
INSERT INTO `benefits` VALUES(5, NULL, 101, 144, 'Combination Package', 99.00, 'Discount Savings on Total Package', 'icon-loop', 1410110367, 2, 0, 144);
INSERT INTO `benefits` VALUES(6, 2, 102, 144, 'Lifestyle Package', 120.00, 'Discount Savings on Lifestyle Needs', 'icon-food', 1410266466, 0, 0, 144);

-- --------------------------------------------------------

--
-- Table structure for table `benefits_applicent`
--

CREATE TABLE `benefits_applicent` (
  `ben_id` int(11) NOT NULL AUTO_INCREMENT,
  `benefits_id` int(11) NOT NULL,
  `applicent_id` int(11) NOT NULL COMMENT 'applicent id',
  PRIMARY KEY (`ben_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `benefits_applicent`
--

INSERT INTO `benefits_applicent` VALUES(1, 1, 69);

-- --------------------------------------------------------

--
-- Table structure for table `business_resource_documents`
--

CREATE TABLE `business_resource_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `business_resource_documents`
--

INSERT INTO `business_resource_documents` VALUES(1, '', 'Program - Integration API Specifications_v1.42.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `business_type`
--

CREATE TABLE `business_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bkey` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bkey` (`bkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `business_type`
--

INSERT INTO `business_type` VALUES(1, 'IsCJb_ysxhlW7Ytbva8l', 'DBA', 0, 1);
INSERT INTO `business_type` VALUES(2, 'tdC89SYMyPGtsjKgoUw_', 'Corporation', 1, 1);
INSERT INTO `business_type` VALUES(3, '6iVJr1GUP951GwEgQkCx', 'LLC', -4, 1);
INSERT INTO `business_type` VALUES(4, 'zhLFQPQglLKH4502_UTR', 'Self', -5, -1);
INSERT INTO `business_type` VALUES(5, 'Njr2d9rlHYvnZ2Kihlj3', 'C-Corp', 0, -1);
INSERT INTO `business_type` VALUES(7, 'dwgbxlHpEZS3h6bD8ebg', 'Incorporate', 0, 1);
INSERT INTO `business_type` VALUES(8, 'b6aHUMEPqy11Gigfls2o', 'Monkey', 47, -1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_key` varchar(20) DEFAULT NULL,
  `fid` int(11) NOT NULL DEFAULT '0',
  `cat_name` varchar(255) DEFAULT NULL,
  `description` text,
  `weight` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `status_display` int(11) NOT NULL DEFAULT '0',
  `generictaxcode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_key` (`cat_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` VALUES(1, '6Y5JMpz380l4hWu2gFU6', 0, 'Skin Care', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(2, 'Aw12I7NIupLfsBOlf8GM', 0, 'Shampoo', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(3, 'Xrj1ncM_CBAKVb6640hc', 0, 'Medicated Rubbing Cream', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(4, '8p0gzawlsFbFHNF_e6JZ', 1, 'Makeup Remover &amp; Cleanser', '', 0, 1, 1, '');
INSERT INTO `categories` VALUES(5, 'i_JFW30htN0sDgq1JyrP', 0, 'Water Filtration Systems', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(6, 'EsNOyVmIh1fhneNTB0rG', 5, 'CounterTop Water Filters', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(7, 'brfBPoBnTmgJtzljSPp8', 5, 'UnderSink Water Filters', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(8, 'NJrryWJbA8WMF4DKbQPO', 5, 'Shower Filters', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(9, 'XNJeHXMdhfQpSyFYKNqF', 5, 'Replacement Cartridges', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(18, '4Bs07ESXg0MAbdFFmVxM', 0, 'Custom Furniture', 'Custom Furniture', 0, -1, 0, NULL);
INSERT INTO `categories` VALUES(10, 'Usr6QccjJlpqHzjutdsc', 0, 'Solar', 'Solar', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(11, 'DLuogdGy_mBcv_oKpV_z', 10, 'Solar Lantern', 'Solar Lantern', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(12, 'VgENMEpKMRfvzkiQdqQy', 0, 'Toothpaste', 'Toothpaste', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(13, 'qYdKsobAXUWZirPaORZU', 0, 'Electronics', 'Electronics', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(14, 'XyiFvgBtEkbOT3e6Uj0z', 13, 'Phone Charger', 'Phone Charger', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(15, 'glxWkr52CpdJ5xVWkl0c', 1, 'Solvarome-Bio Oil', 'Solvarome skin care', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(16, 'y1ggxmuhClIjiXv1L4y2', 1, 'Solvarome-Bio Bath Gel', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(17, 'nsUuEjeKuCwETqvlGzVB', 1, 'Solvarome-Bio Cream', '', 0, 1, 0, NULL);
INSERT INTO `categories` VALUES(19, 'y2PSRgTHqPQIHFOnSI9N', 0, 'Charities', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(20, 'Dp4tvneJ2Z6ZVt906Och', 1, 'Olive Squalane', '', 0, 1, 1, NULL);
INSERT INTO `categories` VALUES(21, 'JungeHVsXQmmhy4eegv4', 1, 'Olive Oil Soap', 'Olive Oil Soap', 0, 1, 1, 'PC030113');
INSERT INTO `categories` VALUES(22, 'JNp7Ow5IODLvOSe2ApcI', 13, 'Bluetooth Speaker', 'Bluetooth Speaker', 0, 1, 0, 'PC030113');
INSERT INTO `categories` VALUES(23, 'hmpSHDjJ9oN6CjgPv3rX', 0, 'Foods', 'Foods', 0, 1, 0, 'PC030113');
INSERT INTO `categories` VALUES(24, 'N33nwh5s5AaUZsfHMZBX', 0, 'E-Cigarette', '', 0, 1, 1, 'D9999999');
INSERT INTO `categories` VALUES(25, 'cWA2jzC4wOUDBC_UJMTP', 0, 'Coffee', 'Coffee', 0, 1, 0, 'PC030113');

-- --------------------------------------------------------

--
-- Table structure for table `efins`
--

CREATE TABLE `efins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `efin` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `efins`
--

INSERT INTO `efins` VALUES(11, '65643\r');
INSERT INTO `efins` VALUES(13, '76865\r');
INSERT INTO `efins` VALUES(14, '78545\r');
INSERT INTO `efins` VALUES(15, '89876\r');
INSERT INTO `efins` VALUES(16, '78546\r');
INSERT INTO `efins` VALUES(17, '12545\r');
INSERT INTO `efins` VALUES(18, '88888\r');

-- --------------------------------------------------------

--
-- Table structure for table `efin_pefin`
--

CREATE TABLE `efin_pefin` (
  `efin_pefin_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT 'this is the Uid for who adding parent EFIN id',
  `efin` int(10) NOT NULL,
  `pefin` int(10) NOT NULL,
  `service_buraue` int(10) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1=Approved,2=rejected',
  `is_view` int(1) NOT NULL COMMENT '1=it''s not viewed by perent efin owner yet, 2= viewed ',
  `add_date` int(10) NOT NULL,
  `approved_date` int(10) NOT NULL,
  `reject_date` int(10) NOT NULL,
  PRIMARY KEY (`efin_pefin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `efin_pefin`
--

INSERT INTO `efin_pefin` VALUES(1, 147, 234567, 888888, 0, 1, 1, 1397173405, 1404945476, 0);

-- --------------------------------------------------------

--
-- Table structure for table `eins`
--

CREATE TABLE `eins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ein` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `eins`
--


-- --------------------------------------------------------

--
-- Table structure for table `ero`
--

CREATE TABLE `ero` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT '1',
  `ptin` varchar(100) DEFAULT NULL,
  `legal_business_name` varchar(255) DEFAULT NULL,
  `legal_business_fname` varchar(255) DEFAULT NULL,
  `legal_business_lname` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `status_ero` int(11) NOT NULL DEFAULT '0' COMMENT '0=training, 1= active, 2=inactive, ',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `legal_business_id` (`ptin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=482 ;

--
-- Dumping data for table `ero`
--

INSERT INTO `ero` VALUES(475, 168, 169, '231231', 'anifmer', 'anif', 'momin', 'test@test.com', 0);
INSERT INTO `ero` VALUES(474, 160, 162, '12345', 'anif', 'anif', 'momin', 'anif@anif.com', 0);
INSERT INTO `ero` VALUES(473, 160, 161, '2', '', '', '', '', 0);
INSERT INTO `ero` VALUES(470, 149, 150, '145632', 'lonelyboy', 'ashiq', 'issingle', 'ashiqissingle@gmail.com', 0);
INSERT INTO `ero` VALUES(471, 154, 155, '232323', 'ajay', 'ajay', 'ajay', 'ajay', 0);
INSERT INTO `ero` VALUES(472, 154, 156, 'ajay', 'ajay', 'ajay', 'ajay', 'ajay', 0);
INSERT INTO `ero` VALUES(476, 168, 170, '123456', 'anifm1', 'anif', 'momin', 'test@test.com', 0);
INSERT INTO `ero` VALUES(477, 144, 171, '23423', 'test4', 'test4', 'test', 'test4@test.com', 0);
INSERT INTO `ero` VALUES(478, 144, 173, '12345566', 'dinaP', 'Dina', 'Paiz', 'dina23423@yahoo.com', 0);
INSERT INTO `ero` VALUES(479, 176, 177, '23123', 'anifm34', 'anif', 'momin', 'anifmomin@gmail.com', 0);
INSERT INTO `ero` VALUES(480, 145, 183, '565657', 'shuvro4', 'shuvro2', 'ah', 'shuvro2@shuvro.com', 0);
INSERT INTO `ero` VALUES(481, 154, 185, '', '', '', '', '', -1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ekey` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `content` longtext,
  `uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ekey` (`ekey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `events`
--


-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `exam_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `Ques1` text NOT NULL,
  `Ques1Ans` text NOT NULL,
  `Ques2` text NOT NULL,
  `Ques2Ans` text NOT NULL,
  `Ques3` text NOT NULL,
  `Ques3Ans` text NOT NULL,
  `Ques4` text NOT NULL,
  `Ques4Ans` text NOT NULL,
  `Ques5` text NOT NULL,
  `Ques5Ans` text NOT NULL,
  `resultParcent` int(10) NOT NULL,
  `rightAns` int(10) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `exam_results`
--

INSERT INTO `exam_results` VALUES(1, 133, 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 40, 2, '2014-03-26', '');
INSERT INTO `exam_results` VALUES(2, 133, 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 100, 5, '2014-03-27', '');
INSERT INTO `exam_results` VALUES(3, 138, 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 20, 1, '2014-04-01', '');
INSERT INTO `exam_results` VALUES(4, 138, 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 60, 3, '2014-04-01', '');
INSERT INTO `exam_results` VALUES(5, 140, 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 60, 3, '2014-04-09', '');
INSERT INTO `exam_results` VALUES(6, 144, 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 0, 0, '2014-04-15', '');
INSERT INTO `exam_results` VALUES(7, 144, 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 0, 0, '2014-04-15', '');
INSERT INTO `exam_results` VALUES(8, 144, 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 'Is this interesting?', '', 0, 0, '2014-04-15', '');
INSERT INTO `exam_results` VALUES(9, 144, 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 80, 4, '2014-04-15', '');
INSERT INTO `exam_results` VALUES(10, 144, 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 100, 5, '2014-04-15', '');
INSERT INTO `exam_results` VALUES(11, 146, 'Is this interesting?', 'Yes', 'Is this interesting?', 'No', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 'Is this interesting?', 'Yes', 40, 2, '2014-04-19', '');
INSERT INTO `exam_results` VALUES(12, 172, 'Is this interesting?', '1', 'Is this interesting?', '1', 'Is this interesting?', '1', 'Is this interesting?', '1', 'Is this interesting?', '1', 100, 5, '2014-05-22', '');

-- --------------------------------------------------------

--
-- Table structure for table `general_setting`
--

CREATE TABLE `general_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minimum_purchased` float NOT NULL DEFAULT '0',
  `limit_time_purchase` int(11) NOT NULL DEFAULT '1',
  `units_purchase` int(11) NOT NULL DEFAULT '1',
  `number_of_level` int(11) NOT NULL DEFAULT '1',
  `direct_sponsor` int(11) NOT NULL DEFAULT '1',
  `to_be_active` int(11) NOT NULL DEFAULT '0',
  `units_active` int(11) NOT NULL DEFAULT '1',
  `date_apply` varchar(15) NOT NULL DEFAULT '0',
  `minimum_payment` float NOT NULL DEFAULT '0',
  `limit_time_payment` int(11) NOT NULL DEFAULT '0',
  `units_payment` int(11) NOT NULL DEFAULT '0',
  `time_purchase_actived` int(11) NOT NULL DEFAULT '1',
  `units_time_purchase` int(11) NOT NULL DEFAULT '1',
  `date_update` datetime NOT NULL,
  `date_holding_account` int(11) NOT NULL DEFAULT '20',
  `vendor_transfer_date` int(11) NOT NULL DEFAULT '20',
  `activate_ship` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `general_setting`
--

INSERT INTO `general_setting` VALUES(1, 0.5, 2, 1, 7, 5, 2, 1, '2013-07-01', 50, 1, 30, 2, 1, '2013-08-25 23:08:22', 20, 20, 0);
INSERT INTO `general_setting` VALUES(2, 0.5, 7, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-06 04:31:43', 20, 20, 0);
INSERT INTO `general_setting` VALUES(3, 0.5, 1, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-06 04:35:40', 20, 20, 0);
INSERT INTO `general_setting` VALUES(4, 0.5, 5, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-06 04:40:21', 20, 20, 0);
INSERT INTO `general_setting` VALUES(5, 0.5, 3, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-06 04:41:18', 20, 20, 0);
INSERT INTO `general_setting` VALUES(6, 0.5, 7, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-06 04:45:58', 20, 20, 0);
INSERT INTO `general_setting` VALUES(7, 0.5, 2, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-09 02:09:34', 20, 20, 0);
INSERT INTO `general_setting` VALUES(8, 0.5, 2, 1, 7, 5, 2, 1, '2013-09-01', 50, 1, 30, 2, 1, '2013-12-09 02:10:51', 20, 20, 0);
INSERT INTO `general_setting` VALUES(9, 0.5, 1, 30, 7, 5, 2, 1, '2013-09-01', 10, 1, 30, 2, 1, '2014-01-07 02:12:52', 20, 20, 0);
INSERT INTO `general_setting` VALUES(10, 0.5, 1, 1, 7, 5, 1, 1, '2013-09-01', 10, 1, 1, 7, 1, '2014-01-13 19:38:02', 20, 20, 0);
INSERT INTO `general_setting` VALUES(11, 0.5, 1, 1, 7, 5, 1, 1, '2013-09-01', 5, 1, 1, 7, 1, '2014-01-14 21:57:28', 20, 20, 0);
INSERT INTO `general_setting` VALUES(12, 0.5, 4, 1, 7, 5, 2, 1, '2013-09-01', 5, 4, 1, 7, 1, '2014-01-15 00:40:03', 20, 20, 0);
INSERT INTO `general_setting` VALUES(13, 0.5, 7, 1, 7, 5, 2, 1, '2013-09-01', 5, 7, 1, 7, 1, '2014-01-15 14:34:40', 20, 20, 0);
INSERT INTO `general_setting` VALUES(14, 0.5, 4, 1, 7, 5, 2, 1, '2013-09-01', 5, 4, 1, 7, 1, '2014-01-15 14:35:04', 20, 20, 0);
INSERT INTO `general_setting` VALUES(15, 0.5, 4, 1, 7, 5, 2, 1, '2014-01-15', 5, 4, 1, 7, 1, '2014-01-15 14:47:44', 20, 20, 0);
INSERT INTO `general_setting` VALUES(16, 0.5, 7, 1, 7, 5, 2, 1, '2014-01-15', 5, 7, 1, 7, 1, '2014-01-15 14:49:29', 20, 20, 0);
INSERT INTO `general_setting` VALUES(17, 0.5, 4, 1, 7, 5, 2, 1, '2014-01-15', 5, 4, 1, 7, 1, '2014-01-15 14:49:59', 20, 20, 3);
INSERT INTO `general_setting` VALUES(18, 0.5, 4, 1, 7, 5, 2, 1, '2014-01-15', 5, 4, 1, 7, 1, '2014-01-15 14:50:11', 20, 20, 0);
INSERT INTO `general_setting` VALUES(19, 0.5, 4, 1, 7, 5, 2, 1, '2014-01-15', 5, 4, 1, 7, 1, '2014-01-15 16:04:12', 20, 20, 3);
INSERT INTO `general_setting` VALUES(20, 0.5, 2, 1, 7, 5, 2, 1, '2014-01-15', 5, 2, 1, 7, 1, '2014-01-15 19:50:24', 20, 20, 0);
INSERT INTO `general_setting` VALUES(21, 0.5, 1, 1, 7, 5, 2, 1, '2014-01-15', 5, 1, 1, 7, 1, '2014-01-15 20:27:06', 20, 20, 0);
INSERT INTO `general_setting` VALUES(22, 0.5, 1, 1, 7, 5, 2, 1, '2014-01-15', 5, 1, 1, 7, 1, '2014-01-15 20:38:48', 20, 1, 0);
INSERT INTO `general_setting` VALUES(23, 0.5, 1, 1, 7, 5, 7, 1, '2014-01-21', 5, 1, 1, 7, 1, '2014-01-21 13:31:32', 20, 1, 0);
INSERT INTO `general_setting` VALUES(24, 0.5, 2, 1, 7, 5, 7, 1, '2014-01-29', 5, 1, 1, 7, 1, '2014-01-29 18:58:24', 20, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `holding_account`
--

CREATE TABLE `holding_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date_transfer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `holding_account`
--


-- --------------------------------------------------------

--
-- Table structure for table `insurance`
--

CREATE TABLE `insurance` (
  `insurance_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL COMMENT 'this app id related with bank product new applciation table',
  `applicent_id` int(11) NOT NULL COMMENT 'this is the primary applicent id',
  `insurance_item` varchar(100) NOT NULL,
  `insurance_img_source` varchar(30) NOT NULL,
  `create_date` int(11) NOT NULL,
  `insurance_status` int(2) NOT NULL COMMENT '0=Pending,1=active, 2=canceled',
  `payment_status` int(2) NOT NULL COMMENT '1=paid, 2 = unpaid',
  `author_id` int(11) NOT NULL COMMENT 'who creating this insurance',
  `uid` int(11) NOT NULL COMMENT 'this is the ID of the parent ERO',
  PRIMARY KEY (`insurance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `insurance`
--

INSERT INTO `insurance` VALUES(1, NULL, 67, 'Home Insurance', 'icon-home3', 1409805235, 1, 0, 144, 144);
INSERT INTO `insurance` VALUES(2, NULL, 67, 'Property & Casualty', 'icon-fire', 1409805235, 0, 0, 144, 144);
INSERT INTO `insurance` VALUES(3, 1, 101, 'Group Health', 'icon-health', 1409812446, 0, 0, 144, 144);
INSERT INTO `insurance` VALUES(4, 1, 101, 'Home Insurance', 'icon-home3', 1409812446, 2, 0, 144, 144);
INSERT INTO `insurance` VALUES(5, NULL, 101, 'Home Insurance', 'icon-home3', 1410101619, 0, 0, 144, 144);

-- --------------------------------------------------------

--
-- Table structure for table `insurance_application_additional_info`
--

CREATE TABLE `insurance_application_additional_info` (
  `insurance_additional_id` int(11) NOT NULL AUTO_INCREMENT,
  `insurance_id` int(11) NOT NULL COMMENT 'this is the referance id of application',
  `aplicent_id` int(11) NOT NULL COMMENT 'this is the id for selected applicent for insurance',
  `insurance_title` varchar(30) NOT NULL,
  `family_coverage_date` date DEFAULT NULL,
  `family_gender` varchar(8) DEFAULT NULL,
  `family_tobacco_use` varchar(3) DEFAULT NULL,
  `company_name_grouphealth` varchar(100) NOT NULL,
  `industry_grouphealth` varchar(100) NOT NULL,
  `company_address_grouphealth` text NOT NULL,
  `state_grouphealth` varchar(10) NOT NULL,
  `zip_grouphealth` varchar(10) NOT NULL,
  `requested_line_grouphealth` text NOT NULL,
  `current_carrier_grouphealth` text NOT NULL,
  `renewal_date_grouphealth` date DEFAULT NULL,
  `effective_date_grouphealth` date DEFAULT NULL,
  `gender_life` varchar(10) NOT NULL,
  `height_life` varchar(10) NOT NULL,
  `width_life` varchar(10) NOT NULL,
  `tobacco_use_life` varchar(10) NOT NULL,
  `marital_status_auto` varchar(10) DEFAULT NULL,
  `gender_auto` varchar(10) DEFAULT NULL,
  `relation_auto` varchar(100) DEFAULT NULL,
  `year_auto` varchar(20) DEFAULT NULL,
  `make_auto` varchar(30) DEFAULT NULL,
  `model_auto` varchar(30) DEFAULT NULL,
  `coverage_auto` varchar(20) DEFAULT NULL,
  `coverage_date_home` date DEFAULT NULL,
  `revenue_property` double(10,2) NOT NULL,
  `past_claims_property` varchar(5) NOT NULL,
  `insurance_type_property` text NOT NULL,
  PRIMARY KEY (`insurance_additional_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `insurance_application_additional_info`
--

INSERT INTO `insurance_application_additional_info` VALUES(1, 1, 67, 'Home Insurance', NULL, NULL, NULL, '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2014-09-20', 0.00, '', '');
INSERT INTO `insurance_application_additional_info` VALUES(2, 2, 67, 'Property & Casualty', NULL, NULL, NULL, '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2312.00, 'Yes', 'General Liability,Professional Liability,Vision');
INSERT INTO `insurance_application_additional_info` VALUES(3, 3, 101, 'Group Health', NULL, NULL, NULL, 'ff', 'ff', 'ff', 'ff', '3333', 'Dental,Vision', '333333', '2014-09-06', '2014-09-09', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, '', '');
INSERT INTO `insurance_application_additional_info` VALUES(4, 4, 101, 'Home Insurance', NULL, NULL, NULL, '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2014-09-27', 0.00, '', '');
INSERT INTO `insurance_application_additional_info` VALUES(5, 5, 101, 'Home Insurance', NULL, NULL, NULL, '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2014-09-26', 0.00, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_applicent`
--

CREATE TABLE `insurance_applicent` (
  `in_id` int(11) NOT NULL AUTO_INCREMENT,
  `applicent_id` int(11) NOT NULL COMMENT 'applicent id',
  `insurance_id` int(11) NOT NULL,
  PRIMARY KEY (`in_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `insurance_applicent`
--

INSERT INTO `insurance_applicent` VALUES(1, 87, 1);
INSERT INTO `insurance_applicent` VALUES(2, 87, 2);

-- --------------------------------------------------------

--
-- Table structure for table `legal_business_contact`
--

CREATE TABLE `legal_business_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linkID` int(11) NOT NULL,
  `contactType` int(11) NOT NULL COMMENT '1:NailSalon Venue 2:Manufacturer 3:Venue Location 4:charities 5:content provider 6:representatives 7:advertisers 8:Acquisition Agent',
  `gender` varchar(10) NOT NULL,
  `title` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `owned_by` varchar(50) DEFAULT NULL,
  `last_update_date` int(11) DEFAULT NULL,
  `note` text,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `legal_business_contact`
--

INSERT INTO `legal_business_contact` VALUES(1, 1, 2, 'Mr', 1, 'Mitchell', 'Phan', '', 'mphan@nailsalontv.com', '', NULL, 1390276487, '', 1);
INSERT INTO `legal_business_contact` VALUES(2, 1, 4, 'Mr', 2, 'Rose', 'Phan', '', 'mphan12345@gmail.com', '', NULL, 1389845411, '', 1);
INSERT INTO `legal_business_contact` VALUES(3, 2, 4, 'Mr', 1, 'An', 'valery', '', 'mphan12345@gmail.com', '', NULL, 1389968342, '', 1);
INSERT INTO `legal_business_contact` VALUES(4, 3, 4, 'Mr', 1, 'kim', '', '', 'helenngn7@gmail.com', '', NULL, 1389968138, '', 1);
INSERT INTO `legal_business_contact` VALUES(5, 4, 4, 'Mr', 0, 'levj123', '', '', 'helenngn7@gmail.com', '', NULL, 1389969683, '', 1);
INSERT INTO `legal_business_contact` VALUES(6, 2, 2, 'Mr', 0, 'Mitchell', 'Phan', '', 'mphan12345@gmail.com', '', NULL, 1391656476, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mailconfig`
--

CREATE TABLE `mailconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `titlemail` text,
  `contentmail` text,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `mailconfig`
--

INSERT INTO `mailconfig` VALUES(1, 'Welcome Email ', 'Bella Vie Network Welcome Email', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0px; \\">Dear&nbsp; </span>!full_name<span style=\\"letter-spacing: 0px; \\">,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Congratulations on becoming a Bella Vie Network (BVN), Independent Representative (IR)</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Rep ID # is:&nbsp; </span>!RepId</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Username Login is:</span> !username</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Password is: </span>!password</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">For more information and answers to common questions please:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Login to <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> today to learn more about BVN! Access <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> for information regarding our products, service offerings, announcements, promotions, training opportunities, and much more. You can access the Business Resources Center, after logging in to review the Code of Ethics, Policies &amp; Procedures. You must review these documents as part of the requirements of becoming an IR (Independent Representative), and we encourage you to reference it frequently to help you build your business on a solid foundation.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Email Us:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; color: rgb(11, 34, 162); \\">\\n	<span style=\\"letter-spacing: 0.0px; color: #000000\\">Representative Support: <a href=\\"mailto:Support@BMNIR.com\\"><span style=\\"letter-spacing: 0px; \\">Support@BellaVieNetwork.com</span></a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Ask your Sponsor for a more personal, detailed response. BVN encourages you to speak with your BVN Sponsor, someone in you upline or a local BVN Independent Representative for the most thorough answers to questions about BVN. We encourage you to save or print a copy of this email for your records.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network, LLC</span></p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(2, 'Additional Downline Confirmation', 'Additional Downline Confirmation', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0px; \\">Congratulations </span>!full_name<span style=\\"letter-spacing: 0px; \\">! You have just added a downline member!&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">...Do you feel that? That&rsquo;s the feeling of making a difference in someones life. Every time you make a purchase and grow your downline, you make a small difference in someones life. A difference that our world NEEDS these days!&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">People have slowly strayed from the thought and principles of helping others. Even a simple gesture of opening a door for someone, has slowly become scarce. So think of this as opening a virtual door for someone to a brighter and more fulfilling future.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">This is just a small taste of how your actions towards yourself can indirectly help someone else. Keep up the good work and help build your downline!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !username, !full_name, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(3, 'Reminder email for Monthly Purchase ', 'BVN Commission Eligibility Reminder', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Hi </span>!full_name<span style=\\"letter-spacing: 0.0px\\">,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Just a friendly reminder from the Bella Vie Network to make a purchase before the conclusion of this months cycle.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><b>Commission Eligibility:</b></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">To be eligible for this months commission, you must make at least one purchase or donation of your choice. Please check out our new and hot items for the month! We have many new services as well that are available.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Log in to <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> to view our current and up coming products!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo; </i></span><span style=\\"letter-spacing: 0.0px\\">the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life</i></span><span style=\\"letter-spacing: 0.0px\\"> we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(4, 'Email Reminder before Termination ', 'Membership Termination Reminder', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Hi </span>!full_name<span style=\\"letter-spacing: 0.0px\\">,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">This is just a friendly reminder letting you know that after the end of this month&rsquo;s cycle, your position in the Bella Vie Network will be terminated. Every one of our members are apart of a greater and life changing movement that we hope to share with our world.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We encourage you as our valued member to not forget the kind of importance you are to our movement.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Please keep in mind that there is still an opportunity to turn things around and not loose your position in our rapidly growing Bella Vie Community. You can either make a purchase of your choice, or a donation of some sort to a charity of your choice. This will also allow you to receive your commission for this month and hold your position in the Bella Vie Network.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">For more information and answers to common questions please:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Login to <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> today to learn more about BVN! Access <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> for information regarding our products, service offerings, announcements, promotions, training opportunities, and much more. You can access the Business Resources Center, after logging in to review the Code of Ethics, Policies &amp; Procedures. You must review these documents as part of the requirements of becoming an IR (Independent Representative), and we encourage you to reference it frequently to help you build your business on a solid foundation.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Email Us:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; color: rgb(11, 34, 162); \\">\\n	<span style=\\"letter-spacing: 0.0px; color: #000000\\">Representative Support: <a href=\\"mailto:Support@BMNIR.com\\"><span style=\\"letter-spacing: 0px; \\">Support@BellaVieNetwork.com</span></a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Ask your Sponsor for a more personal, detailed response. BVN encourages you to speak with your BVN Sponsor, someone in you upline or a local BVN Independent Representative for the most thorough answers to questions about BVN. We encourage you to save or print a copy of this email for your records.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(5, 'Termination Confirmation Email', 'Termination Confirmation Email', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Hi </span>!full_name,</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We are sad to notify you that your membership and position in the Bella Vie Network has been terminated and lost.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We wish you the best and hope to see you in future!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">If you have any question then feel free to email our support team at: <a href=\\"mailto:Support@BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">Support@BellaVieNetwork.com</span></a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(6, 'Forgot Password ', 'Password Request Email', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0px; \\">Dear </span>!full_name,</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We understand you&rsquo;d like to change your password. Just </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\">Click Here&nbsp;</span><span style=\\"letter-spacing: 0.0px\\"> and follow the prompts. And don&rsquo;t forget your password is case sensitive.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">You didn&rsquo;t ask to change your password? Then just ignore this email. If you have questions, we&rsquo;re always happy to help. Just email us at <a href=\\"mailto:Support@BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">Support@BellaVieNetwork.com</span></a> anytime.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>', 'Customize e-mail messages sent to users who request a new password. Available variables are: !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(7, 'Member Slots Available', 'Member Slots Available', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Hi _____,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We noticed that you still have available slots in your downline. We encourage you to speak with your leaders/sponsors and your upline for any question and suggestions. You can log into your account and visit your &ldquo;Sponsor Tree&rdquo; for more information.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Make sure to go and spread the word about this amazing movement! We are looking forward to seeing each and every one of our members succeeding and enjoying their Bella Vie (Beautiful Life)</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(8, 'New Product Announcements ', 'New Product Announcements', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Hi _____!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We have some great new products and services that have just joined our Bella Vie Network! Be sure to visit the link bellow to view our exclusive deals now!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; color: rgb(11, 34, 162); \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><a href=\\"http://www.BellaVieNetwork.com/Shop\\">www.BellaVieNetwork.com/Shop</a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Everyday we are searching for new products that we hope to be of interest to our members. Products that will entice our members to visit our Network first BEFORE searching anywhere else.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Stop by </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\">here</span><span style=\\"letter-spacing: 0.0px\\"> regularly to check out our great deals and services that we hope to provide for you!&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network</span></p>\\n<div>\\n	&nbsp;</div>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(9, 'Welcome Email for Manufacturer ', 'Vendor Welcome Email', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Dear&nbsp;</span><span style=\\"background-color: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; line-height: 15px;\\">!full_name ,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Congratulations on becoming a Bella Vie Network (BVN), Vendor!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We are excited that you have joined our Bella Vie Network. If you are not already familiar with our Brand then here is a simple brief description: &ldquo;Bella Vie&rdquo; is French, for </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\">Beautiful Life,</span><span style=\\"letter-spacing: 0.0px\\"> and our goal as a company is to instill and spread a &ldquo;Beautiful Life&rdquo; amongst everyone in this Amazing World. To do so, we need the help of each every one of you.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">As a vendor associated with our company, we will do our best to benefit all parties involved including the members of our Network.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">With this said, we hope for a very successful and long relationship between our companies.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Vendor ID # is: !RepId</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Username Login is: </span>!username</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Password is: </span>!password</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">For more information and answers to common questions please:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Login to <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> today to learn more about BVN, or&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Email Us: Representative Support: <a href=\\"mailto:Support@BMNIR.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">Support@BellaVieNetwork.com</span></a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network, LLC</span></p>\\n<div>\\n	&nbsp;</div>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(10, 'Reminder for Low Inventory', 'Low Inventory Reminder', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Dear __________,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We have some GREAT news for you...We are low on inventory for your product(s)/voucher(s)/Service(s)! So please login and update your inventory so we can continue to provide for our customers!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network, LLC</span></p>', 'Available variables are: !items_list');
INSERT INTO `mailconfig` VALUES(11, 'Cancelled Orders', '', '', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');
INSERT INTO `mailconfig` VALUES(12, 'Welcome Email for Charity', 'Charity Welcome Email', '<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Dear&nbsp;</span><span style=\\"background-color: rgb(255, 255, 255); color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; line-height: 15px;\\">!full_name ,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Congratulations on becoming a Bella Vie Network (BVN), Charity!</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">We are excited that you have joined our Bella Vie Network. If you are not already familiar with our Brand then here is a simple brief description: &ldquo;Bella Vie&rdquo; is French, for </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\">Beautiful Life,</span><span style=\\"letter-spacing: 0.0px\\"> and our goal as a company is to instill and spread a &ldquo;Beautiful Life&rdquo; amongst everyone in this Amazing World. To do so, we need the help of each every one of you.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">As a vendor associated with our company, we will do our best to benefit all parties involved including the members of our Network.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">With this said, we hope for a very successful and long relationship between our companies.&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Charity ID # is: !RepId</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Username Login is: </span>!username</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Your BVN Password is: </span>!password</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">For more information and answers to common questions please:&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Login to <a href=\\"http://www.BellaVieNetwork.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">www.BellaVieNetwork.com</span></a> today to learn more about BVN, or&nbsp;</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Email Us: Representative Support: <a href=\\"mailto:Support@BMNIR.com\\"><span style=\\"letter-spacing: 0px; color: rgb(11, 34, 162); \\">Support@BellaVieNetwork.com</span></a></span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life </i></span><span style=\\"letter-spacing: 0.0px\\">we wait to share with you all.</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Warmest Regards,</span></p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; min-height: 14px; \\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px; font-size: 12px; font-family: Helvetica; \\">\\n	<span style=\\"letter-spacing: 0.0px\\">Bella Vie Network, LLC</span></p>\\n<div>\\n	&nbsp;</div>', 'Customize welcome e-mail messages sent to new members upon registering, when no administrator approval is required. Available variables are: !RepId, !full_name, !username, !site, !password, !uri, !uri_brief, !mailto, !date, !login_uri, !edit_uri, !login_url, !signature.');

-- --------------------------------------------------------

--
-- Table structure for table `mail_roles`
--

CREATE TABLE `mail_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(20) NOT NULL,
  `rid` int(11) NOT NULL,
  `role_type` varchar(50) NOT NULL DEFAULT 'all',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mkey` (`mkey`,`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mail_roles`
--


-- --------------------------------------------------------

--
-- Table structure for table `mail_users`
--

CREATE TABLE `mail_users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `ukey` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mail_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `author` int(11) NOT NULL DEFAULT '1',
  `legal_business_id` varchar(20) DEFAULT NULL,
  `legal_business_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `country` varchar(10) DEFAULT 'US',
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `business_type` int(11) DEFAULT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `contract_start_date` int(11) DEFAULT NULL,
  `contract_end_date` int(11) DEFAULT NULL,
  `contract_file` varchar(100) DEFAULT NULL,
  `payment_type` varchar(100) DEFAULT NULL,
  `beneficiary_bank` varchar(255) DEFAULT NULL,
  `beneficiary_name` varchar(255) DEFAULT NULL,
  `account_NO` varchar(255) DEFAULT NULL,
  `SWIFT_CODE` varchar(255) DEFAULT NULL,
  `data_xml` longtext,
  `balance_pending_day` int(11) NOT NULL DEFAULT '15',
  `date_of_birth` int(11) NOT NULL,
  `payment_check` int(11) NOT NULL DEFAULT '0',
  `present_account` varchar(50) NOT NULL,
  PRIMARY KEY (`mid`),
  UNIQUE KEY `mid` (`mid`,`uid`),
  UNIQUE KEY `legal_business_id` (`legal_business_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` VALUES(1, 2, 1, '14673943', 'Bobbie LLC', '17 Morgan st', 'Irvine', 'CA', '92618', 'US', '(949) 123-4567', '', '', 3, '12-12345678', 1388559600, 1443769200, '', '', '', '', '', '', 'a:10:{s:7:"usps_id";s:0:"";s:16:"origin_firstname";s:0:"";s:15:"origin_lastname";s:0:"";s:14:"origin_address";s:0:"";s:11:"origin_city";s:0:"";s:14:"origin_zipcode";s:0:"";s:12:"origin_phone";s:0:"";s:11:"origin_mail";s:0:"";s:14:"origin_country";s:0:"";s:12:"origin_state";s:0:"";}', 15, 0, 0, '');
INSERT INTO `manufacturers` VALUES(2, 38, 1, '38967886', 'Bella Vie Network', '36 Technology Drive', 'undefined', 'CA', '92618', 'US', '', '', '', 3, '', 1391238000, 1435647600, '', '', '', '', '', '', NULL, 15, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `mass_mail`
--

CREATE TABLE `mass_mail` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mail_from` varchar(255) NOT NULL,
  `mail_subject` varchar(255) DEFAULT NULL,
  `mail_body` longtext,
  `created` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mkey` (`mkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mass_mail`
--


-- --------------------------------------------------------

--
-- Table structure for table `master_ero`
--

CREATE TABLE `master_ero` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `efin` varchar(200) NOT NULL,
  `p_efin` varchar(200) NOT NULL,
  `service_bureau_num` int(20) NOT NULL,
  `is_parent_efin` int(1) NOT NULL,
  `is_service_bureau` int(1) NOT NULL,
  `is_view` int(1) NOT NULL COMMENT '1 = viewed by pEFIN owner, 0 = not viewed yet',
  `pefin_status` int(1) NOT NULL COMMENT '1 = Approved, 2 = rejected',
  `image` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `business_addr_1` varchar(255) NOT NULL,
  `business_addr_2` varchar(255) NOT NULL,
  `business_phone` varchar(20) NOT NULL,
  `business_zip` varchar(20) NOT NULL,
  `business_city` varchar(200) NOT NULL,
  `business_state` varchar(200) NOT NULL,
  `same_as` int(11) NOT NULL DEFAULT '0',
  `mail_addr_1` varchar(255) NOT NULL,
  `mail_addr_2` varchar(255) NOT NULL,
  `mail_zip` varchar(20) NOT NULL,
  `mail_city` varchar(200) NOT NULL,
  `mail_state` varchar(200) NOT NULL,
  `tax_software` varchar(100) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_routing` varchar(100) NOT NULL,
  `bank_account` varchar(100) NOT NULL,
  `tax_preparation_fee` double(10,2) DEFAULT NULL,
  `bank_transmission_fee` double(10,2) DEFAULT NULL,
  `sb_fee` double(10,2) DEFAULT NULL,
  `e_file_fee` double(10,2) DEFAULT NULL,
  `add_on_fee` double(10,2) DEFAULT NULL,
  `addon` float NOT NULL,
  `file` float NOT NULL,
  `agprep` float NOT NULL,
  `ag` float NOT NULL,
  `date_created` int(11) NOT NULL,
  `complete_status` int(1) NOT NULL COMMENT '1 = done, 0 = not done',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `master_ero`
--

INSERT INTO `master_ero` VALUES(4, 1, '123456123123', '', 0, 0, 0, 0, 0, 'a373c1b7d43f0c2cb0b26be93258ad6e.jpg', '', '', '', '', '', '', '', 0, '', '', '', '', '', 'Tax Act', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 1393533716, 0);
INSERT INTO `master_ero` VALUES(18, 144, '', '', 0, 0, 0, 0, 0, '.', 'TRP Group, LLC', 'Address 1', '', '(321) 312-3123', '12312', 'NK', 'Hawaii', 1, 'Address 1', '', '12312', 'NK', 'Hawaii', 'Lacerte', 'Bank of America', '213123122', '123123123', 150.00, 19.95, 10.00, 10.00, 40.00, 0, 0, 0, 0, 1408419411, 1);
INSERT INTO `master_ero` VALUES(19, 145, '', '', 0, 0, 0, 0, 0, '.', 'TRP Test', 'address 1', '', '(323) 423-4234', '31231', 'NK', 'Arkansas', 1, 'address 1', '', '31231', 'NK', 'Arkansas', 'Taxwise', 'Bank Name', '234234324', '234234234', 20.00, 20.00, 30.00, 40.00, 50.00, 0, 0, 0, 0, 1403677747, 1);
INSERT INTO `master_ero` VALUES(20, 146, '', '', 987654, 0, 1, 0, 0, '.', '', 'Address 1', '', '', '34324', 'NK', 'California', 1, 'Address 1', '', '34324', 'NK', 'California', 'Lacerte', 'HSBC Bank', '', '', 10.00, 20.00, 30.00, 40.00, 50.00, 0, 0, 0, 0, 1397173636, 0);
INSERT INTO `master_ero` VALUES(21, 147, '', '', 888888, 0, 1, 1, 1, '.', 'Test 2', 'Address 1', '', '(242) 432-4424', '23123', 'NK', 'Oregon', 1, 'Address 1', '', '23123', 'NK', 'Oregon', 'Taxwise', 'HSBC', '231231231', '123231232312', 0.00, 0.00, 0.00, NULL, 0.00, 0, 0, 0, 0, 1404422920, 1);
INSERT INTO `master_ero` VALUES(22, 148, '', '', 888888, 1, 1, 1, 1, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(23, 149, '', '', 888888, 1, 1, 1, 1, 'fd433bba2f78ec9e33cb204b930182d0.jpg', 'test', 'test', '', '', '78945', 'sugar land', '', 1, 'test', '', '78945', 'sugar land', '', 'Taxwise', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1397287496, 0);
INSERT INTO `master_ero` VALUES(24, 151, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(25, 152, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(26, 153, '', '', 0, 0, 0, 0, 0, '5d9e640cf0a91319ee9c0b602ef21b84.jpg', '', '123 main street', '2', '', '77478', 'sugarland', '', 1, '123 main street', '2', '77478', 'sugarland', '', 'Taxwise', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1397591370, 0);
INSERT INTO `master_ero` VALUES(27, 154, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', 'Tax Act', '', '', '', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1410442501, 0);
INSERT INTO `master_ero` VALUES(28, 157, '', '', 0, 0, 0, 0, 0, '.', 'abc company', '123 ABC Street', '', '713-999-9090', '77093', 'Houston', 'Texas', 1, '123 ABC Street', '', '77093', 'Houston', 'Texas', 'Drake', 'ABC Bank', '111222333', '1234567890', 100.00, 20.00, 35.00, 10.00, 40.00, 0, 0, 0, 0, 1398524876, 1);
INSERT INTO `master_ero` VALUES(29, 158, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(30, 159, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(31, 160, '', '', 0, 0, 0, 0, 0, '52c0d43aacfccda29475b4e506bbb7a9.png', 'test', 'test', 'afd', 'test', '78978', 'test', 'American Samoa', 1, 'test', '', '78978', 'test', 'Delaware', 'Taxwise', 'anif bank', '123456789', '12345', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1398716995, 1);
INSERT INTO `master_ero` VALUES(32, 163, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(33, 164, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(34, 165, '', '', 0, 0, 0, 0, 0, '.', 'dfaf', 'ad', '', 'dsafa', '56789', 'adf', 'American Samoa', 1, 'ad', '', '56789', 'adf', 'American Samoa', 'ProSeries', '122', '123456789', '12345', 23.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1398959444, 1);
INSERT INTO `master_ero` VALUES(35, 166, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(36, 167, '', '', 0, 0, 0, 0, 0, '.', 'test', 'adsf', 'afd', 'adfa', '12344', 'afd', 'American Samoa', 1, 'adsf', 'afd', '12344', 'afd', 'American Samoa', 'ProSeries', '123', '123456789', '44444', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1399471016, 1);
INSERT INTO `master_ero` VALUES(37, 168, '', '', 0, 0, 0, 0, 0, '.', 'abc', 'afadf', '', '(234) 242-3423', '77777', 'qwerq', 'Alabama', 1, 'afadf', '', '77777', 'qwerq', 'Alabama', 'Tax Act', 'test', '123456789', '23456', 0.00, 0.00, 0.00, 12.00, 0.00, 0, 0, 0, 0, 1400082245, 1);
INSERT INTO `master_ero` VALUES(38, 172, '', '', 0, 0, 0, 0, 0, '.', 'LMS', '123 Test', '', '(832) 122-1234', '77099', 'Houston', 'Texas', 1, '123 Test', '', '77099', 'Houston', 'Texas', 'Drake', 'BOA', '044072340', '000123456789', 150.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1400689680, 1);
INSERT INTO `master_ero` VALUES(39, 174, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(40, 175, '', '', 0, 0, 0, 0, 0, '.', 'teaer', 'aer', '', '(324) 223-4243', '34242', 'te', 'Alabama', 1, 'aer', '', '34242', 'te', 'Alabama', 'TaxSlayer', 'tewt', '234234234', '23424', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1400789908, 1);
INSERT INTO `master_ero` VALUES(41, 176, '', '', 0, 0, 0, 0, 0, '.', 'afd', 'adfaf', '', '(234) 242-3423', '34223', 'asdf', 'Alabama', 1, 'adfaf', '', '34223', 'asdf', 'Alabama', 'Tax Act', '342', '342344342', '234234234', 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1400791166, 1);
INSERT INTO `master_ero` VALUES(42, 178, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `master_ero` VALUES(43, 179, '', '', 0, 0, 0, 0, 0, '.', 'test comp', 'long way home', '', '(123) 456-7890', '10453', 'Gotham', 'Alabama', 1, 'long way home', '', '10453', 'Gotham', 'Alabama', 'Ultra Tax', 'Bank of America', '044072324', '000123456789', 10.00, 20.00, 30.00, NULL, 50.00, 0, 0, 0, 0, 1403939586, 1);
INSERT INTO `master_ero` VALUES(44, 184, '', '', 0, 0, 0, 0, 0, '.', '', '', '', '', '', '', 'Alabama', 0, '', '', '', '', 'Alabama', '', '', '', '', 0.00, 0.00, 0.00, NULL, 0.00, 0, 0, 0, 0, 1404947849, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mb_menu`
--

CREATE TABLE `mb_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `link` varchar(255) DEFAULT NULL,
  `icon` varchar(50) NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `perm` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mkey` (`mkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

--
-- Dumping data for table `mb_menu`
--

INSERT INTO `mb_menu` VALUES(1, 'YHiuRRiUksvDVLUfpseB', 'People', '', '', 'icon-users', 0, 49, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(2, 'p3rYxGg0ZRrS8xkoNpgd', 'Administrators', '', 'user/administrators', 'icol-user-business', 1, 50, 1, 'a:5:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"8";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(39, '0wsqo7FE7ZmazvSeF0PB', 'Statistics', '', '', 'icon-bars', 0, 35, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(7, '1idQc_REqvKHUDhveAPr', 'Products', '', 'admin/products_manage', 'icol-databases', 9, 48, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(8, 'Uc8aoFkdyiTAf6pPYJMW', 'Orders', '', 'store/orders', 'icol-cart', 9, 50, 1, 'a:5:{i:0;s:1:"2";i:1;s:1:"3";i:2;s:1:"4";i:3;s:1:"5";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(9, 'Qs0I6j7A0WbKZ_KcEUg2', 'Store', '', '', 'icon-shopping-cart', 0, 48, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(10, '2rWZZu6hKw3o2WGevEFZ', 'Tax Setting', '', 'store/tax', 'icol-money', 9, 46, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(11, '_Oeh87ispyzARRqH4hgS', 'Shipping setting', '', 'store/shipping', 'icol-delivery', 9, 45, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(12, '30s9VHNJfouWHjuDtr3j', 'Attributes', '', 'store/attributes', 'icol-brick', 9, 40, 1, 'a:5:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"8";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(104, 'mW6GXoOIMaVP38pBuCK8', 'Categories', '', 'admin/categories', 'icol-chart-organisation', 9, 38, 1, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(110, 'q3pFWhMFzvxccEgYbc4a', 'Home Settings', '', 'home/settings', 'icol-hammer-screwdriver', 100, 47, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(109, 'XLu09XzoTfufFKupr2hc', 'Contact Settings', '', 'contact/lists', 'icol-hammer-screwdriver', 100, 35, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(21, 'SbbZUmwURvpqYowlDFBw', 'Settings', '', '', 'icon-cogs', 0, 18, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(22, 'ByhI1J5ron3hxFsSdTxB', 'Permissions', '', 'user/permissions', 'icol-bomb', 21, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(24, 'zrsIzJSTW97MOkUopDf0', 'Appearance', '', 'admin/appearance', 'icol-layout-select-sidebar', 21, -3, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(100, 'sct56MBx8znMpynugaGU', 'Content', '', '', 'icon-list', 0, 38, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(25, 'e_yRazNLNqbdOiMERGV6', 'User E-mail settings', '', '', 'icol-email', 21, -4, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(26, '9TuvkUSYyHL7NqYSDv2e', 'Site Informations', '', 'admin/siteinfo', 'icol-doc-text-image', 21, 41, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(27, 'rhJEkwekPh5PAULAsjVk', 'Menus', '', 'admin/menus', 'icol-text-list-bullets', 21, -2, 1, 'a:5:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"8";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(29, 'R9_z2iJjvEGFhTa2WDua', 'Roles', '', 'user/roles', 'icol-group', 21, -6, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(36, 'JI5TbTO8T_IMglR5w1D9', 'Warranty', '', 'store/warranty', 'icon-time', 9, 32, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(106, 'VfybhbaDn8TehgwYBjqg', 'About Settings', '', 'about/settings', 'icol-hammer-screwdriver', 100, 44, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(41, 'mAbcNh2XWmOaj1yCeIfm', 'Vendors', '', 'manufacturers', 'icol-user-business-boss', 1, 44, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(91, 'JFU2uxastwXsLEpQysqn', 'Users Management', '', 'manufacturers/list', '', 0, 48, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(42, 'xXM39hGkQjXCcwj3miaE', 'Promotions', '', 'store/promotion', 'icon-gift', 9, 34, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(43, 'LNIn2FaqpDcimZNptG0j', 'Payment Settings', '', 'admin/paypal_setting', 'icol-money-dollar', 21, 46, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(44, 'wQetdVZTqdBzz5xXRVeD', 'Charities', '', 'charityManage', 'icol-user-thief-baldie', 1, 0, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(107, 'Wx0ve90aAX49VxVLJ1NS', 'Resources Settings', '', 'businessResources/settings', 'icol-hammer-screwdriver', 100, 41, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(46, 'pWPL2Dkd3hHDERsqGjZo', 'Independent Representative', '', 'representatives', 'icol-user', 1, 0, 1, 'a:5:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"8";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(75, 'i6tBuDw9K3SanM377hVq', 'Customers', '', 'user/clients', '', 0, 49, 0, 'a:1:{i:0;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(66, 'VBEckT_D9qBhAvEL7xIu', 'Mass E-mail Messages', '', 'admin/massmail', 'icos-mail', 25, -7, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(73, 'Z8X2o76tb4g09e40cact', 'Independent Rep Reports', 'Reports for Sale Representatives.', 'report/salerep', 'icol-chart-organisation', 39, 45, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(72, 'LTuHTYgUdBO_g9pCQWRv', 'Sale Representatives', '', 'representatives/list', '', 0, 48, 1, 'a:2:{i:0;s:1:"6";i:1;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(65, 'BN4zdyvNRvJgvxfM_Hyr', 'Auto E-mail', '', 'admin/automail', 'icol-envelope', 25, 0, 1, 'a:5:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"8";i:4;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(69, 'hecPb9Pu1LBF1sRviKGl', 'Vendor reports', '', 'report/manufacturer', 'icol-chart-bar', 39, 47, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(90, '_pR8e5dKZ9V_ngzGxW_R', 'Sales Reports', '', 'manufacturers/reports', '', 39, 0, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(74, 'Av53UupYu3C30568sc0S', 'Dashboard', '', 'representatives/reports', '', 0, 50, 1, 'a:1:{i:0;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(76, 'GvqeN70Okip54QoTZwb5', 'Charities Reports', '', 'report/charities_report', 'icol-heart', 39, 42, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(108, 'Z1UDmVT5vnqWRsI1oLuH', 'Charities Settings', '', 'charities/settings', 'icol-hammer-screwdriver', 100, 38, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(78, 'BA__AzOWPcVGLWl0uXsn', 'Employees bonus Reports', '', 'report/employees', 'icol-add', 39, 14, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(79, 'JYJX4SG5xmwidbUDDYhg', 'Products Reports', '', 'report/products/productList', 'icol-database', 39, 30, 1, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(81, 'jIy7sH2_cJ8g2TnWBj7y', 'Countries settings', '', 'admin/countries', 'icol-flag-blue', 21, -8, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(89, '2Wvi8GRWHYAtV0Tljufk', 'Dashboard', '', 'charities/reports', '', 0, 50, 1, 'a:1:{i:0;s:1:"8";}');
INSERT INTO `mb_menu` VALUES(92, 'wlGjPcKcfqWt9hpc9P7q', 'Invoices', '', 'report/checks', 'icol-calculator', 39, 10, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(93, 'KApdteqMJttryIyab4O6', 'Donation', '', 'report/donation', 'icol-envelope-2', 39, -50, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(94, 'eAlya_qPtwc5N3UBXk6f', 'Dressing Room Setting', '', 'store/dressingroom', '', 9, 36, 0, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(98, '5KKklWMIR5xhodJnvNwB', 'MultiLevel General setting', '', 'settingrepresent', 'icol-hammer-screwdriver', 9, -40, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(101, 'J4I0h89gpir0zGP2JP0H', 'Events', '', 'events/listEvents', 'icol-calendar-1', 100, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(102, 'qPUXq2c4thoTENkoyhl4', 'Banner Settings', '', 'admin/banner', '', 100, -1, 0, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(103, 'o5lB993dyc76R_sOLiBt', 'Auto Ship', '', 'store/autodelivery', 'icon-truck', 9, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(111, 'vdX02N4Y8aGcvHhQpcNr', 'Financial Report', '', 'report/finalreport', 'icol-chart-curve', 39, 39, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(112, 'RymaIC6i47h_fv4Y8MZr', 'Dashboard', '', 'report/manufacturer/details', 'icon-home', 0, 50, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(113, 'Ca9kKDGb7IYgE1KXQEd3', 'Tax Reports', '', 'report/taxreport', 'icol-calendar-2', 39, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(119, 'cbe54_qukUIUeg4VvBVD', 'Vouchers', '', 'store/orders/vouchers', 'icol-medal-bronze-1', 9, 49, 1, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(120, 'AtwI58Nunqx_GdIhJRN0', 'Redeem Voucher', '', 'store/vouchers', 'icon-archive', 0, 0, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(123, 'aJ82Id6aCFA15VA9QjyE', 'Term Settings', '', 'term/settings', 'icol-page-white-text-width', 100, -38, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(122, 'JJWeXhLsZ3wOqJyKnyHf', 'Policy Settings', '', 'policy/settings', 'icol-page-white-text-width', 100, -40, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(124, 'xV4Gh_YLo32mlHul7H42', 'Banners Setting', '', 'shop/banners_home', 'icol-picture', 100, 31, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(125, '3R68ux74rmfzu2R6JNLC', 'Review List', '', 'store/review', 'icol-comment', 9, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(126, 'E55FLBfoUsG0UDYd8Abg', 'Support', '', 'support/lists', 'icol-user-black-female', 100, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(128, 'xSYvSdH8mdQqVAi5D5y3', 'Transfer History for Member', '', 'store/transfermoney', 'icon-bended-arrow-right', 39, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `mb_menu` VALUES(136, 'vodLjm5iYsxF9iW2YlGO', 'Transfer History for Vendors', 'Transfer history for Vendors', 'store/transfermanu', 'icol-box', 39, 0, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `mb_menu` VALUES(129, 'CqvPQtkHSsT8uOnzHjBM', 'General', '', 'dashboard', 'icon-home', 0, 50, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(130, 'Gr3Do2JnU4Ise1N7b0Wc', 'Dashboard', '', 'admin/maindashboard', 'icol-dashboard', 129, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(132, 'Eqb19fGGAINhp4Uf9z_9', 'Tax Codes', 'Tax Code For Every Product', 'store/taxcode', '', 9, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(133, 'KsA88Jnkwsw7IMDK5KsZ', 'Bank Account', 'Bank Account For Vendor', 'admin/mbankaccount', 'icon-business-card', 0, 0, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `mb_menu` VALUES(135, 'F4FQjCtkEe0_tGNTTUrR', 'Business Type', 'Business Type', 'businesstype', 'icol-hammer-screwdriver', 100, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `mb_menu` VALUES(137, 'N3u5QYxWGQgEiJz_UcB_', 'Policies and Procedures', '', 'procedure/settings', 'icol-hammer-screwdriver', 100, 0, 1, 'a:1:{i:0;s:1:"3";}');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mkey` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `link` varchar(255) DEFAULT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `perm` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mkey` (`mkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=108 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` VALUES(1, 'YHiuRRiUksvDVLUfpseB', 'People', '', '', 0, 50, 1, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(2, 'p3rYxGg0ZRrS8xkoNpgd', 'Administrators', '', 'root/user/administrators', 1, 50, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(39, '0wsqo7FE7ZmazvSeF0PB', 'Reports', '', '', 0, 35, 1, 'a:5:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"9";}');
INSERT INTO `menu` VALUES(106, 'bwc2ptug9fc72xq8q0b2', 'Financial Report', '', 'root/report/finalreport', 39, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(38, 'KLW7g2suUqDYHSKcmrq2', 'Affiliates', '', 'affiliates/list', 1, 47, 1, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(7, '1idQc_REqvKHUDhveAPr', 'Products', '', 'root/admin/products_manage', 9, 48, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"5";}');
INSERT INTO `menu` VALUES(8, 'Uc8aoFkdyiTAf6pPYJMW', 'Orders', '', 'root/store/orders', 9, 49, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `menu` VALUES(9, 'Qs0I6j7A0WbKZ_KcEUg2', 'Store', '', '', 0, 49, 1, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(10, '2rWZZu6hKw3o2WGevEFZ', 'Tax', '', 'root/store/tax', 9, 46, 1, 'a:4:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";}');
INSERT INTO `menu` VALUES(11, '_Oeh87ispyzARRqH4hgS', 'Shipping setting', '', 'root/store/shipping', 9, 45, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(12, '30s9VHNJfouWHjuDtr3j', 'Attributes', '', 'root/store/attributes', 9, 40, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(13, 'aG2f3jxrsAqrevzbrHuH', 'Product Markup', '', 'index.php?q=store/markup/list', 9, 47, 0, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(21, 'SbbZUmwURvpqYowlDFBw', 'Configuration', '', '', 0, 18, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(105, '9x72a8frb1b14cim2m2q', 'Dashboard', '', 'manufacturers/reports', 0, 50, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `menu` VALUES(22, 'ByhI1J5ron3hxFsSdTxB', 'Permissions', '', 'index.php?q=user/permissions', 21, 0, 1, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(23, 'IgP1enIkLXbcvdMA4Tt5', 'Modules', '', 'index.php?q=admin/modules', 21, -1, 0, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(24, 'zrsIzJSTW97MOkUopDf0', 'Appearance', '', 'root/admin/appearance', 21, -3, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `menu` VALUES(25, 'e_yRazNLNqbdOiMERGV6', 'User E-mail settings', '', '', 21, -4, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(26, '9TuvkUSYyHL7NqYSDv2e', 'Site Informations', '', 'root/admin/siteinfo', 21, 41, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `menu` VALUES(27, 'rhJEkwekPh5PAULAsjVk', 'Menus', '', 'admin/menus/list', 21, -2, 1, 'a:9:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";i:3;s:1:"7";i:4;s:1:"8";i:5;s:1:"9";i:6;s:2:"10";i:7;s:2:"11";i:8;s:2:"12";}');
INSERT INTO `menu` VALUES(28, '_xBsXxPpjqjZMT_bxUnT', 'Categories', '', 'root/admin/categories', 9, 38, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(29, 'R9_z2iJjvEGFhTa2WDua', 'Roles', '', 'root/user/roles', 21, -6, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `menu` VALUES(36, 'JI5TbTO8T_IMglR5w1D9', 'Warranty', '', 'root/store/warranty', 9, 34, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(41, 'mAbcNh2XWmOaj1yCeIfm', 'Manufacturers', '', 'manufacturers/list', 1, 44, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(91, 'JFU2uxastwXsLEpQysqn', 'Users Management', '', 'manufacturers/list', 0, 48, 1, 'a:0:{}');
INSERT INTO `menu` VALUES(42, 'xXM39hGkQjXCcwj3miaE', 'Promotions', '', 'store/promotion/list', 9, 36, 1, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(43, 'LNIn2FaqpDcimZNptG0j', 'Payment Settings', '', 'admin/paypal_setting', 21, 46, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(44, 'wQetdVZTqdBzz5xXRVeD', 'Charities', '', 'root/charityManage', 1, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(46, 'pWPL2Dkd3hHDERsqGjZo', 'Independent Representative', '', 'root/representatives', 1, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(56, 'KmvvcU7nlpp0W_OyIp7w', 'Greeting Messages', '', 'gettingmessage/list', 0, 45, 0, 'a:11:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";}');
INSERT INTO `menu` VALUES(75, 'i6tBuDw9K3SanM377hVq', 'Customers', '', 'user/clients', 0, 49, 0, 'a:1:{i:0;s:1:"9";}');
INSERT INTO `menu` VALUES(66, 'VBEckT_D9qBhAvEL7xIu', 'Mass E-mail Messages', '', 'admin/massmail/list', 25, -7, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(73, 'Z8X2o76tb4g09e40cact', 'Independent Rep Reports', 'Reports for Sale Representatives.', 'root/report/salerep', 39, 46, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(80, 'WeSS_Jqr2ntIb7uxCgyf', 'Sales Reports', '', 'reports/salereport', 39, 28, 0, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";}');
INSERT INTO `menu` VALUES(72, 'LTuHTYgUdBO_g9pCQWRv', 'Sale Representatives', '', 'representatives/list', 0, 48, 1, 'a:2:{i:0;s:1:"6";i:1;s:1:"9";}');
INSERT INTO `menu` VALUES(59, 'h_gZLBXHQ6mqdaQkLakR', 'Dashboard', '', 'affiliates/reports', 0, 50, 1, 'a:1:{i:0;s:1:"6";}');
INSERT INTO `menu` VALUES(62, 'rhUB5GSSHHZnoXk2eBcJ', 'Affiliates', '', 'affiliates/list', 0, 48, 1, 'a:1:{i:0;s:2:"12";}');
INSERT INTO `menu` VALUES(64, 'nlMqWJPA0x6gNwmnT8PI', 'Dashboard', '', 'acquisitions/reports', 0, 50, 1, 'a:1:{i:0;s:2:"12";}');
INSERT INTO `menu` VALUES(65, 'BN4zdyvNRvJgvxfM_Hyr', 'Auto E-mail', '', 'root/admin/automail', 25, 0, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `menu` VALUES(67, 'OtOnDOYoR0P2mvlAXxpT', 'Account Payable', '', 'payment/invoices', 39, 20, 0, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(69, 'hecPb9Pu1LBF1sRviKGl', 'Manufacturer reports', '', 'reports/manufacturers', 39, 47, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(90, '_pR8e5dKZ9V_ngzGxW_R', 'Sales Reports', '', 'manufacturers/reports', 39, 0, 1, 'a:1:{i:0;s:1:"5";}');
INSERT INTO `menu` VALUES(74, 'Av53UupYu3C30568sc0S', 'Dashboard', '', 'representatives/reports', 0, 50, 1, 'a:1:{i:0;s:1:"9";}');
INSERT INTO `menu` VALUES(76, 'GvqeN70Okip54QoTZwb5', 'Charities Reports', '', 'root/report/charities_report', 39, 16, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(78, 'BA__AzOWPcVGLWl0uXsn', 'Employees bonus Reports', '', 'root/report/employees', 39, 14, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(79, 'JYJX4SG5xmwidbUDDYhg', 'Products Reports', '', 'root/report/products/productList', 39, 30, 1, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"5";}');
INSERT INTO `menu` VALUES(81, 'jIy7sH2_cJ8g2TnWBj7y', 'Countries settings', '', 'countries/list', 21, -8, 1, 'a:1:{i:0;s:1:"3";}');
INSERT INTO `menu` VALUES(89, '2Wvi8GRWHYAtV0Tljufk', 'Dashboard', '', 'charities/reports', 0, 50, 1, 'a:1:{i:0;s:1:"8";}');
INSERT INTO `menu` VALUES(92, 'wlGjPcKcfqWt9hpc9P7q', 'Invoices', '', 'root/report/checks', 39, 0, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(93, 'KApdteqMJttryIyab4O6', 'Donation', '', 'root/report/donation', 39, -50, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(94, 'eAlya_qPtwc5N3UBXk6f', 'Dressing Room Setting', '', 'root/store/dressingroom', 9, 37, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(95, 'pzMF3BIrxKA0rqbNuxhM', 'Ad Shop', '', 'network/ads/adshop', 0, -3, 1, 'a:1:{i:0;s:1:"6";}');
INSERT INTO `menu` VALUES(96, '863YUxDbNUsay6xLIJRA', 'Ad Storage', '', 'network/ads/adstorage', 0, -5, 1, 'a:1:{i:0;s:1:"6";}');
INSERT INTO `menu` VALUES(98, '5KKklWMIR5xhodJnvNwB', 'MultiLevel General setting', '', 'root/settingrepresent', 9, -40, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(100, '7tEGtf5ERmLw7MiZzZMh', 'Auto Ship', '', 'root/store/autodelivery', 9, 32, 1, 'a:3:{i:0;s:1:"3";i:1;s:1:"4";i:2;s:1:"9";}');
INSERT INTO `menu` VALUES(101, 'sucK1S1bS6qQvfFxTXrD', 'Content Management', '', '', 0, 47, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(102, 'bGTlQsJeZdv5njG0cAeA', 'Events', '', 'root/events/listEvents', 101, 48, 1, 'a:7:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"8";i:6;s:1:"9";}');
INSERT INTO `menu` VALUES(103, '7GfJMRngswVhS2nRfcIB', 'Banner Settings', '', 'root/admin/banner', 101, 41, 1, 'a:2:{i:0;s:1:"3";i:1;s:1:"4";}');
INSERT INTO `menu` VALUES(104, 'axkrll7evvf4rimddqm4', 'Financial Report', '', 'http://98.189.127.195/bellavienetwork.com/report/finalreport', 39, 0, 1, 'a:1:{i:0;s:1:"3";}');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `mkey` varchar(20) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `module` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  PRIMARY KEY (`mkey`),
  UNIQUE KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` VALUES('RyiQ8PZMhMxxCbdmhwOg', 'Add Acquisition Agent', 'acquisitions/add.php', '');
INSERT INTO `modules` VALUES('mjqB2M9o81GPpOumtiZA', 'Delete any Acquisition Agent', 'acquisitions/del.php', '');
INSERT INTO `modules` VALUES('VlYnn1AumdBzmS0dtZrr', 'Edit any Acquisition Agent', 'acquisitions/edit.php', '');
INSERT INTO `modules` VALUES('QY65BqhVz6kjg8wtZXOb', 'Acquisition Agent listing', 'acquisitions/list.php', '');
INSERT INTO `modules` VALUES('0eAbXqffexwVksPMIXw8', 'View own reports', 'acquisitions/reports.php', '');
INSERT INTO `modules` VALUES('SGgZEDitqXuf2UhO_mUe', 'View any acquisitions', 'acquisitions/view.php', '');
INSERT INTO `modules` VALUES('JfZTyEv6qR39n_YRD7aT', 'Add new Category', 'admin/categories/add.php', '');
INSERT INTO `modules` VALUES('TuZLe6oeh8iRd8ABBVxS', 'Delete any Category', 'admin/categories/del.php', '');
INSERT INTO `modules` VALUES('BqROpEvTAvCjQqUUl8Yt', 'Edit category', 'admin/categories/edit.php', '');
INSERT INTO `modules` VALUES('67a_4ABvkYcMdTqtnLcN', 'Categories listing', 'admin/categories/list.php', '');
INSERT INTO `modules` VALUES('7EXMVGv6EjpAfpz_Q0v0', 'Delete E-mail', 'admin/delmail.php', '');
INSERT INTO `modules` VALUES('OxCRgSausc8pGU1rpmmT', 'E-mail assignment', 'admin/emailassignment.php', '');
INSERT INTO `modules` VALUES('wCjCSIBxyWnbjPTqwTKO', 'Add mass email', 'admin/massmail/add.php', '');
INSERT INTO `modules` VALUES('WH4rrUJG7VptnyV57msu', 'Delete mass email', 'admin/massmail/del.php', '');
INSERT INTO `modules` VALUES('9FwijeyFY9jJGc1XXzsU', 'Edit mass email', 'admin/massmail/edit.php', '');
INSERT INTO `modules` VALUES('kIcx6Xs2_Km8eIfnNDTA', 'Mass email listing', 'admin/massmail/list.php', '');
INSERT INTO `modules` VALUES('C2984ZVRndAxLDUHpczo', 'Add new menu', 'admin/menus/add.php', '');
INSERT INTO `modules` VALUES('MMmRjr5WVNmmEkky96el', 'Delete menu', 'admin/menus/del.php', '');
INSERT INTO `modules` VALUES('9FIK8sgnuGB7hNNr5HdP', 'Edit menu', 'admin/menus/edit.php', '');
INSERT INTO `modules` VALUES('jp2Ozbby7gJ6LhFIAMWV', 'View menus', 'admin/menus/list.php', '');
INSERT INTO `modules` VALUES('inRp0uHV2Kzb0qPosRMp', 'Administer modules', 'admin/modules.php', '');
INSERT INTO `modules` VALUES('XlkysQ7TsSDA5kQVEmFw', 'Paypal Settings', 'admin/paypal_setting.php', '');
INSERT INTO `modules` VALUES('W4xLkb0DTCSFN346I0O1', 'Site Informations', 'admin/siteinfo.php', '');
INSERT INTO `modules` VALUES('HhqBr6mWhRh4LEFWGvoo', 'SubMenus', 'admin/SubMenus.php', '');
INSERT INTO `modules` VALUES('X6UAZNwxCan_hDByzNjH', 'Appearance Settings', 'admin/themes.php', '');
INSERT INTO `modules` VALUES('uqA5V6Rqq5Ed1oEP10ct', 'Redirect Permission', 'admin/redirect.php', '');
INSERT INTO `modules` VALUES('0ps8TUvhwFOQWeLG_rXq', 'Add advertiser', 'advertisers/add.php', '');
INSERT INTO `modules` VALUES('mrucFYSwOUPE98BSRWZd', 'Delete any advertisers', 'advertisers/del.php', '');
INSERT INTO `modules` VALUES('rDTdHuJHLvYXMi0h7HDK', 'Edit any advertisers', 'advertisers/edit.php', '');
INSERT INTO `modules` VALUES('qhdpfE7WqWZIoILV2ksD', 'Advertisers listing', 'advertisers/list.php', '');
INSERT INTO `modules` VALUES('GgpiofpkMSU30Yi5sfRJ', 'View any advertisers', 'advertisers/view.php', '');
INSERT INTO `modules` VALUES('1GgedS062iyxoOFCTVNv', 'Add new affiliate', 'affiliates/add.php', '');
INSERT INTO `modules` VALUES('CxoskwnDB4bsAiwE0mzR', 'Delete affiliate', 'affiliates/del.php', '');
INSERT INTO `modules` VALUES('vYeJBJbkuOGWaUekfrOH', 'Edit affiliates', 'affiliates/edit.php', '');
INSERT INTO `modules` VALUES('lPZLXleMyEtTrxrT7s3q', 'Affiliates listting', 'affiliates/list.php', '');
INSERT INTO `modules` VALUES('6yc6hggnyUVJzJklUpYh', 'View own reports', 'affiliates/reports.php', '');
INSERT INTO `modules` VALUES('Th0_EhSBALSv9Urg1yuo', 'View any affiliates', 'affiliates/view.php', '');
INSERT INTO `modules` VALUES('ieYIO9TynHG6Ow5iDNK3', 'Add new charity', 'charities/add.php', '');
INSERT INTO `modules` VALUES('nrolaGs25NgcR5Ku3tTf', 'Delete any charities', 'charities/del.php', '');
INSERT INTO `modules` VALUES('1obFK6NcJm6WEk8FQqwL', 'Edit any charities', 'charities/edit.php', '');
INSERT INTO `modules` VALUES('3DNKxn4qHNvy1w3AsGD1', 'Charities listing', 'charities/list.php', '');
INSERT INTO `modules` VALUES('TzNmJKRp0d0TGDs3Rmjo', 'View own reports', 'charities/reports.php', '');
INSERT INTO `modules` VALUES('ZEMP1FfjaJaU8N6HnNWd', 'View any charities', 'charities/view.php', '');
INSERT INTO `modules` VALUES('QMWlfdeWqn_eSMUJgZSh', 'Add content provider', 'contenprovider/add.php', '');
INSERT INTO `modules` VALUES('xS1vvW2JzOMeqypvCugS', 'Delete any content provider', 'contenprovider/del.php', '');
INSERT INTO `modules` VALUES('IgXpTGYZvG7ToXdJJ6i8', 'Edit any content provider', 'contenprovider/edit.php', '');
INSERT INTO `modules` VALUES('JVbZirkLMqtjxfzfMm57', 'Content provider listing', 'contenprovider/list.php', '');
INSERT INTO `modules` VALUES('T1P7NTGmOOjmylbHDlie', 'View Content Provider', 'contenprovider/view.php', '');
INSERT INTO `modules` VALUES('CBo0HN6i00fIRVEuDaGg', 'Add country', 'countries/add.php', '');
INSERT INTO `modules` VALUES('OQtc2uKuGSz9jOYQqTZg', 'Delete country', 'countries/del.php', '');
INSERT INTO `modules` VALUES('J4OpOiJhifnXVG0N1z8x', 'View countries listing', 'countries/list.php', '');
INSERT INTO `modules` VALUES('gwvpai6q2UxBOaQsjpzs', 'Add state', 'countries/states/add.php', '');
INSERT INTO `modules` VALUES('uNffi5T_tQwAcRPc0LtS', 'Delete state', 'countries/states/del.php', '');
INSERT INTO `modules` VALUES('uQi9RYr0dQiysnEBhmAB', 'Edit state', 'countries/states/edit.php', '');
INSERT INTO `modules` VALUES('2W14DG7tgpaB6iAM7RC_', 'View states listing', 'countries/states/list.php', '');
INSERT INTO `modules` VALUES('wKxPY1g6dGw5snZWTWe0', 'Dressing Room Setting', 'dressingroom/list.php', '');
INSERT INTO `modules` VALUES('aer7nRIjxajUKHzyyFtB', 'Export Invoices', 'export/invoice_acquisitions.php', '');
INSERT INTO `modules` VALUES('bPwt3lfL7jB8oS4BzCLX', 'Export Affiliates listing', 'export/affiliates.php', '');
INSERT INTO `modules` VALUES('CVGcs6ARAThByEVqP02M', 'Export Sale rep report', 'export/salerepre_export.php', '');
INSERT INTO `modules` VALUES('_tJnbDPA5qmxaTztOZ_d', 'Export Product report', 'export/product_export.php', '');
INSERT INTO `modules` VALUES('IhF6Lqmqfe2exBsy57YZ', 'Export Manufacturers report', 'export/manufacturers_export.php', '');
INSERT INTO `modules` VALUES('m_mnTLGwzFA7M63nlfDq', 'Export Employees report', 'export/employees_export.php', '');
INSERT INTO `modules` VALUES('jvvGXN4RKSGXSS_AsFWS', 'Export Charities report', 'export/charities_export.php', '');
INSERT INTO `modules` VALUES('amPNhI0sNtJWO4rBHlhh', 'Export Affiliates report', 'export/affiliates_export.php', '');
INSERT INTO `modules` VALUES('3O4Kv3aOyXvz9abfI1yl', 'Export Acquisition report', 'export/acquisition_export.php', '');
INSERT INTO `modules` VALUES('j77Y1ooyzvFs9z3ur38Q', 'Export sales report', 'export/sales_export.php', '');
INSERT INTO `modules` VALUES('PtfOugT38hdgda55oisO', 'Export Report to Excel', 'export/affiliate_export.php', '');
INSERT INTO `modules` VALUES('EY7Dm0YESVkxepazvUrs', 'Export Report to Excel', 'export/manufacturer_export.php', '');
INSERT INTO `modules` VALUES('1vz5vtZKCho65lzhamLT', 'Export Report to Excel', 'export/salerepresent_export.php', '');
INSERT INTO `modules` VALUES('8Vfc_gBv1TORdcMzwB_Y', 'Export Report to Excel', 'export/charitie_export.php', '');
INSERT INTO `modules` VALUES('IoUDzjKAvlit6ov6uXql', 'Export Report to Excel', 'export/acquisitionagent_export.php', '');
INSERT INTO `modules` VALUES('G43J7E9s59gDm07M9Gcu', 'csDMA', 'gettingmessage/csDMA.php', '');
INSERT INTO `modules` VALUES('SkNNz9JUW05rUX1QrZ9l', 'getting message list', 'gettingmessage/list.php', '');
INSERT INTO `modules` VALUES('YePa5LN4hPnZ01Co0b9L', 'Add greeting', 'greetingsetup/add.php', '');
INSERT INTO `modules` VALUES('132KQTq7HoivsXvny8gG', 'Delete any greeting', 'greetingsetup/del.php', '');
INSERT INTO `modules` VALUES('MuvClXRaYPMPHEonmEjU', 'Edit any greeting', 'greetingsetup/edit.php', '');
INSERT INTO `modules` VALUES('SreaeAnDxu9_OtBZghZ5', 'Greeting listing', 'greetingsetup/list.php', '');
INSERT INTO `modules` VALUES('XBJu5YHgrQ0_85acuoFS', 'View greeting setup', 'greetingsetup/view.php', '');
INSERT INTO `modules` VALUES('IERlYia4qZZcqywkl4rD', 'View shopping cart', 'items/cart.php', 'For external page');
INSERT INTO `modules` VALUES('JhDHbDJvSddpHUBv3Boi', 'Descriptions for Items', 'items/description.php', '');
INSERT INTO `modules` VALUES('qdkGBHQNA8f_eIHfbfBT', 'View Product detail', 'items/item.php', 'For external page');
INSERT INTO `modules` VALUES('jE8Rozbg0qoftzLV7rxx', 'Create new account', 'manufacturers/add.php', '');
INSERT INTO `modules` VALUES('oCwsa3yc90VhtTYHhZO7', 'Delete account', 'manufacturers/del.php', '');
INSERT INTO `modules` VALUES('f3cyrNio5GLj98C900aZ', 'Edit account profile', 'manufacturers/edit.php', '');
INSERT INTO `modules` VALUES('PW69EUGMJjJObLctolHj', 'View account listing', 'manufacturers/list.php', '');
INSERT INTO `modules` VALUES('RisPfUO6KPUoAPn5zTH8', 'Sales reports', 'manufacturers/reports.php', '');
INSERT INTO `modules` VALUES('3fL4QNn5ZVdF3vj8fY3P', 'View account profile', 'manufacturers/view.php', '');
INSERT INTO `modules` VALUES('CM42rgXQZtNRyoLe2Y9Q', 'Employees', 'manufacturers/employee.php', '');
INSERT INTO `modules` VALUES('JblBxvyCirgK4aCkgo15', 'Ad shop', 'network/ads/adshop.php', '');
INSERT INTO `modules` VALUES('IEM6TnaKb9qKaBaxWz04', 'Ad storage', 'network/ads/adstorage.php', '');
INSERT INTO `modules` VALUES('Q7EJ76UckK8SeKOXXOwY', 'Customize ad file', 'network/ads/customize.php', '');
INSERT INTO `modules` VALUES('MNxNdya0X0TDWncSjWVt', 'Edit ad file', 'network/ads/editads.php', '');
INSERT INTO `modules` VALUES('wSbBsPnetK35mpIqOIfA', 'Add Ad Type', 'network/adtype/add.php', '');
INSERT INTO `modules` VALUES('xZ3jb62kRMEduFEMkRRD', 'Delete Ad Type', 'network/adtype/del.php', '');
INSERT INTO `modules` VALUES('rJyDZ6UCDZ31NV_PQ1Zx', 'Edit Ad Type', 'network/adtype/edit.php', '');
INSERT INTO `modules` VALUES('DsBYY5A8MgA3QYwEUg7n', 'View Ad type Listing', 'network/adtype/list.php', '');
INSERT INTO `modules` VALUES('5ZdIKdmp5aNxtdlg9LeL', 'Upload Ad', 'network/adupload/add.php', '');
INSERT INTO `modules` VALUES('SEDxs02c6Yp1MsylvKBp', 'Delete Ad', 'network/adupload/del.php', '');
INSERT INTO `modules` VALUES('LdN4hYagzeRgIkE1av36', 'Edit Ad', 'network/adupload/edit.php', '');
INSERT INTO `modules` VALUES('ihXUAk6WVwbvAOoICoO2', 'View Ad listing', 'network/adupload/list.php', '');
INSERT INTO `modules` VALUES('su81EBSEWKA5SnPhxK3i', 'Add brand', 'network/brand/add.php', '');
INSERT INTO `modules` VALUES('64DwIS4a6H3o20fwrX1d', 'Delete any brands', 'network/brand/del.php', '');
INSERT INTO `modules` VALUES('xr8eENm2mf_i_CEIjxIf', 'Edit any brands', 'network/brand/edit.php', '');
INSERT INTO `modules` VALUES('5BuXqziIm9MJpKOUm0Ly', 'Brands listing', 'network/brand/list.php', '');
INSERT INTO `modules` VALUES('6KG5b_ClxaqsBKeS9nql', 'Add business type', 'network/business/add.php', '');
INSERT INTO `modules` VALUES('m2UCBxlNnWbivIdxHGI5', 'Delete business type', 'network/business/del.php', '');
INSERT INTO `modules` VALUES('HZn9ysQxF7IRYsjpPu33', 'Edit business type', 'network/business/edit.php', '');
INSERT INTO `modules` VALUES('g4bBBeZ9Lx4hlgfJyW74', 'Business Type Listtings', 'network/business/list.php', '');
INSERT INTO `modules` VALUES('SFLGXUWCeOukHwM9AA8b', 'Add new content category', 'network/contentcatelory/add.php', '');
INSERT INTO `modules` VALUES('Y1i98oIwaEqT91pVLamP', 'Delete content category', 'network/contentcatelory/del.php', '');
INSERT INTO `modules` VALUES('OH0l3Bkt2huP9DN7SksT', 'Edit content category', 'network/contentcatelory/edit.php', '');
INSERT INTO `modules` VALUES('PIBNzRy_RLPvhEGa5RuX', 'Content Categories', 'network/contentcatelory/list.php', '');
INSERT INTO `modules` VALUES('ZPBXieT0pbJ4JaH8R58y', 'Add new contentfile', 'network/contentfile/add.php', '');
INSERT INTO `modules` VALUES('FMTNfpO_68XUMnHVspKH', 'Delete content file', 'network/contentfile/del.php', '');
INSERT INTO `modules` VALUES('qjA0j7Y02Voxw7aBlOBh', 'Edit content file', 'network/contentfile/edit.php', '');
INSERT INTO `modules` VALUES('Mn2oBy9TOJq5SQV1NIRE', 'Content file listing', 'network/contentfile/list.php', '');
INSERT INTO `modules` VALUES('s51P22gdFIkhVcvmBu6e', 'view content file', 'network/contentfile/view.php', '');
INSERT INTO `modules` VALUES('3l_K2UBEri9KdZp5Qv8l', 'Add dma', 'network/dma/add.php', '');
INSERT INTO `modules` VALUES('3isOkO1L8WQ08QAZz2sR', 'Dma csDMA', 'network/dma/csDMA.php', '');
INSERT INTO `modules` VALUES('_qrcfG8W258Vdpyn4pZ1', 'dma csVenue', 'network/dma/csVenue.php', '');
INSERT INTO `modules` VALUES('aFR9khXJ_fq_wHcBd_n5', 'Delete any dma', 'network/dma/del.php', '');
INSERT INTO `modules` VALUES('5kP8Adlts_ktE2rP_en9', 'Edit any dma', 'network/dma/edit.php', '');
INSERT INTO `modules` VALUES('jE9Gaa7e_k_4uO1VScea', 'DMA listing', 'network/dma/list.php', '');
INSERT INTO `modules` VALUES('bptGgLkgQyfZ3du4dr8d', 'Add equipment', 'network/equipment/add.php', '');
INSERT INTO `modules` VALUES('C77XLYHs0Ls20My7nJd3', 'Delete any equipments', 'network/equipment/del.php', '');
INSERT INTO `modules` VALUES('8iR0mbTptQtVNpHndeF4', 'Edit any equipment', 'network/equipment/edit.php', '');
INSERT INTO `modules` VALUES('QYWGAjDNSGFPPmF2onhH', 'Equipments listing', 'network/equipment/list.php', '');
INSERT INTO `modules` VALUES('4AsGP0TT4oMJbxfBHIep', 'Add options for equipment', 'network/equipment/options/add.php', '');
INSERT INTO `modules` VALUES('dJ4TVyK9MRGFgZaWOUFI', 'Delete option equipment', 'network/equipment/options/del.php', '');
INSERT INTO `modules` VALUES('8hh_FmBbUFqYekGZIgYk', 'Edit option equipment', 'network/equipment/options/edit.php', '');
INSERT INTO `modules` VALUES('XuP4iQQXzkuwrBwWNXXx', 'View Option equipment listing', 'network/equipment/options/list.php', '');
INSERT INTO `modules` VALUES('sdkwnQlWW_6JuLDDqipP', 'Add playlist', 'network/playlist/add.php', '');
INSERT INTO `modules` VALUES('sKbKaxvOkibCllYy2_kN', 'Playlist csDMA', 'network/playlist/csDMA.php', '');
INSERT INTO `modules` VALUES('9fmtQQW6YaLgKMQL9api', 'Edit playlist', 'network/playlist/edit.php', '');
INSERT INTO `modules` VALUES('jqKuByTsy46x9iQPYBLM', 'View playlist', 'network/playlist/list.php', '');
INSERT INTO `modules` VALUES('CmScYyNWy35IjF2L4L4r', 'Playlist systemplay', 'network/playlist/systemplay.php', '');
INSERT INTO `modules` VALUES('E0PD9uiWWUyn6fpTD3F2', 'View playlist', 'network/playlist/view.php', '');
INSERT INTO `modules` VALUES('Wzz54rGY3zewpT0nd8_Z', 'Delete Playlist', 'network/playlist/del.php', '');
INSERT INTO `modules` VALUES('SxcNDsv6k1Y7pn2jFx7x', 'Add title', 'network/title/add.php', '');
INSERT INTO `modules` VALUES('XbUbVJoYHnNoKP1d7m_h', 'Delete any titles', 'network/title/del.php', '');
INSERT INTO `modules` VALUES('cXesa6CFtz2fAMhkkv9R', 'Edit any titles', 'network/title/edit.php', '');
INSERT INTO `modules` VALUES('JL6jqWmn9lva9zt9uVDO', 'Titles listing', 'network/title/list.php', '');
INSERT INTO `modules` VALUES('jvTK70VhlTZUuU4KGfYE', 'Add venues', 'network/venue/add.php', '');
INSERT INTO `modules` VALUES('qiFpHYn2JRGTcot_edgq', 'Venue csVenue', 'network/venue/csVenue.php', '');
INSERT INTO `modules` VALUES('X5agP4Rk_FOqNjFk7ScG', 'Delete any venues', 'network/venue/del.php', '');
INSERT INTO `modules` VALUES('yIpAXIz1LGhyBhEfblqa', 'Edit any venues', 'network/venue/edit.php', '');
INSERT INTO `modules` VALUES('Q4qscVD_CFF1cVr0sRR4', 'Venues listing', 'network/venue/list.php', '');
INSERT INTO `modules` VALUES('mYrN5VT7797M3m568y5z', 'Add verify', 'network/verify/add.php', '');
INSERT INTO `modules` VALUES('3Aq6kHVDoBwApNdkxXgu', 'verify csDMA', 'network/verify/csDMA.php', '');
INSERT INTO `modules` VALUES('Vc7Op1JmpEMh42dezZHO', 'Delete any verifies', 'network/verify/del.php', '');
INSERT INTO `modules` VALUES('p3eFFiP35VmQXitc_3oo', 'Edit any verifies', 'network/verify/edit.php', '');
INSERT INTO `modules` VALUES('w1wqTAuio2WEU09p8Ni3', 'Verify listing', 'network/verify/list.php', '');
INSERT INTO `modules` VALUES('xuIkD2mDUBSfrevaEQiS', 'Verify list location', 'network/verify/list_location.php', '');
INSERT INTO `modules` VALUES('H4mrTkHRXPJk_gvD9I6S', 'Verify verify', 'network/verify/verify.php', '');
INSERT INTO `modules` VALUES('_OYrSjrlZz4wsfObpkSc', 'View Verify', 'network/verify/view.php', '');
INSERT INTO `modules` VALUES('0rnZxJrwgndf12wIaNU0', 'Not Connect SMS Setup', 'network/systemreport/smssetup.php', '');
INSERT INTO `modules` VALUES('cOmuT1v9adb0upPSY6d5', 'Proof of Play', 'network/systemreport/proof-of-play.php', '');
INSERT INTO `modules` VALUES('jha_9wI__s0b6cwP3SN4', 'Payment acquisitions', 'payment/acquisitions.php', '');
INSERT INTO `modules` VALUES('VPTf4yXRu0MD8MD78b78', 'Payment affiliates', 'payment/affiliates.php', '');
INSERT INTO `modules` VALUES('tW_pYw4QdHJYls3eRQWa', 'Payment charities', 'payment/charities.php', '');
INSERT INTO `modules` VALUES('GxDD97A78w5rh4G6pAMt', 'Checkout', 'payment/checkout.php', '');
INSERT INTO `modules` VALUES('7lhhD_mkQ9klvOPuKerI', 'Google Checkout', 'payment/googlecheckout.php', '');
INSERT INTO `modules` VALUES('AlZIfvDvVfOSqV0UVb4B', 'View invoices details', 'payment/invoices/idetails.php', '');
INSERT INTO `modules` VALUES('adMwRrQrw8ZWiOuo9YJU', 'Invoices listing', 'payment/invoices.php', '');
INSERT INTO `modules` VALUES('3nBi9q2TsWjsZWPGcvYy', 'Login to checkout', 'payment/login2checkout.php', '');
INSERT INTO `modules` VALUES('sMkAqFPosn8lBzuzPmh8', 'Payment manufacturers', 'payment/manufacturers.php', '');
INSERT INTO `modules` VALUES('8rqWd5sgpfEHSP89jNmC', 'Order Confirm', 'payment/order_confirm.php', '');
INSERT INTO `modules` VALUES('0kNrJ8QojYmgfC3ixkoM', 'Payment sale representatives', 'payment/representatives.php', '');
INSERT INTO `modules` VALUES('qTj9xh7HUj_KtN13KHgT', 'Submit Order', 'payment/submitorder.php', '');
INSERT INTO `modules` VALUES('utZzVRIh2WUiE3g2adzU', 'Raise', 'payment/raise.php', '');
INSERT INTO `modules` VALUES('mztGbkyLRFubYiAJ09QR', 'Payment employees', 'payment/employees.php', '');
INSERT INTO `modules` VALUES('UA0os6gs_Fcl4wR6GfGn', 'Print All Check', 'print/invoices.php', '');
INSERT INTO `modules` VALUES('K1oy9P7ZNNFxcvLvqROp', 'Print Orders', 'print/odetails.php', '');
INSERT INTO `modules` VALUES('mRBgcibJnIWYy_mxPjlr', 'Print Check Manufacturers', 'print/payorder.php', '');
INSERT INTO `modules` VALUES('WRPZbpks7YIjE9pHhrfu', 'Print ship label', 'print/label.php', '');
INSERT INTO `modules` VALUES('01in1vLFEWXqCBeERpWO', 'Create product', 'product/add.php', '');
INSERT INTO `modules` VALUES('ctXHrceyujTj_2x1m8wQ', 'Add advertising product', 'product/advertising/add.php', '');
INSERT INTO `modules` VALUES('Tj6lLaPspA6sTT0aW4zf', 'Delete advertising product', 'product/advertising/del.php', '');
INSERT INTO `modules` VALUES('cioc_M4mJp2szYD_Yhrd', 'Edit advertising product', 'product/advertising/edit.php', '');
INSERT INTO `modules` VALUES('Imvr6lsReSWh0ak0Mdbl', 'View advertising products', 'product/advertising/list.php', '');
INSERT INTO `modules` VALUES('t4DSR6SFd9JMl53gfkXk', 'Search advertising product', 'product/advertising/search.php', '');
INSERT INTO `modules` VALUES('3JrSKCImFJxZC0HjWoPZ', 'Check money payment', 'product/advertising/check_money.php', '');
INSERT INTO `modules` VALUES('mnAxhtEmErxw1OeAgMMM', 'Createad ad order', 'product/advertising/createad.php', '');
INSERT INTO `modules` VALUES('k9ANZ7XKkByxPZVWho9L', 'Delete any products', 'product/del.php', '');
INSERT INTO `modules` VALUES('Gv8w_bWfNxRYwhBkenUo', 'Edit any products', 'product/edit.php', '');
INSERT INTO `modules` VALUES('PiMt7wfeLeQgANDAPoGr', 'View products listing', 'product/list.php', '');
INSERT INTO `modules` VALUES('Mne9uFjyT3NxbpR9LXdm', 'Modify cost', 'product/modify-cost.php', '');
INSERT INTO `modules` VALUES('2TtrwWSaansNAxMV3VjL', 'View acquisition agent&#039;s reports', 'reports/acquisitions.php', '');
INSERT INTO `modules` VALUES('OW9PaQNMCRZtzhLW4tX5', 'View affiliates&#039;s reports', 'reports/affiliates.php', '');
INSERT INTO `modules` VALUES('GeI7BjSu415HM4lNJZpb', 'View charities&#039;s reports', 'reports/charities.php', '');
INSERT INTO `modules` VALUES('Fdu5l6QfoCaVHIS9RzsL', 'View employees&#039;s reports', 'reports/employees.php', '');
INSERT INTO `modules` VALUES('pnh1gtEPiTQOXaI87odl', 'View manufacturers&#039;s reports', 'reports/manufacturers.php', '');
INSERT INTO `modules` VALUES('Rd6oLNmsbE3qS_hYtMDB', 'Products Reports', 'reports/products.php', '');
INSERT INTO `modules` VALUES('ujehkNfg2irIlnWX09e2', 'View Sales Rep&#039;s report', 'reports/salerep.php', '');
INSERT INTO `modules` VALUES('D8ik7p8q2ARmKWtUzy1q', 'View sale reports', 'reports/salereport.php', '');
INSERT INTO `modules` VALUES('GhZSvLFkJKcbHklEAjUr', 'View Invoices', 'reports/checks.php', '');
INSERT INTO `modules` VALUES('KBDjnW3MkT2R1Qzlq4w0', 'Donation', 'reports/raiselist.php', '');
INSERT INTO `modules` VALUES('Ez1aJcF_lorVQU53GYER', 'Upload Signature', 'reports/salerepupload.php', '');
INSERT INTO `modules` VALUES('HwxxbXJsQ6hju9k4aP3m', 'Add representative', 'representatives/add.php', '');
INSERT INTO `modules` VALUES('4j7J42BAP6hlrEJtqiL7', 'Delete any representatives', 'representatives/del.php', '');
INSERT INTO `modules` VALUES('dTgyRrrS6k4Zu1yoS0Ox', 'Edit any representatives', 'representatives/edit.php', '');
INSERT INTO `modules` VALUES('VWIbphvGp7DtPJRw19dW', 'Representatives listing', 'representatives/list.php', '');
INSERT INTO `modules` VALUES('i8LCY9RGZv3JgALvI62X', 'View own reports', 'representatives/reports.php', '');
INSERT INTO `modules` VALUES('3ufH4X4UV7G35hvSgbn5', 'view Representatives', 'representatives/view.php', '');
INSERT INTO `modules` VALUES('wgECGeYeaQ13a7A5UtPE', 'MultiLevel General setting', 'representatives/settings.php', '');
INSERT INTO `modules` VALUES('qlxksT3yUgsgmWJT1xX2', 'View live monitoring', 'service-support/live monitoring/list.php', '');
INSERT INTO `modules` VALUES('dOUdKk9uDNYUYl4f4XEO', 'csDMA', 'service-support/livecontrol/csDMA.php', '');
INSERT INTO `modules` VALUES('3XVfItYzRKqUoaYZlWiO', 'dataRequest', 'service-support/livecontrol/dataRequest.php', '');
INSERT INTO `modules` VALUES('z6yngst_eXO8JychS1Gy', 'Livecontrol Img Remote', 'service-support/livecontrol/imgRemote.php', '');
INSERT INTO `modules` VALUES('BXiPY7D9TLNiHavNsONw', 'Livecontrol list', 'service-support/livecontrol/list.php', '');
INSERT INTO `modules` VALUES('Aru_PwgwmNVOA4sn0VmJ', 'Add system', 'service-support/systemmanagement/add.php', '');
INSERT INTO `modules` VALUES('vZR2ZcFhQiEgzYgdknAx', 'csSystem', 'service-support/systemmanagement/csSystem.php', '');
INSERT INTO `modules` VALUES('0kapdsD4dPDyDkqsmWA3', 'Edit system', 'service-support/systemmanagement/edit.php', '');
INSERT INTO `modules` VALUES('54_tSfx2tXpi8pybI15L', 'View system listing', 'service-support/systemmanagement/list.php', '');
INSERT INTO `modules` VALUES('Gy__IqrVbSO6LNptTMkb', 'Delete System', 'service-support/systemmanagement/del.php', '');
INSERT INTO `modules` VALUES('zAkwPIxmzsY5QX4ocl_e', 'Software update listing', 'softwareupdate/list.php', '');
INSERT INTO `modules` VALUES('3NaEqpX928aqxlMcT_pi', 'Add tax rate', 'store/addtax.php', '');
INSERT INTO `modules` VALUES('rcd807m3il7bZ7Femsdd', 'Create attribute for product', 'store/attributes/add.php', '');
INSERT INTO `modules` VALUES('qdtOo6Q856gm8jkYfwQo', 'Delete any attribute', 'store/attributes/del.php', '');
INSERT INTO `modules` VALUES('IMpwJVAVdDZtHi7WeNU_', 'Edit any attributes', 'store/attributes/edit.php', '');
INSERT INTO `modules` VALUES('I0lG9wteS9sqLIMktc5w', 'View attributes listing', 'store/attributes/list.php', '');
INSERT INTO `modules` VALUES('FUPc2MZ7oI6XxgswDMBk', 'Create options for attributes', 'store/attributes/options/add.php', '');
INSERT INTO `modules` VALUES('KTAlrcvLeq7K_MN2oBYD', 'Delete any options', 'store/attributes/options/del.php', '');
INSERT INTO `modules` VALUES('9Upw1HTtjjDU345k7qVa', 'Edit any options', 'store/attributes/options/edit.php', '');
INSERT INTO `modules` VALUES('GSLGtnxHOl_N5p_VXTe6', 'View options listing', 'store/attributes/options/list.php', '');
INSERT INTO `modules` VALUES('2IPUCJ5yZPzvw_1Qnon2', 'Delete any shippings', 'store/del_ship.php', '');
INSERT INTO `modules` VALUES('AmEvCEgomkjdSYYjxSHm', 'Delete any taxs', 'store/del_tax.php', '');
INSERT INTO `modules` VALUES('x7HT6xyjMLabYEwp9CEk', 'Edit any shippings', 'store/editship.php', '');
INSERT INTO `modules` VALUES('MNouBcDYVa_h7H134obG', 'Edit any tax rate', 'store/edittax.php', '');
INSERT INTO `modules` VALUES('0bHOG44EwxP9a3sB6VoQ', 'Add markup', 'store/markup/add.php', '');
INSERT INTO `modules` VALUES('WoYSVOKP3LkSNSwiMpN8', 'Delete any markups', 'store/markup/del.php', '');
INSERT INTO `modules` VALUES('Nr6Nkndf9sWUHJCsmb8e', 'Edit any markups', 'store/markup/edit.php', '');
INSERT INTO `modules` VALUES('NiXp0ixBy_zbruOBISHH', 'Markups listing', 'store/markup/list.php', '');
INSERT INTO `modules` VALUES('asrI1T0dVXix55NVd_mC', 'View markups in product', 'store/markup/view.php', '');
INSERT INTO `modules` VALUES('_LDq6hSDZoa0HrxyamBV', 'View Ad orders detail for client', 'store/orders/ads/codetails.php', '');
INSERT INTO `modules` VALUES('_Ki5EVPIV2aVCDbxKT1z', 'View Ad orders detail for admin', 'store/orders/ads/details.php', '');
INSERT INTO `modules` VALUES('81oFiL3jYu5Y4dSyYx7J', 'Advertising orders', 'store/orders/ads.php', '');
INSERT INTO `modules` VALUES('o7uRAve_e5zcWNH5oAdc', 'View order detail for clients', 'store/orders/codetails.php', '');
INSERT INTO `modules` VALUES('V90PbId05_yj7S1wd5vh', 'Mail Invoices', 'store/orders/mailinvoice.php', '');
INSERT INTO `modules` VALUES('0UkLxRGMhKO2IEGJbJy7', 'Order details', 'store/orders/odetails.php', '');
INSERT INTO `modules` VALUES('JXgFF71I1mQQ60rtHY5O', 'Add package to order', 'store/orders/packages/add.php', '');
INSERT INTO `modules` VALUES('xIG5bbEdK3_jPfOjbES3', 'Delete package', 'store/orders/packages/del.php', '');
INSERT INTO `modules` VALUES('QxIMn05UN0yax45z6zCG', 'Edit package', 'store/orders/packages/edit.php', '');
INSERT INTO `modules` VALUES('rCP7IsgJWYBCl_dy09iC', 'View packages', 'store/orders/packages.php', '');
INSERT INTO `modules` VALUES('YGClAxLvXd1dFFacYgMK', 'Admin Refund', 'store/orders/refunds/arefund.php', '');
INSERT INTO `modules` VALUES('4dbedfD3dLv2hXcIkN37', 'Customer request refund', 'store/orders/refunds/refund.php', '');
INSERT INTO `modules` VALUES('RUalpaocg0Na3Oe6THZX', 'Refund', 'store/orders/refunds.php', '');
INSERT INTO `modules` VALUES('ijOp6QXgtQd_AdlPezSk', 'Add shipment to order', 'store/orders/shipments/add.php', '');
INSERT INTO `modules` VALUES('pukh9pdRX2D5bgj8X7dg', 'Edit ship manually', 'store/orders/shipments/editmanually.php', '');
INSERT INTO `modules` VALUES('NggcksSoRAz6VKdTZhAL', 'Edit ship UPS', 'store/orders/shipments/editups.php', '');
INSERT INTO `modules` VALUES('_3Ga_Q9XMb4piarss_H9', 'Ship manually', 'store/orders/shipments/manually.php', '');
INSERT INTO `modules` VALUES('z65jF92ugr6fkBgkhe7T', 'Ship UPS', 'store/orders/shipments/ups.php', '');
INSERT INTO `modules` VALUES('g19o1pHiodhO_bXFaO0g', 'Ship USPS', 'store/orders/shipments/usps.php', '');
INSERT INTO `modules` VALUES('6VmgmsqtxncDdaR5qocF', 'Edit ship USPS', 'store/orders/shipments/editusps.php', '');
INSERT INTO `modules` VALUES('sjirl6XmKMsFxELKX24j', 'View shipments', 'store/orders/shipments.php', '');
INSERT INTO `modules` VALUES('e6Mi7tcnHfTZp2gBX4Nw', 'Receive order mail from customer', 'store/orders/receive_order_mail.php', '');
INSERT INTO `modules` VALUES('F523zDCluvuzr6IopKCW', 'Tracking Shipment', 'store/orders/tracking.php', '');
INSERT INTO `modules` VALUES('QkL6bSwZL9kf4c9sFAE0', 'View orders listing', 'store/orders.php', '');
INSERT INTO `modules` VALUES('VYpcUXq9xSVd3v95duTO', 'Add promotion', 'store/promotion/add.php', '');
INSERT INTO `modules` VALUES('t4Um6SFBh9r3XG6GtmEQ', 'Delete any promotion', 'store/promotion/del.php', '');
INSERT INTO `modules` VALUES('4pTXKRRNbzrusaHoTbQ1', 'Edit any promotion', 'store/promotion/edit.php', '');
INSERT INTO `modules` VALUES('a8TXDPsX1XDVbmHM4np5', 'View promotions listing', 'store/promotion/list.php', '');
INSERT INTO `modules` VALUES('NGo6HVkpX0fXXZKqE3cu', 'Add promotype', 'store/promotion/promotype/add.php', '');
INSERT INTO `modules` VALUES('PGeRSLtLZIFBeUxMFH1r', 'Delete promotype', 'store/promotion/promotype/del.php', '');
INSERT INTO `modules` VALUES('w7ejWOApGDKl9O3Qplqe', 'Edit promotype', 'store/promotion/promotype/edit.php', '');
INSERT INTO `modules` VALUES('TWGYG3zazqsWmx69bvoH', 'View promotypes listing', 'store/promotion/promotype/list.php', '');
INSERT INTO `modules` VALUES('YYTWCLCSWTWFEss_kRaK', 'view promotions in product', 'store/promotion/view.php', '');
INSERT INTO `modules` VALUES('7twjQriRMwozk7lNHNut', 'View shippings', 'store/shipping/view.php', '');
INSERT INTO `modules` VALUES('wgY0yg0JY1jVjF4ZWcFy', 'Add shipping fixed rate', 'store/shipping/manually.php', '');
INSERT INTO `modules` VALUES('yUYJQ9lnMGH8MvYaBOKo', 'Edit shipping fixed rate', 'store/shipping/mmanually.php', '');
INSERT INTO `modules` VALUES('ET_No3Ca68zuRm3wleKY', 'Edit shipping ups', 'store/shipping/mups.php', '');
INSERT INTO `modules` VALUES('KgVlBJm3mWhRFgtU_VV5', 'Add shipping method', 'store/shipping/new.php', '');
INSERT INTO `modules` VALUES('SjozvhVHlvuVCf73OiW1', 'Add shipping ups', 'store/shipping/ups.php', '');
INSERT INTO `modules` VALUES('53HA_0k_L54tdiSzpD6B', 'Edit shipping usps', 'store/shipping/musps.php', '');
INSERT INTO `modules` VALUES('Yqlpvii9q50lYwMXmWWY', 'Add shipping usps', 'store/shipping/usps.php', '');
INSERT INTO `modules` VALUES('RQgUYZjdrfEfvQvQYEPe', 'Edit shipping fedex', 'store/shipping/mfedex.php', '');
INSERT INTO `modules` VALUES('yEpl129dNU1tz8dnWmrd', 'Add shipping fedex', 'store/shipping/fedex.php', '');
INSERT INTO `modules` VALUES('sVjNBFto99sxDIJtzHwc', 'View Shipping listing', 'store/shipping.php', '');
INSERT INTO `modules` VALUES('rnvRkz47Mk_46jHwO0KN', 'Tax rates settings', 'store/tax_rates.php', '');
INSERT INTO `modules` VALUES('Y3_qBJK0f72wjncuM7qn', 'Add warranty', 'store/warranty/add.php', '');
INSERT INTO `modules` VALUES('hqgczNzcD_Qr2QIsoiiu', 'Delete any warranties', 'store/warranty/del.php', '');
INSERT INTO `modules` VALUES('eaQonTIZPYcWdiZDWnFp', 'Edit any warranties', 'store/warranty/edit.php', '');
INSERT INTO `modules` VALUES('mlGe1exemGzqGeFUYcJD', 'View warranties', 'store/warranty/list.php', '');
INSERT INTO `modules` VALUES('1Lo5UdxGr3VEdrI5v6_H', 'View warranties in products', 'store/warranty/view.php', '');
INSERT INTO `modules` VALUES('ByY8DtRqvpTm08gKaEfs', 'Access User', 'user/accesser.php', '');
INSERT INTO `modules` VALUES('x1WBhuvDquaSTZPlhwBD', 'Add admin account', 'user/admin/add.php', '');
INSERT INTO `modules` VALUES('LkPBhWbiqgAN7ldeGyHW', 'Delete any admin account', 'user/admin/del.php', '');
INSERT INTO `modules` VALUES('THDCwiNDEe6foKHwwIEM', 'Edit any admin account', 'user/admin/edit.php', '');
INSERT INTO `modules` VALUES('P5oLfsQMRmxzU12f9EJC', 'Manage admin account', 'user/admin.php', '');
INSERT INTO `modules` VALUES('JEHYedjAMNbqHq2NIHoo', 'View Client detail', 'user/cdetails.php', '');
INSERT INTO `modules` VALUES('UMODCFJA_h3IMzwQDFfe', 'Clients listing', 'user/clients.php', '');
INSERT INTO `modules` VALUES('ihvQxms6THkCjY6MtFbs', 'Delete any admin account', 'user/deladmin.php', '');
INSERT INTO `modules` VALUES('N41otm4XR91ZgmFEyXzh', 'Delete Client', 'user/delclient.php', '');
INSERT INTO `modules` VALUES('uKKExGfQag9NaZ1ubUgT', 'Configuration E-mail', 'user/mailconfig.php', '');
INSERT INTO `modules` VALUES('T8IZjctI0TPjspNPVSvV', 'View and Change own profiles', 'user/myaccount.php', '');
INSERT INTO `modules` VALUES('Zty0ipkk9q3SnAHxC05r', 'Administer permissions', 'user/permissions.php', '');
INSERT INTO `modules` VALUES('IKB00dFKW1vLkTBs8LC7', 'Permissions for roles', 'user/perms.php', '');
INSERT INTO `modules` VALUES('1uPeVlCmbxDK85zX85wU', 'Manage roles', 'user/roles.php', '');
INSERT INTO `modules` VALUES('VSv5BWnzxQvoaasVlV7n', 'View client account', 'user/view.php', '');

-- --------------------------------------------------------

--
-- Table structure for table `newapp_benefits_insurance_note`
--

CREATE TABLE `newapp_benefits_insurance_note` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `note_text` text NOT NULL,
  `create_date` int(15) NOT NULL,
  `create_by` int(11) NOT NULL COMMENT 'This is the login user id, who is adding this notes',
  `note_from` varchar(10) NOT NULL COMMENT 'new_apps, benefits, insurance',
  `new_app_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `newapp_benefits_insurance_note`
--

INSERT INTO `newapp_benefits_insurance_note` VALUES(1, 'this is test note', 1409615280, 144, 'insurance', 2);
INSERT INTO `newapp_benefits_insurance_note` VALUES(2, 'this is test note 2', 1409615475, 144, 'insurance', 2);
INSERT INTO `newapp_benefits_insurance_note` VALUES(3, 'this is test canceled', 1409615565, 144, 'insurance', 2);
INSERT INTO `newapp_benefits_insurance_note` VALUES(4, 'this is active status', 1409615985, 144, 'insurance', 2);
INSERT INTO `newapp_benefits_insurance_note` VALUES(5, 'this is active benefits', 1409616624, 144, 'benefits', 5);
INSERT INTO `newapp_benefits_insurance_note` VALUES(6, 'this is bow active benefits', 1409817733, 144, 'benefits', 4);
INSERT INTO `newapp_benefits_insurance_note` VALUES(7, 'this is active insurance', 1410183522, 1, 'insurance', 1);
INSERT INTO `newapp_benefits_insurance_note` VALUES(8, 'this is canceled insurance', 1410183539, 1, 'insurance', 4);
INSERT INTO `newapp_benefits_insurance_note` VALUES(9, 'canceled test', 1410342400, 1, 'benefits', 5);

-- --------------------------------------------------------

--
-- Table structure for table `new_app`
--

CREATE TABLE `new_app` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `applicent_id` int(11) NOT NULL,
  `app_total_refund_amt` double(10,2) NOT NULL,
  `app_tax_preparation_fee` double(10,2) DEFAULT '0.00',
  `app_bank_transmission_fee` double(10,2) DEFAULT '0.00',
  `app_sb_fee` double(10,2) DEFAULT '0.00',
  `app_e_file_fee` double(10,2) DEFAULT '0.00',
  `app_add_on_fee` double(10,2) DEFAULT '0.00',
  `app_actual_tax_preparation_fee` double(10,2) DEFAULT '0.00',
  `app_actual_bank_transmission_fee` double(10,2) DEFAULT '0.00',
  `app_actual_sb_fee` double(10,2) DEFAULT '0.00',
  `app_actual_add_on_fee` double(10,2) DEFAULT '0.00',
  `audit_guard_item` varchar(50) DEFAULT NULL,
  `audit_guard_item_desc` varchar(100) DEFAULT NULL,
  `audit_guard_img_source` varchar(100) DEFAULT NULL,
  `audit_guard_fee` double(10,2) DEFAULT '0.00',
  `actual_audit_guard_fee` double(10,2) DEFAULT '0.00',
  `deposit_amount` double(10,2) DEFAULT '0.00',
  `posted_date` int(15) DEFAULT NULL COMMENT 'when getting the text file form bank.',
  `app_total_fees` double(10,2) DEFAULT '0.00',
  `app_refund_amt` double(10,2) DEFAULT '0.00',
  `app_benefit` double(10,2) DEFAULT '0.00',
  `app_net_refund_amt` double(10,2) DEFAULT '0.00',
  `payment_method` varchar(20) DEFAULT NULL,
  `create_date` int(20) NOT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  `app_bank_name` varchar(100) DEFAULT NULL,
  `app_account_no` varchar(50) DEFAULT NULL,
  `app_routing_no` varchar(50) DEFAULT NULL,
  `assign_acc_no` varchar(20) NOT NULL,
  `assign_routing_no` varchar(20) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0=Pending,1=ready to print, 2=check printed, 3= voided check',
  `payment_status` int(2) NOT NULL COMMENT '0= pending, 1=Partial, 2 = Full',
  `author_id` int(11) NOT NULL COMMENT 'who (employee) submit this application',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `new_app`
--

INSERT INTO `new_app` VALUES(1, 144, 101, 2000.00, 150.00, 19.95, 10.00, 0.00, 40.00, 0.00, 0.00, 0.00, 0.00, 'Audit Guard Protection', 'Secure your clients from IRS Audits', 'icon-shield', 40.00, 0.00, 0.00, NULL, 219.95, 1780.05, 160.00, 1620.05, 'Check', 1409812446, '', '', '', '', '4234432342334534', '4232343433', 1, 0, 171);
INSERT INTO `new_app` VALUES(2, 144, 102, 3000.00, 150.00, 19.95, 10.00, 0.00, 40.00, 0.00, 0.00, 0.00, 0.00, 'Audit Guard Protection', 'Secure your clients from IRS Audits', 'icon-shield', 40.00, 0.00, 0.00, NULL, 219.95, 2780.05, 160.00, 2620.05, 'Check', 1410266466, '', '', '', '', '4234432342334534', '4232343433', 0, 0, 173);

-- --------------------------------------------------------

--
-- Table structure for table `new_applicent`
--

CREATE TABLE `new_applicent` (
  `applicent_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `ss_number` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `add_spouse` varchar(5) NOT NULL,
  `sp_first_name` varchar(100) NOT NULL,
  `sp_last_name` varchar(100) NOT NULL,
  `sp_ssn_no` varchar(50) NOT NULL,
  `sp_dob` varchar(15) NOT NULL,
  `street_address_1` text NOT NULL,
  `street_address_2` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country` varchar(20) NOT NULL,
  `cell_phone` varchar(20) NOT NULL,
  `email_add` varchar(30) NOT NULL,
  `create_date` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`applicent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

--
-- Dumping data for table `new_applicent`
--

INSERT INTO `new_applicent` VALUES(35, 144, 'Rohim', 'Mia', '231-23-1231', '32/31/2312', '0', '', '', '', '', '21312', '', '12312', 'Alabama', '23231', '', '(231) 231-2312', 'sss@dd.com', 1407142870, 144);
INSERT INTO `new_applicent` VALUES(36, 144, 'kammal', 'Hasan', '231-23-1231', '32/31/2312', '0', '', '', '', '', '21312', '', '12312', 'Alabama', '23231', '', '(231) 231-2312', 'sss@dd.com', 1407142894, 144);
INSERT INTO `new_applicent` VALUES(53, 144, 'Azfar', 'Rahman', '242-34-2342', '34/23/2342', '0', '', '', '', '', '3241', '', '23', 'Alabama', '32333', '', '(342) 342-3423', 'shuvro@s.com', 1407302714, 144);
INSERT INTO `new_applicent` VALUES(55, 144, 'Al', 'Jahan', '312-31-2312', '32/13/1231', '0', '', '', '', '', '123', '', '123', 'Alabama', '31233', '', '(312) 312-3123', 's@s.bollo', 1407389289, 144);
INSERT INTO `new_applicent` VALUES(56, 144, 'John', 'Dev2', '312-31-2312', '21/31/2312', '0', '', '', '', '', '312312kljkj', '', '232', 'Alabama', '31222', '', '(312) 312-3213', 'shuvro@osourcebd.com', 1407389411, 144);
INSERT INTO `new_applicent` VALUES(57, 144, 'kamal', 'Hasan', '123-12-3123', '23/12/3123', '0', '', '', '', '', '312', '', '21312', 'Alabama', '23433', '', '(342) 342-3423', 's@s.b', 1407389845, 144);
INSERT INTO `new_applicent` VALUES(58, 144, 'Jahan', 'Ali', '423-42-3423', '34/23/4234', '0', '', '', '', '', '3423', '', '3242', 'Alabama', '23423', '', '(423) 423-4234', 'sss@dd.com', 1407389993, 144);
INSERT INTO `new_applicent` VALUES(61, 144, 'Kanchon', 'Ali', '231-23-1231', '31/23/1231', '0', '', '', '', '', '3123', '', '2312', 'Alabama', '21322', '', '(312) 312-3123', 'shuvro@s.com', 1407394871, 144);
INSERT INTO `new_applicent` VALUES(67, 144, 'Al', 'Shahan', '123-12-3123', '23/12/3123', '0', '', '', '', '', 'test addres', '', 'NK', 'Alabama', '12322', '', '(312) 312-3123', 'shuvro@osourcebd.com', 1407808640, 144);
INSERT INTO `new_applicent` VALUES(68, 144, 'EQWE', 'QWE', '312-31-2312', '23/12/3123', '0', '', '', '', '', 'DASD', '', 'DAS', 'Delaware', '31232', '', '(323) 123-1231', 'shuvro@osourcebd.com', 1407814385, 144);
INSERT INTO `new_applicent` VALUES(69, 144, 'RWEE', 'HASAN', '231-31-2312', '23/12/3231', '0', '', '', '', '', '3123', '', '2312', 'Alabama', '32132', '', '(321) 231-2312', 'shuvro@osourcebd.com', 1407814690, 144);
INSERT INTO `new_applicent` VALUES(70, 144, 'Tuhufatul', 'Jannat', '312-32-3123', '31/23/1231', '0', '', '', '', '', 'Mirpur', '', 'dk', 'Alabama', '21322', '', '(323) 123-1231', 'shuvro@osourcebd.com', 1408225360, 144);
INSERT INTO `new_applicent` VALUES(71, 144, 'John', 'Dev2', '312-31-2312', '21/31/2312', '0', '', '', '', '', '312312kljkj', '', '232', 'Alabama', '31222', '', '(312) 312-3213', 'shuvro@osourcebd.com', 1408228353, 144);
INSERT INTO `new_applicent` VALUES(72, 144, 'David', 'Miles', '312-31-2312', '23/12/3123', '0', '', '', '', '', 'lkdfsldj', '', 'kjl', 'Connecticut', '22232', '', '(323) 123-1231', 's@s55.com', 1408228414, 144);
INSERT INTO `new_applicent` VALUES(74, 144, 'Jannat', 'raya', '123-12-3123', '31/23/1231', '0', '', '', '', '', 'fsdf', '', 'kjl', 'Alabama', '31232', '', '(231) 231-2312', 's@s.b', 1408515223, 144);
INSERT INTO `new_applicent` VALUES(84, 144, 'yasmin', 'Akter', '312-31-2312', '31/23/1231', '0', '', '', '', '', 'sdfsd', '', 'sdfsd', 'Alabama', '32132', '', '(312) 312-3123', 's@s.com', 1409210029, 144);
INSERT INTO `new_applicent` VALUES(87, 144, 'An', 'Nafi', '321-23-1231', '32/13/1231', '0', '', '', '', '', 'dasd', '', '12312', 'Alabama', '23122', '', '(312) 312-3123', 'j@j.com', 1409389680, 144);
INSERT INTO `new_applicent` VALUES(88, 144, 'Shuvro', 'Ahmed', '231-23-2131', '32/13/1231', '0', '', '', '', '', 'sdfsd', '', '123', 'Alabama', '12222', '', '(231) 231-2321', 'shuvro@osourcebd.com', 1409516139, 144);
INSERT INTO `new_applicent` VALUES(95, 144, 'test', 'test', '312-31-2312', '23/12/3123', '0', '', '', '', '', '12312', '', '231', 'Alabama', '32222', '', '(312) 312-3123', 'shuvro@osourcebd.com', 1409631338, 144);
INSERT INTO `new_applicent` VALUES(96, 144, 'test2', 'test2', '312-31-2312', '23/12/3123', '0', '', '', '', '', 'sdfs', '', 'dsdas', 'Alabama', '21232', '', '(312) 312-3123', 'shuvro@osourcebd.com', 1409634143, 144);
INSERT INTO `new_applicent` VALUES(97, 144, 'test3', 'test3', '234-23-4234', '32/42/3423', '0', '', '', '', '', 'fsdf', '', 'dfssd', 'Alabama', '34234', '', '(432) 342-3423', 'shuvro@osourcebd.com', 1409634321, 144);
INSERT INTO `new_applicent` VALUES(98, 144, 'sd', 'sda', '231-23-1231', '32/13/1231', '0', '', '', '', '', 'fsd', '', 'dsad', 'Alaska', '21321', '', '(323) 123-1231', 'shuvro@osourcebd.com', 1409634618, 144);
INSERT INTO `new_applicent` VALUES(99, 144, 'Zishan', 'Momin', '313-11-3131', '31/31/3131', '0', '', '', '', '', '31', '', '13jl', 'Alabama', '31111', '', '(313) 131-3131', 'shuvro@osourcebd.com', 1409811555, 144);
INSERT INTO `new_applicent` VALUES(100, 144, 'Akil', 'Momin', '313-13-1313', '13/13/1313', '0', '', '', '', '', 'sfs', '', 'sfs', 'Indiana', '31311', '', '(313) 131-3131', 'akilmomin@gmail.com', 1409811899, 144);
INSERT INTO `new_applicent` VALUES(101, 144, 'Kismot', 'Ali', '231-23-1231', '23/12/3123', '0', '', '', '', '', ';kl', '', 'k;lkl', 'Alabama', '12312', '', '(231) 231-2312', 'shuvro@osourcebd.com', 1409812446, 144);
INSERT INTO `new_applicent` VALUES(102, 144, 'kamal', 'Hasan', '213-12-3123', '23/12/3123', '0', '', '', '', '', 'fsfsfsd', '', 'jlj', 'Alabama', '23122', '', '(321) 312-3123', 'kamal@kamal.com', 1410266466, 144);

-- --------------------------------------------------------

--
-- Table structure for table `new_app_product`
--

CREATE TABLE `new_app_product` (
  `app_pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `prodcut_name` varchar(100) NOT NULL,
  `short_desc` varchar(100) NOT NULL,
  `img_source` text NOT NULL,
  `price` double(10,2) NOT NULL,
  `create_date` int(11) NOT NULL,
  `insurance_status` int(2) DEFAULT NULL COMMENT 'Instuance Status, 0=Pending,1=active, 2=canceled',
  PRIMARY KEY (`app_pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `new_app_product`
--


-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `order_date` int(11) NOT NULL,
  `order_from` int(11) NOT NULL COMMENT '1=from top modal box, 2 = from supplies page',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` VALUES(1, 133, 1396096412, 1);
INSERT INTO `orders` VALUES(2, 138, 1396265426, 1);
INSERT INTO `orders` VALUES(3, 140, 1396977817, 1);
INSERT INTO `orders` VALUES(4, 144, 1397504413, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `pkey` varchar(20) NOT NULL,
  `check_number` varchar(100) DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '5',
  `legal_business_id` varchar(50) DEFAULT NULL,
  `legal_business_name` varchar(255) DEFAULT NULL,
  `pay` decimal(20,2) NOT NULL DEFAULT '0.00',
  `date_pay` datetime NOT NULL,
  `pay_type` int(11) NOT NULL DEFAULT '0',
  `pay_month` int(2) DEFAULT NULL,
  `pay_year` int(4) DEFAULT NULL,
  `transferred` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkey` (`pkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `payments_orders`
--

CREATE TABLE `payments_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pkey` varchar(20) NOT NULL,
  `okey` varchar(20) NOT NULL,
  `pay` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `payments_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `paypal_settings`
--

CREATE TABLE `paypal_settings` (
  `payment_name` varchar(50) NOT NULL,
  `auth_login_id` varchar(50) NOT NULL,
  `auth_tran_key` varchar(100) NOT NULL,
  `auth_url` varchar(100) NOT NULL,
  `email_order` text NOT NULL,
  `signature` varchar(255) NOT NULL,
  `api_username` varchar(255) NOT NULL,
  `api_password` varchar(255) NOT NULL,
  `api_signature` varchar(255) NOT NULL,
  `firstdata_host` varchar(255) NOT NULL,
  `firstdata_port` varchar(10) NOT NULL,
  `firstdata_store_number` varchar(20) NOT NULL,
  `transaction` varchar(50) NOT NULL,
  UNIQUE KEY `auth_login_id` (`auth_login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paypal_settings`
--

INSERT INTO `paypal_settings` VALUES('authorize', '4ZuE3EFu64J9', '7B8p9CDJ978hqh6V', 'testMode', 'a:1:{i:0;s:20:"dong@nailsalontv.com";}', 'Bella Media Network Inc.', 'nghiem_1322465148_biz_api1.yahoo.com', '1322465185', 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-As-TKrBzjbITAzRLAR8LWYhqEm1F', 'staging.linkpt.net', '1129', '1909425572', 'DECLINE');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `perm` longtext CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` VALUES(1, 1, 'a:15:{i:0;s:14:"items/cart.php";i:1;s:21:"items/description.php";i:2;s:22:"items/findlocation.php";i:3;s:14:"items/item.php";i:4;s:20:"payment/checkout.php";i:5;s:28:"payment/checkoutdelivery.php";i:6;s:26:"payment/googlecheckout.php";i:7;s:26:"payment/login2checkout.php";i:8;s:25:"payment/order_confirm.php";i:9;s:23:"payment/submitorder.php";i:10;s:18:"print/odetails.php";i:11;s:31:"store/orders/refunds/refund.php";i:12;s:24:"store/orders/refunds.php";i:13;s:25:"store/orders/tracking.php";i:14;s:29:"store/orders/trackingusps.php";}');
INSERT INTO `permissions` VALUES(2, 2, 'a:21:{i:0;s:14:"items/cart.php";i:1;s:21:"items/description.php";i:2;s:22:"items/findlocation.php";i:3;s:14:"items/item.php";i:4;s:18:"items/wishlist.php";i:5;s:20:"payment/checkout.php";i:6;s:28:"payment/checkoutdelivery.php";i:7;s:26:"payment/googlecheckout.php";i:8;s:26:"payment/login2checkout.php";i:9;s:25:"payment/order_confirm.php";i:10;s:23:"payment/submitorder.php";i:11;s:31:"payment/submitorderdelivery.php";i:12;s:18:"print/odetails.php";i:13;s:20:"store/orders/ads.php";i:14;s:26:"store/orders/codetails.php";i:15;s:31:"store/orders/refunds/refund.php";i:16;s:24:"store/orders/refunds.php";i:17;s:25:"store/orders/tracking.php";i:18;s:29:"store/orders/trackingusps.php";i:19;s:16:"store/orders.php";i:20;s:18:"user/myaccount.php";}');
INSERT INTO `permissions` VALUES(3, 3, 'a:164:{i:0;s:24:"admin/categories/add.php";i:1;s:24:"admin/categories/del.php";i:2;s:25:"admin/categories/edit.php";i:3;s:25:"admin/categories/list.php";i:4;s:17:"admin/delmail.php";i:5;s:25:"admin/emailassignment.php";i:6;s:22:"admin/massmail/add.php";i:7;s:22:"admin/massmail/del.php";i:8;s:23:"admin/massmail/edit.php";i:9;s:23:"admin/massmail/list.php";i:10;s:19:"admin/menus/add.php";i:11;s:19:"admin/menus/del.php";i:12;s:20:"admin/menus/edit.php";i:13;s:20:"admin/menus/list.php";i:14;s:17:"admin/modules.php";i:15;s:24:"admin/paypal_setting.php";i:16;s:18:"admin/redirect.php";i:17;s:18:"admin/siteinfo.php";i:18;s:18:"admin/SubMenus.php";i:19;s:16:"admin/themes.php";i:20;s:17:"charities/add.php";i:21;s:17:"charities/del.php";i:22;s:18:"charities/edit.php";i:23;s:18:"charities/list.php";i:24;s:21:"charities/reports.php";i:25;s:18:"charities/view.php";i:26;s:17:"countries/add.php";i:27;s:17:"countries/del.php";i:28;s:18:"countries/list.php";i:29;s:24:"countries/states/add.php";i:30;s:24:"countries/states/del.php";i:31;s:25:"countries/states/edit.php";i:32;s:25:"countries/states/list.php";i:33;s:21:"dressingroom/list.php";i:34;s:27:"export/charities_export.php";i:35;s:26:"export/charitie_export.php";i:36;s:27:"export/employees_export.php";i:37;s:31:"export/invoice_acquisitions.php";i:38;s:31:"export/manufacturers_export.php";i:39;s:30:"export/manufacturer_export.php";i:40;s:25:"export/product_export.php";i:41;s:31:"export/salerepresent_export.php";i:42;s:27:"export/salerepre_export.php";i:43;s:23:"export/sales_export.php";i:44;s:18:"items/wishlist.php";i:45;s:21:"manufacturers/add.php";i:46;s:21:"manufacturers/del.php";i:47;s:22:"manufacturers/edit.php";i:48;s:26:"manufacturers/employee.php";i:49;s:24:"manufacturers/export.php";i:50;s:22:"manufacturers/list.php";i:51;s:25:"manufacturers/reports.php";i:52;s:22:"manufacturers/view.php";i:53;s:24:"payment/acquisitions.php";i:54;s:21:"payment/charities.php";i:55;s:21:"payment/employees.php";i:56;s:29:"payment/invoices/idetails.php";i:57;s:20:"payment/invoices.php";i:58;s:25:"payment/manufacturers.php";i:59;s:17:"payment/raise.php";i:60;s:27:"payment/representatives.php";i:61;s:18:"print/invoices.php";i:62;s:15:"print/label.php";i:63;s:18:"print/odetails.php";i:64;s:18:"print/payorder.php";i:65;s:15:"product/add.php";i:66;s:15:"product/del.php";i:67;s:16:"product/edit.php";i:68;s:16:"product/list.php";i:69;s:23:"product/modify-cost.php";i:70;s:22:"product/modify-tax.php";i:71;s:24:"reports/acquisitions.php";i:72;s:21:"reports/charities.php";i:73;s:18:"reports/checks.php";i:74;s:21:"reports/employees.php";i:75;s:25:"reports/manufacturers.php";i:76;s:20:"reports/products.php";i:77;s:21:"reports/raiselist.php";i:78;s:19:"reports/salerep.php";i:79;s:22:"reports/salereport.php";i:80;s:25:"reports/salerepupload.php";i:81;s:23:"representatives/add.php";i:82;s:23:"representatives/del.php";i:83;s:24:"representatives/edit.php";i:84;s:24:"representatives/list.php";i:85;s:27:"representatives/reports.php";i:86;s:28:"representatives/settings.php";i:87;s:24:"representatives/view.php";i:88;s:16:"store/addtax.php";i:89;s:24:"store/attributes/add.php";i:90;s:24:"store/attributes/del.php";i:91;s:25:"store/attributes/edit.php";i:92;s:25:"store/attributes/list.php";i:93;s:32:"store/attributes/options/add.php";i:94;s:32:"store/attributes/options/del.php";i:95;s:33:"store/attributes/options/edit.php";i:96;s:33:"store/attributes/options/list.php";i:97;s:18:"store/del_ship.php";i:98;s:17:"store/del_tax.php";i:99;s:17:"store/edittax.php";i:100;s:20:"store/markup/add.php";i:101;s:20:"store/markup/del.php";i:102;s:21:"store/markup/edit.php";i:103;s:21:"store/markup/list.php";i:104;s:21:"store/markup/view.php";i:105;s:20:"store/orders/ads.php";i:106;s:28:"store/orders/mailinvoice.php";i:107;s:25:"store/orders/odetails.php";i:108;s:29:"store/orders/packages/add.php";i:109;s:29:"store/orders/packages/del.php";i:110;s:30:"store/orders/packages/edit.php";i:111;s:25:"store/orders/packages.php";i:112;s:32:"store/orders/refunds/arefund.php";i:113;s:30:"store/orders/shipments/add.php";i:114;s:39:"store/orders/shipments/editmanually.php";i:115;s:34:"store/orders/shipments/editups.php";i:116;s:35:"store/orders/shipments/editusps.php";i:117;s:35:"store/orders/shipments/manually.php";i:118;s:30:"store/orders/shipments/ups.php";i:119;s:31:"store/orders/shipments/usps.php";i:120;s:26:"store/orders/shipments.php";i:121;s:29:"store/orders/trackingusps.php";i:122;s:16:"store/orders.php";i:123;s:23:"store/promotion/add.php";i:124;s:23:"store/promotion/del.php";i:125;s:24:"store/promotion/edit.php";i:126;s:24:"store/promotion/list.php";i:127;s:33:"store/promotion/promotype/add.php";i:128;s:33:"store/promotion/promotype/del.php";i:129;s:34:"store/promotion/promotype/edit.php";i:130;s:34:"store/promotion/promotype/list.php";i:131;s:24:"store/promotion/view.php";i:132;s:24:"store/shipping/fedex.php";i:133;s:27:"store/shipping/manually.php";i:134;s:25:"store/shipping/mfedex.php";i:135;s:28:"store/shipping/mmanually.php";i:136;s:23:"store/shipping/mups.php";i:137;s:24:"store/shipping/musps.php";i:138;s:22:"store/shipping/new.php";i:139;s:22:"store/shipping/ups.php";i:140;s:23:"store/shipping/usps.php";i:141;s:23:"store/shipping/view.php";i:142;s:18:"store/shipping.php";i:143;s:19:"store/tax_rates.php";i:144;s:22:"store/warranty/add.php";i:145;s:22:"store/warranty/del.php";i:146;s:23:"store/warranty/edit.php";i:147;s:23:"store/warranty/list.php";i:148;s:23:"store/warranty/view.php";i:149;s:17:"user/accesser.php";i:150;s:18:"user/admin/add.php";i:151;s:18:"user/admin/del.php";i:152;s:19:"user/admin/edit.php";i:153;s:14:"user/admin.php";i:154;s:17:"user/cdetails.php";i:155;s:16:"user/clients.php";i:156;s:17:"user/deladmin.php";i:157;s:18:"user/delclient.php";i:158;s:19:"user/mailconfig.php";i:159;s:18:"user/myaccount.php";i:160;s:20:"user/permissions.php";i:161;s:14:"user/perms.php";i:162;s:14:"user/roles.php";i:163;s:13:"user/view.php";}');
INSERT INTO `permissions` VALUES(4, 4, 'a:135:{i:0;s:24:"admin/categories/add.php";i:1;s:24:"admin/categories/del.php";i:2;s:25:"admin/categories/edit.php";i:3;s:25:"admin/categories/list.php";i:4;s:25:"admin/emailassignment.php";i:5;s:22:"admin/massmail/add.php";i:6;s:22:"admin/massmail/del.php";i:7;s:23:"admin/massmail/edit.php";i:8;s:23:"admin/massmail/list.php";i:9;s:19:"admin/menus/add.php";i:10;s:20:"admin/menus/list.php";i:11;s:17:"admin/modules.php";i:12;s:24:"admin/paypal_setting.php";i:13;s:18:"admin/siteinfo.php";i:14;s:18:"admin/SubMenus.php";i:15;s:16:"admin/themes.php";i:16;s:17:"charities/add.php";i:17;s:18:"charities/edit.php";i:18;s:18:"charities/list.php";i:19;s:21:"charities/reports.php";i:20;s:18:"charities/view.php";i:21;s:17:"countries/add.php";i:22;s:18:"countries/list.php";i:23;s:24:"countries/states/add.php";i:24;s:25:"countries/states/edit.php";i:25;s:25:"countries/states/list.php";i:26;s:21:"dressingroom/list.php";i:27;s:27:"export/charities_export.php";i:28;s:26:"export/charitie_export.php";i:29;s:27:"export/employees_export.php";i:30;s:31:"export/invoice_acquisitions.php";i:31;s:31:"export/manufacturers_export.php";i:32;s:30:"export/manufacturer_export.php";i:33;s:25:"export/product_export.php";i:34;s:31:"export/salerepresent_export.php";i:35;s:27:"export/salerepre_export.php";i:36;s:23:"export/sales_export.php";i:37;s:18:"items/wishlist.php";i:38;s:21:"manufacturers/add.php";i:39;s:22:"manufacturers/edit.php";i:40;s:26:"manufacturers/employee.php";i:41;s:24:"manufacturers/export.php";i:42;s:22:"manufacturers/list.php";i:43;s:25:"manufacturers/reports.php";i:44;s:22:"manufacturers/view.php";i:45;s:24:"payment/acquisitions.php";i:46;s:21:"payment/charities.php";i:47;s:29:"payment/invoices/idetails.php";i:48;s:20:"payment/invoices.php";i:49;s:25:"payment/manufacturers.php";i:50;s:27:"payment/representatives.php";i:51;s:18:"print/invoices.php";i:52;s:15:"print/label.php";i:53;s:18:"print/odetails.php";i:54;s:18:"print/payorder.php";i:55;s:15:"product/add.php";i:56;s:15:"product/del.php";i:57;s:16:"product/edit.php";i:58;s:16:"product/list.php";i:59;s:23:"product/modify-cost.php";i:60;s:22:"product/modify-tax.php";i:61;s:24:"reports/acquisitions.php";i:62;s:21:"reports/charities.php";i:63;s:18:"reports/checks.php";i:64;s:21:"reports/employees.php";i:65;s:25:"reports/manufacturers.php";i:66;s:20:"reports/products.php";i:67;s:21:"reports/raiselist.php";i:68;s:19:"reports/salerep.php";i:69;s:22:"reports/salereport.php";i:70;s:25:"reports/salerepupload.php";i:71;s:23:"representatives/add.php";i:72;s:24:"representatives/edit.php";i:73;s:24:"representatives/list.php";i:74;s:27:"representatives/reports.php";i:75;s:28:"representatives/settings.php";i:76;s:24:"representatives/view.php";i:77;s:16:"store/addtax.php";i:78;s:24:"store/attributes/add.php";i:79;s:25:"store/attributes/edit.php";i:80;s:25:"store/attributes/list.php";i:81;s:32:"store/attributes/options/add.php";i:82;s:33:"store/attributes/options/edit.php";i:83;s:33:"store/attributes/options/list.php";i:84;s:17:"store/edittax.php";i:85;s:20:"store/markup/add.php";i:86;s:21:"store/markup/edit.php";i:87;s:21:"store/markup/list.php";i:88;s:21:"store/markup/view.php";i:89;s:20:"store/orders/ads.php";i:90;s:28:"store/orders/mailinvoice.php";i:91;s:25:"store/orders/odetails.php";i:92;s:29:"store/orders/packages/add.php";i:93;s:29:"store/orders/packages/del.php";i:94;s:30:"store/orders/packages/edit.php";i:95;s:25:"store/orders/packages.php";i:96;s:32:"store/orders/refunds/arefund.php";i:97;s:30:"store/orders/shipments/add.php";i:98;s:39:"store/orders/shipments/editmanually.php";i:99;s:34:"store/orders/shipments/editups.php";i:100;s:35:"store/orders/shipments/editusps.php";i:101;s:35:"store/orders/shipments/manually.php";i:102;s:30:"store/orders/shipments/ups.php";i:103;s:31:"store/orders/shipments/usps.php";i:104;s:26:"store/orders/shipments.php";i:105;s:16:"store/orders.php";i:106;s:23:"store/promotion/add.php";i:107;s:24:"store/promotion/edit.php";i:108;s:24:"store/promotion/list.php";i:109;s:33:"store/promotion/promotype/add.php";i:110;s:34:"store/promotion/promotype/edit.php";i:111;s:34:"store/promotion/promotype/list.php";i:112;s:24:"store/promotion/view.php";i:113;s:24:"store/shipping/fedex.php";i:114;s:27:"store/shipping/manually.php";i:115;s:25:"store/shipping/mfedex.php";i:116;s:28:"store/shipping/mmanually.php";i:117;s:23:"store/shipping/mups.php";i:118;s:24:"store/shipping/musps.php";i:119;s:22:"store/shipping/new.php";i:120;s:22:"store/shipping/ups.php";i:121;s:23:"store/shipping/usps.php";i:122;s:23:"store/shipping/view.php";i:123;s:18:"store/shipping.php";i:124;s:19:"store/tax_rates.php";i:125;s:22:"store/warranty/add.php";i:126;s:23:"store/warranty/edit.php";i:127;s:23:"store/warranty/list.php";i:128;s:23:"store/warranty/view.php";i:129;s:17:"user/accesser.php";i:130;s:17:"user/cdetails.php";i:131;s:16:"user/clients.php";i:132;s:18:"user/delclient.php";i:133;s:18:"user/myaccount.php";i:134;s:13:"user/view.php";}');
INSERT INTO `permissions` VALUES(5, 5, 'a:42:{i:0;s:18:"admin/SubMenus.php";i:1;s:30:"export/manufacturer_export.php";i:2;s:18:"items/wishlist.php";i:3;s:21:"manufacturers/add.php";i:4;s:21:"manufacturers/del.php";i:5;s:22:"manufacturers/edit.php";i:6;s:26:"manufacturers/employee.php";i:7;s:22:"manufacturers/list.php";i:8;s:25:"manufacturers/reports.php";i:9;s:22:"manufacturers/view.php";i:10;s:15:"print/label.php";i:11;s:18:"print/odetails.php";i:12;s:15:"product/add.php";i:13;s:15:"product/del.php";i:14;s:16:"product/edit.php";i:15;s:16:"product/list.php";i:16;s:20:"reports/products.php";i:17;s:24:"store/attributes/add.php";i:18;s:25:"store/attributes/edit.php";i:19;s:25:"store/attributes/list.php";i:20;s:32:"store/attributes/options/add.php";i:21;s:33:"store/attributes/options/edit.php";i:22;s:33:"store/attributes/options/list.php";i:23;s:28:"store/orders/mailinvoice.php";i:24;s:25:"store/orders/odetails.php";i:25;s:29:"store/orders/packages/add.php";i:26;s:29:"store/orders/packages/del.php";i:27;s:30:"store/orders/packages/edit.php";i:28;s:25:"store/orders/packages.php";i:29;s:35:"store/orders/receive_order_mail.php";i:30;s:32:"store/orders/refunds/arefund.php";i:31;s:30:"store/orders/shipments/add.php";i:32;s:39:"store/orders/shipments/editmanually.php";i:33;s:34:"store/orders/shipments/editups.php";i:34;s:35:"store/orders/shipments/editusps.php";i:35;s:35:"store/orders/shipments/manually.php";i:36;s:30:"store/orders/shipments/ups.php";i:37;s:31:"store/orders/shipments/usps.php";i:38;s:26:"store/orders/shipments.php";i:39;s:16:"store/orders.php";i:40;s:17:"user/accesser.php";i:41;s:18:"user/myaccount.php";}');
INSERT INTO `permissions` VALUES(6, 8, 'a:6:{i:0;s:21:"charities/reports.php";i:1;s:26:"export/charitie_export.php";i:2;s:18:"items/wishlist.php";i:3;s:25:"store/orders/odetails.php";i:4;s:16:"store/orders.php";i:5;s:18:"user/myaccount.php";}');
INSERT INTO `permissions` VALUES(7, 9, 'a:31:{i:0;s:14:"items/cart.php";i:1;s:21:"items/description.php";i:2;s:22:"items/findlocation.php";i:3;s:14:"items/item.php";i:4;s:18:"items/wishlist.php";i:5;s:20:"payment/checkout.php";i:6;s:28:"payment/checkoutdelivery.php";i:7;s:27:"payment/checkout_donate.php";i:8;s:26:"payment/googlecheckout.php";i:9;s:26:"payment/login2checkout.php";i:10;s:25:"payment/order_confirm.php";i:11;s:34:"payment/order_confirm_delivery.php";i:12;s:24:"payment/submitdonate.php";i:13;s:23:"payment/submitorder.php";i:14;s:31:"payment/submitorderdelivery.php";i:15;s:19:"print/dodetails.php";i:16;s:18:"print/odetails.php";i:17;s:23:"representatives/add.php";i:18;s:24:"representatives/list.php";i:19;s:27:"representatives/reports.php";i:20;s:24:"representatives/view.php";i:21;s:26:"store/autodeliverorder.php";i:22;s:22:"store/autodelivery.php";i:23;s:20:"store/orders/ads.php";i:24;s:26:"store/orders/codetails.php";i:25;s:31:"store/orders/refunds/refund.php";i:26;s:24:"store/orders/refunds.php";i:27;s:25:"store/orders/tracking.php";i:28;s:16:"store/orders.php";i:29;s:16:"user/clients.php";i:30;s:18:"user/myaccount.php";}');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `itm_id` int(10) NOT NULL,
  `rname` varchar(40) NOT NULL,
  `rcontent` text NOT NULL,
  `rating` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `rdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `reviews`
--


-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `rlink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` VALUES(1, 'Anonymous user', 'home');
INSERT INTO `roles` VALUES(2, 'Authenticated user', 'home');
INSERT INTO `roles` VALUES(3, 'Administrator', 'admin/maindashboard');
INSERT INTO `roles` VALUES(5, 'Ero', 'home');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permission`
--

CREATE TABLE `roles_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT 'Foreign Key: role.rid.',
  `permission` varchar(255) NOT NULL DEFAULT '' COMMENT 'A single permission granted to the role identified by rid.',
  `controller` varchar(255) NOT NULL DEFAULT '' COMMENT 'The controllers declaring the permission.',
  `functions` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores the permissions assigned to user roles.' AUTO_INCREMENT=135 ;

--
-- Dumping data for table `roles_permission`
--

INSERT INTO `roles_permission` VALUES(1, 1, 'Ero', 'Ero', 'a:20:{i:0;s:5:"index";i:1;s:19:"ShowPenddingApprove";i:2;s:10:"approveEro";i:3;s:12:"ShowApproved";i:4;s:23:"ShowPendingRegistration";i:5;s:12:"ShowRejected";i:6;s:11:"ShowAllEros";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";i:19;s:19:"ShowAllErosForAdmin";}');
INSERT INTO `roles_permission` VALUES(2, 1, 'Settings', 'Settings', 'a:21:{i:0;s:5:"index";i:1;s:14:"profileSetting";i:2;s:22:"showCompanyInformation";i:3;s:27:"showSetupCompanyInformation";i:4;s:15:"saveCompanyInfo";i:5;s:20:"saveSetupCompanyInfo";i:6;s:15:"saveProfileInfo";i:7;s:13:"resetPassword";i:8;s:13:"showUserLists";i:9;s:12:"showUserForm";i:10;s:7:"addUser";i:11;s:10:"deleteUser";i:12;s:11:"ShowService";i:13;s:11:"saveService";i:14;s:13:"deleteService";i:15;s:15:"savePaymentInfo";i:16;s:15:"showPaymentInfo";i:17;s:16:"showBankFeesInfo";i:18;s:19:"saveBankingFeesInfo";i:19;s:19:"checkParentEFINInfo";i:20;s:15:"checkSBEFINInfo";}');
INSERT INTO `roles_permission` VALUES(3, 1, 'Auto Application', 'Autoc', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(4, 1, 'Download Resource', 'Download', 'a:1:{i:0;s:8:"get_file";}');
INSERT INTO `roles_permission` VALUES(5, 1, 'Forgot Passwords', 'Getpassword', 'a:2:{i:0;s:5:"index";i:1;s:7:"getpass";}');
INSERT INTO `roles_permission` VALUES(6, 1, 'Home', 'Home', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(7, 1, 'Login Site', 'Login', 'a:3:{i:0;s:5:"index";i:1;s:10:"checklogin";i:2;s:11:"sendmessage";}');
INSERT INTO `roles_permission` VALUES(8, 1, 'Sign In', 'Signin', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(9, 1, 'Sign up', 'Signup', 'a:5:{i:0;s:5:"index";i:1;s:8:"register";i:2;s:13:"saveUserDatas";i:3;s:14:"checkUserlogin";i:4;s:14:"forgotpassword";}');
INSERT INTO `roles_permission` VALUES(10, 1, 'Default Site', 'Site', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(11, 1, 'Clock GMT', 'Timegmt', 'a:2:{i:0;s:5:"index";i:1;s:10:"gettimeGMT";}');
INSERT INTO `roles_permission` VALUES(12, 1, 'TRP Admin Login Site', 'Trpadmin', 'a:2:{i:0;s:5:"index";i:1;s:10:"checklogin";}');
INSERT INTO `roles_permission` VALUES(13, 2, 'Accounts', 'Accounts', 'a:19:{i:0;s:5:"index";i:1;s:27:"ShowPenddingApproveAccounts";i:2;s:10:"approveEro";i:3;s:20:"ShowApprovedAccounts";i:4;s:23:"ShowPendingRegistration";i:5;s:20:"ShowRejectedAccounts";i:6;s:15:"ShowAllAccounts";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";}');
INSERT INTO `roles_permission` VALUES(14, 2, 'Bank Products', 'Bankproducts', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(15, 2, 'Client Center', 'Clientcenter', 'a:12:{i:0;s:5:"index";i:1;s:6:"newapp";i:2;s:22:"saveNewApplicationInfo";i:3;s:21:"showRecentApplication";i:4;s:8:"nextstep";i:5;s:21:"updateApplicationInfo";i:6;s:27:"showPendingFundsApplication";i:7;s:27:"showReadyToPrintApplication";i:8;s:35:"showSelectedReadyToPrintApplication";i:9;s:18:"showAllApplication";i:10;s:22:"showPrintedApplication";i:11;s:21:"showVoidedApplication";}');
INSERT INTO `roles_permission` VALUES(16, 2, 'Compliance Test', 'Compliancetest', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(17, 2, 'Services', 'Customers', 'a:5:{i:0;s:5:"index";i:1;s:16:"showCustomerList";i:2;s:18:"updateCustomerInfo";i:3;s:32:"showDetailsAboutSelectedCustomer";i:4;s:23:"showCustomerReportByEro";}');
INSERT INTO `roles_permission` VALUES(18, 2, 'Ero', 'Ero', 'a:20:{i:0;s:5:"index";i:1;s:19:"ShowPenddingApprove";i:2;s:10:"approveEro";i:3;s:12:"ShowApproved";i:4;s:23:"ShowPendingRegistration";i:5;s:12:"ShowRejected";i:6;s:11:"ShowAllEros";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";i:19;s:19:"ShowAllErosForAdmin";}');
INSERT INTO `roles_permission` VALUES(19, 2, 'Reporting', 'Reporting', 'a:35:{i:0;s:5:"index";i:1;s:27:"showBankProductFundedReport";i:2;s:29:"showBankProductUnfundedReport";i:3;s:39:"showBankProductUnfundedReportDatePicker";i:4;s:37:"showBankProductFundedReportDatePicker";i:5;s:30:"showBankProductByCustomrReport";i:6;s:40:"showBankProductByCustomrReportDatePicker";i:7;s:31:"showBankProductByEmployeeReport";i:8;s:41:"showBankProductByEmployeeReportDatePicker";i:9;s:39:"showBankProductByEmployeeCustomerReport";i:10;s:35:"updateApplicationInfoFromReportPage";i:11;s:33:"showDiscountBenefitsRevenueReport";i:12;s:43:"showDiscountBenefitsRevenueReportDatePicker";i:13;s:34:"showDiscountBenefitsCustomerReport";i:14;s:44:"showDiscountBenefitsCustomerReportDatePicker";i:15;s:38:"showDiscountBenefitsBenefitsSoldReport";i:16;s:48:"showDiscountBenefitsBenefitsSoldReportDatePicker";i:17;s:34:"showDiscountBenefitsEmployeeReport";i:18;s:44:"showDiscountBenefitsEmployeeReportDatePicker";i:19;s:42:"showDiscountBenefitsEmployeeCustomerReport";i:20;s:27:"showInsurancesRevenueReport";i:21;s:37:"showInsurancesRevenueReportDatePicker";i:22;s:33:"showInsurancesInsuranceSoldReport";i:23;s:43:"showInsurancesInsuranceSoldReportDatePicker";i:24;s:28:"showInsurancesCustomerReport";i:25;s:38:"showInsurancesCustomerReportDatePicker";i:26;s:28:"showInsurancesEmployeeReport";i:27;s:38:"showInsurancesEmployeeReportDatePicker";i:28;s:36:"showInsurancesEmployeeCustomerReport";i:29;s:28:"showTotalIncomeRevenueReport";i:30;s:38:"showTotalIncomeRevenueReportDatePicker";i:31;s:30:"showServiceBureauRevenueReport";i:32;s:40:"showServiceBureauRevenueReportDatePicker";i:33;s:38:"showDiscountBenefitsSoldCustomerReport";i:34;s:32:"showInsurancesSoldCustomerReport";}');
INSERT INTO `roles_permission` VALUES(20, 2, 'Services', 'Services', 'a:22:{i:0;s:5:"index";i:1;s:8:"benefits";i:2;s:9:"insurance";i:3;s:12:"addinsurance";i:4;s:20:"showPendingInsurance";i:5;s:11:"addbenefits";i:6;s:19:"showPendingBenefits";i:7;s:19:"updateInsuranceInfo";i:8;s:18:"updateBenefitsInfo";i:9;s:17:"insurancePolicies";i:10;s:14:"benefitsOrders";i:11;s:16:"addinsuranceNext";i:12;s:15:"addbenefitsNext";i:13;s:15:"addNewApplicent";i:14;s:19:"showActiveInsurance";i:15;s:22:"showCancelledInsurance";i:16;s:18:"showActiveBenefits";i:17;s:21:"showCancelledBenefits";i:18;s:15:"searchApplicent";i:19;s:31:"changeStatusAndAddInsuranceNote";i:20;s:30:"changeStatusAndAddBenefitsNote";i:21;s:24:"getSelectedApplicentInfo";}');
INSERT INTO `roles_permission` VALUES(21, 2, 'Settings', 'Settings', 'a:21:{i:0;s:5:"index";i:1;s:14:"profileSetting";i:2;s:22:"showCompanyInformation";i:3;s:27:"showSetupCompanyInformation";i:4;s:15:"saveCompanyInfo";i:5;s:20:"saveSetupCompanyInfo";i:6;s:15:"saveProfileInfo";i:7;s:13:"resetPassword";i:8;s:13:"showUserLists";i:9;s:12:"showUserForm";i:10;s:7:"addUser";i:11;s:10:"deleteUser";i:12;s:11:"ShowService";i:13;s:11:"saveService";i:14;s:13:"deleteService";i:15;s:15:"savePaymentInfo";i:16;s:15:"showPaymentInfo";i:17;s:16:"showBankFeesInfo";i:18;s:19:"saveBankingFeesInfo";i:19;s:19:"checkParentEFINInfo";i:20;s:15:"checkSBEFINInfo";}');
INSERT INTO `roles_permission` VALUES(22, 2, 'Training', 'Training', 'a:10:{i:0;s:5:"index";i:1;s:8:"doupload";i:2;s:14:"showCompliance";i:3;s:12:"saveDocument";i:4;s:19:"showReviewMaterials";i:5;s:18:"viewReviewMaterial";i:6;s:20:"saveTrainingExamData";i:7;s:19:"showUsersExamResult";i:8;s:11:"examDetails";i:9;s:11:"certificate";}');
INSERT INTO `roles_permission` VALUES(23, 2, 'Auto Application', 'Autoc', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(24, 2, 'Download Resource', 'Download', 'a:1:{i:0;s:8:"get_file";}');
INSERT INTO `roles_permission` VALUES(25, 2, 'Home', 'Home', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(26, 2, 'Log Out Site', 'Logout', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(27, 2, 'Sign In', 'Signin', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(28, 2, 'Sign up', 'Signup', 'a:5:{i:0;s:5:"index";i:1;s:8:"register";i:2;s:13:"saveUserDatas";i:3;s:14:"checkUserlogin";i:4;s:14:"forgotpassword";}');
INSERT INTO `roles_permission` VALUES(29, 2, 'Default Site', 'Site', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(30, 2, 'Clock GMT', 'Timegmt', 'a:2:{i:0;s:5:"index";i:1;s:10:"gettimeGMT";}');
INSERT INTO `roles_permission` VALUES(31, 2, 'Edit My Account', 'Myaccount', 'a:2:{i:0;s:5:"index";i:1;s:9:"takephoto";}');
INSERT INTO `roles_permission` VALUES(32, 3, 'Management Permissions System', 'Permissions', 'a:2:{i:0;s:5:"index";i:1;s:8:"saveperm";}');
INSERT INTO `roles_permission` VALUES(33, 3, 'Accounts', 'Accounts', 'a:19:{i:0;s:5:"index";i:1;s:27:"ShowPenddingApproveAccounts";i:2;s:10:"approveEro";i:3;s:20:"ShowApprovedAccounts";i:4;s:23:"ShowPendingRegistration";i:5;s:20:"ShowRejectedAccounts";i:6;s:15:"ShowAllAccounts";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";}');
INSERT INTO `roles_permission` VALUES(34, 3, 'Management Appearance System', 'Appearance', 'a:2:{i:0;s:5:"index";i:1;s:9:"saveTheme";}');
INSERT INTO `roles_permission` VALUES(35, 3, 'Bankaccount', 'Bankaccount', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(36, 3, 'Bank App', 'Bankapp', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(37, 3, 'Bank Products', 'Bankproducts', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(38, 3, 'Benefits', 'Benefits', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(39, 3, 'Bureau', 'Bureau', 'a:2:{i:0;s:5:"index";i:1;s:14:"showAllService";}');
INSERT INTO `roles_permission` VALUES(40, 3, 'Client Center', 'Clientcenter', 'a:12:{i:0;s:5:"index";i:1;s:6:"newapp";i:2;s:22:"saveNewApplicationInfo";i:3;s:21:"showRecentApplication";i:4;s:8:"nextstep";i:5;s:21:"updateApplicationInfo";i:6;s:27:"showPendingFundsApplication";i:7;s:27:"showReadyToPrintApplication";i:8;s:35:"showSelectedReadyToPrintApplication";i:9;s:18:"showAllApplication";i:10;s:22:"showPrintedApplication";i:11;s:21:"showVoidedApplication";}');
INSERT INTO `roles_permission` VALUES(41, 3, 'Compliance Test', 'Compliancetest', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(42, 3, 'Conetent', 'Content', 'a:5:{i:0;s:5:"index";i:1;s:10:"importEfin";i:2;s:9:"importEin";i:3;s:10:"deleteEfin";i:4;s:9:"deleteEin";}');
INSERT INTO `roles_permission` VALUES(43, 3, 'Services', 'Customers', 'a:5:{i:0;s:5:"index";i:1;s:16:"showCustomerList";i:2;s:18:"updateCustomerInfo";i:3;s:32:"showDetailsAboutSelectedCustomer";i:4;s:23:"showCustomerReportByEro";}');
INSERT INTO `roles_permission` VALUES(44, 3, 'Dashboard', 'Dashboard', 'a:3:{i:0;s:5:"index";i:1;s:12:"getAllEvents";i:2;s:16:"getDataUserChart";}');
INSERT INTO `roles_permission` VALUES(45, 3, 'Ero', 'Ero', 'a:20:{i:0;s:5:"index";i:1;s:19:"ShowPenddingApprove";i:2;s:10:"approveEro";i:3;s:12:"ShowApproved";i:4;s:23:"ShowPendingRegistration";i:5;s:12:"ShowRejected";i:6;s:11:"ShowAllEros";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";i:19;s:19:"ShowAllErosForAdmin";}');
INSERT INTO `roles_permission` VALUES(46, 3, 'Loans', 'Loans', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(47, 3, 'Management Menu System', 'Menus', 'a:3:{i:0;s:5:"index";i:1;s:8:"savelist";i:2;s:5:"lists";}');
INSERT INTO `roles_permission` VALUES(48, 3, 'Add Menu', 'Menus', 'a:2:{i:0;s:3:"add";i:1;s:7:"saveadd";}');
INSERT INTO `roles_permission` VALUES(49, 3, 'Edit Menu', 'Menus', 'a:2:{i:0;s:4:"edit";i:1;s:8:"saveedit";}');
INSERT INTO `roles_permission` VALUES(50, 3, 'Delete Menu', 'Menus', 'a:1:{i:0;s:6:"delete";}');
INSERT INTO `roles_permission` VALUES(51, 3, 'My Company', 'Mycompany', 'a:3:{i:0;s:5:"index";i:1;s:10:"createUser";i:2;s:4:"edit";}');
INSERT INTO `roles_permission` VALUES(52, 3, 'Orders', 'Orders', 'a:3:{i:0;s:5:"index";i:1;s:4:"cart";i:2;s:18:"saveOrderFromModal";}');
INSERT INTO `roles_permission` VALUES(53, 3, 'Redirect Account', 'Redirect', 'a:1:{i:0;s:5:"Index";}');
INSERT INTO `roles_permission` VALUES(54, 3, 'Reporting', 'Reporting', 'a:35:{i:0;s:5:"index";i:1;s:27:"showBankProductFundedReport";i:2;s:29:"showBankProductUnfundedReport";i:3;s:39:"showBankProductUnfundedReportDatePicker";i:4;s:37:"showBankProductFundedReportDatePicker";i:5;s:30:"showBankProductByCustomrReport";i:6;s:40:"showBankProductByCustomrReportDatePicker";i:7;s:31:"showBankProductByEmployeeReport";i:8;s:41:"showBankProductByEmployeeReportDatePicker";i:9;s:39:"showBankProductByEmployeeCustomerReport";i:10;s:35:"updateApplicationInfoFromReportPage";i:11;s:33:"showDiscountBenefitsRevenueReport";i:12;s:43:"showDiscountBenefitsRevenueReportDatePicker";i:13;s:34:"showDiscountBenefitsCustomerReport";i:14;s:44:"showDiscountBenefitsCustomerReportDatePicker";i:15;s:38:"showDiscountBenefitsBenefitsSoldReport";i:16;s:48:"showDiscountBenefitsBenefitsSoldReportDatePicker";i:17;s:34:"showDiscountBenefitsEmployeeReport";i:18;s:44:"showDiscountBenefitsEmployeeReportDatePicker";i:19;s:42:"showDiscountBenefitsEmployeeCustomerReport";i:20;s:27:"showInsurancesRevenueReport";i:21;s:37:"showInsurancesRevenueReportDatePicker";i:22;s:33:"showInsurancesInsuranceSoldReport";i:23;s:43:"showInsurancesInsuranceSoldReportDatePicker";i:24;s:28:"showInsurancesCustomerReport";i:25;s:38:"showInsurancesCustomerReportDatePicker";i:26;s:28:"showInsurancesEmployeeReport";i:27;s:38:"showInsurancesEmployeeReportDatePicker";i:28;s:36:"showInsurancesEmployeeCustomerReport";i:29;s:28:"showTotalIncomeRevenueReport";i:30;s:38:"showTotalIncomeRevenueReportDatePicker";i:31;s:30:"showServiceBureauRevenueReport";i:32;s:40:"showServiceBureauRevenueReportDatePicker";i:33;s:38:"showDiscountBenefitsSoldCustomerReport";i:34;s:32:"showInsurancesSoldCustomerReport";}');
INSERT INTO `roles_permission` VALUES(55, 3, 'Services', 'Services', 'a:22:{i:0;s:5:"index";i:1;s:8:"benefits";i:2;s:9:"insurance";i:3;s:12:"addinsurance";i:4;s:20:"showPendingInsurance";i:5;s:11:"addbenefits";i:6;s:19:"showPendingBenefits";i:7;s:19:"updateInsuranceInfo";i:8;s:18:"updateBenefitsInfo";i:9;s:17:"insurancePolicies";i:10;s:14:"benefitsOrders";i:11;s:16:"addinsuranceNext";i:12;s:15:"addbenefitsNext";i:13;s:15:"addNewApplicent";i:14;s:19:"showActiveInsurance";i:15;s:22:"showCancelledInsurance";i:16;s:18:"showActiveBenefits";i:17;s:21:"showCancelledBenefits";i:18;s:15:"searchApplicent";i:19;s:31:"changeStatusAndAddInsuranceNote";i:20;s:30:"changeStatusAndAddBenefitsNote";i:21;s:24:"getSelectedApplicentInfo";}');
INSERT INTO `roles_permission` VALUES(56, 3, 'Settings', 'Settings', 'a:21:{i:0;s:5:"index";i:1;s:14:"profileSetting";i:2;s:22:"showCompanyInformation";i:3;s:27:"showSetupCompanyInformation";i:4;s:15:"saveCompanyInfo";i:5;s:20:"saveSetupCompanyInfo";i:6;s:15:"saveProfileInfo";i:7;s:13:"resetPassword";i:8;s:13:"showUserLists";i:9;s:12:"showUserForm";i:10;s:7:"addUser";i:11;s:10:"deleteUser";i:12;s:11:"ShowService";i:13;s:11:"saveService";i:14;s:13:"deleteService";i:15;s:15:"savePaymentInfo";i:16;s:15:"showPaymentInfo";i:17;s:16:"showBankFeesInfo";i:18;s:19:"saveBankingFeesInfo";i:19;s:19:"checkParentEFINInfo";i:20;s:15:"checkSBEFINInfo";}');
INSERT INTO `roles_permission` VALUES(57, 3, 'Training', 'Training', 'a:10:{i:0;s:5:"index";i:1;s:8:"doupload";i:2;s:14:"showCompliance";i:3;s:12:"saveDocument";i:4;s:19:"showReviewMaterials";i:5;s:18:"viewReviewMaterial";i:6;s:20:"saveTrainingExamData";i:7;s:19:"showUsersExamResult";i:8;s:11:"examDetails";i:9;s:11:"certificate";}');
INSERT INTO `roles_permission` VALUES(58, 3, 'Auto Application', 'Autoc', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(59, 3, 'Truncate Commissoin and Orders', 'Autoc', 'a:1:{i:0;s:8:"truncate";}');
INSERT INTO `roles_permission` VALUES(60, 3, 'Download Resource', 'Download', 'a:1:{i:0;s:8:"get_file";}');
INSERT INTO `roles_permission` VALUES(61, 3, 'View ERO List', 'Eros', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(62, 3, 'View ERO', 'Eros', 'a:1:{i:0;s:4:"view";}');
INSERT INTO `roles_permission` VALUES(63, 3, 'Add ERO', 'Eros', 'a:1:{i:0;s:3:"add";}');
INSERT INTO `roles_permission` VALUES(64, 3, 'Delete ERO', 'Eros', 'a:1:{i:0;s:6:"delete";}');
INSERT INTO `roles_permission` VALUES(65, 3, 'Modify ERO', 'Eros', 'a:1:{i:0;s:4:"edit";}');
INSERT INTO `roles_permission` VALUES(66, 3, 'Export Excel ERO', 'Eros', 'a:1:{i:0;s:6:"export";}');
INSERT INTO `roles_permission` VALUES(67, 3, 'Home', 'Home', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(68, 3, 'Home Settings', 'Home', 'a:2:{i:0;s:8:"settings";i:1;s:6:"update";}');
INSERT INTO `roles_permission` VALUES(69, 3, 'Login Site', 'Login', 'a:3:{i:0;s:5:"index";i:1;s:10:"checklogin";i:2;s:11:"sendmessage";}');
INSERT INTO `roles_permission` VALUES(70, 3, 'Log Out Site', 'Logout', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(71, 3, 'View my account', 'Myaccount_links', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(72, 3, 'Sign In', 'Signin', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(73, 3, 'Sign up', 'Signup', 'a:5:{i:0;s:5:"index";i:1;s:8:"register";i:2;s:13:"saveUserDatas";i:3;s:14:"checkUserlogin";i:4;s:14:"forgotpassword";}');
INSERT INTO `roles_permission` VALUES(74, 3, 'Default Site', 'Site', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(75, 3, 'Clock GMT', 'Timegmt', 'a:2:{i:0;s:5:"index";i:1;s:10:"gettimeGMT";}');
INSERT INTO `roles_permission` VALUES(76, 3, 'View Administrators Listing', 'Administrators', 'a:3:{i:0;s:5:"index";i:1;s:16:"loadDataAccounts";i:2;s:13:"loadDataRoles";}');
INSERT INTO `roles_permission` VALUES(77, 3, 'Add Account', 'Administrators', 'a:2:{i:0;s:3:"add";i:1;s:11:"saveAccount";}');
INSERT INTO `roles_permission` VALUES(78, 3, 'Edit Account', 'Administrators', 'a:2:{i:0;s:4:"edit";i:1;s:13:"updateAccount";}');
INSERT INTO `roles_permission` VALUES(79, 3, 'Delete Account', 'Administrators', 'a:2:{i:0;s:16:"preDeleteAccount";i:1;s:13:"deleteAccount";}');
INSERT INTO `roles_permission` VALUES(80, 3, 'Edit My Account', 'Myaccount', 'a:2:{i:0;s:5:"index";i:1;s:9:"takephoto";}');
INSERT INTO `roles_permission` VALUES(81, 3, 'Management Permissions Role', 'Rolepermission', 'a:2:{i:0;s:5:"index";i:1;s:8:"saveperm";}');
INSERT INTO `roles_permission` VALUES(82, 3, 'Manage Roles', 'Roles', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(83, 5, 'Management Permissions System', 'Permissions', 'a:2:{i:0;s:5:"index";i:1;s:8:"saveperm";}');
INSERT INTO `roles_permission` VALUES(84, 5, 'Accounts', 'Accounts', 'a:19:{i:0;s:5:"index";i:1;s:27:"ShowPenddingApproveAccounts";i:2;s:10:"approveEro";i:3;s:20:"ShowApprovedAccounts";i:4;s:23:"ShowPendingRegistration";i:5;s:20:"ShowRejectedAccounts";i:6;s:15:"ShowAllAccounts";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";}');
INSERT INTO `roles_permission` VALUES(85, 5, 'Management Appearance System', 'Appearance', 'a:2:{i:0;s:5:"index";i:1;s:9:"saveTheme";}');
INSERT INTO `roles_permission` VALUES(86, 5, 'Bankaccount', 'Bankaccount', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(87, 5, 'Bank App', 'Bankapp', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(88, 5, 'Bank Products', 'Bankproducts', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(89, 5, 'Benefits', 'Benefits', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(90, 5, 'Bureau', 'Bureau', 'a:2:{i:0;s:5:"index";i:1;s:14:"showAllService";}');
INSERT INTO `roles_permission` VALUES(91, 5, 'Client Center', 'Clientcenter', 'a:12:{i:0;s:5:"index";i:1;s:6:"newapp";i:2;s:22:"saveNewApplicationInfo";i:3;s:21:"showRecentApplication";i:4;s:8:"nextstep";i:5;s:21:"updateApplicationInfo";i:6;s:27:"showPendingFundsApplication";i:7;s:27:"showReadyToPrintApplication";i:8;s:35:"showSelectedReadyToPrintApplication";i:9;s:18:"showAllApplication";i:10;s:22:"showPrintedApplication";i:11;s:21:"showVoidedApplication";}');
INSERT INTO `roles_permission` VALUES(92, 5, 'Compliance Test', 'Compliancetest', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(93, 5, 'Conetent', 'Content', 'a:5:{i:0;s:5:"index";i:1;s:10:"importEfin";i:2;s:9:"importEin";i:3;s:10:"deleteEfin";i:4;s:9:"deleteEin";}');
INSERT INTO `roles_permission` VALUES(94, 5, 'Services', 'Customers', 'a:5:{i:0;s:5:"index";i:1;s:16:"showCustomerList";i:2;s:18:"updateCustomerInfo";i:3;s:32:"showDetailsAboutSelectedCustomer";i:4;s:23:"showCustomerReportByEro";}');
INSERT INTO `roles_permission` VALUES(95, 5, 'Dashboard', 'Dashboard', 'a:3:{i:0;s:5:"index";i:1;s:12:"getAllEvents";i:2;s:16:"getDataUserChart";}');
INSERT INTO `roles_permission` VALUES(96, 5, 'Ero', 'Ero', 'a:20:{i:0;s:5:"index";i:1;s:19:"ShowPenddingApprove";i:2;s:10:"approveEro";i:3;s:12:"ShowApproved";i:4;s:23:"ShowPendingRegistration";i:5;s:12:"ShowRejected";i:6;s:11:"ShowAllEros";i:7;s:9:"rejectEro";i:8;s:17:"approveFromReject";i:9;s:14:"rejectApproved";i:10;s:9:"deleteEro";i:11;s:14:"ClickToEditEro";i:12;s:13:"resetPassword";i:13;s:13:"ShowUserLists";i:14;s:12:"ShowServices";i:15;s:11:"saveService";i:16;s:13:"deleteService";i:17;s:7:"addUser";i:18;s:10:"deleteUser";i:19;s:19:"ShowAllErosForAdmin";}');
INSERT INTO `roles_permission` VALUES(97, 5, 'Loans', 'Loans', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(98, 5, 'Management Menu System', 'Menus', 'a:3:{i:0;s:5:"index";i:1;s:8:"savelist";i:2;s:5:"lists";}');
INSERT INTO `roles_permission` VALUES(99, 5, 'Add Menu', 'Menus', 'a:2:{i:0;s:3:"add";i:1;s:7:"saveadd";}');
INSERT INTO `roles_permission` VALUES(100, 5, 'Edit Menu', 'Menus', 'a:2:{i:0;s:4:"edit";i:1;s:8:"saveedit";}');
INSERT INTO `roles_permission` VALUES(101, 5, 'Delete Menu', 'Menus', 'a:1:{i:0;s:6:"delete";}');
INSERT INTO `roles_permission` VALUES(102, 5, 'My Company', 'Mycompany', 'a:3:{i:0;s:5:"index";i:1;s:10:"createUser";i:2;s:4:"edit";}');
INSERT INTO `roles_permission` VALUES(103, 5, 'Orders', 'Orders', 'a:3:{i:0;s:5:"index";i:1;s:4:"cart";i:2;s:18:"saveOrderFromModal";}');
INSERT INTO `roles_permission` VALUES(104, 5, 'Redirect Account', 'Redirect', 'a:1:{i:0;s:5:"Index";}');
INSERT INTO `roles_permission` VALUES(105, 5, 'Reporting', 'Reporting', 'a:35:{i:0;s:5:"index";i:1;s:27:"showBankProductFundedReport";i:2;s:29:"showBankProductUnfundedReport";i:3;s:39:"showBankProductUnfundedReportDatePicker";i:4;s:37:"showBankProductFundedReportDatePicker";i:5;s:30:"showBankProductByCustomrReport";i:6;s:40:"showBankProductByCustomrReportDatePicker";i:7;s:31:"showBankProductByEmployeeReport";i:8;s:41:"showBankProductByEmployeeReportDatePicker";i:9;s:39:"showBankProductByEmployeeCustomerReport";i:10;s:35:"updateApplicationInfoFromReportPage";i:11;s:33:"showDiscountBenefitsRevenueReport";i:12;s:43:"showDiscountBenefitsRevenueReportDatePicker";i:13;s:34:"showDiscountBenefitsCustomerReport";i:14;s:44:"showDiscountBenefitsCustomerReportDatePicker";i:15;s:38:"showDiscountBenefitsBenefitsSoldReport";i:16;s:48:"showDiscountBenefitsBenefitsSoldReportDatePicker";i:17;s:34:"showDiscountBenefitsEmployeeReport";i:18;s:44:"showDiscountBenefitsEmployeeReportDatePicker";i:19;s:42:"showDiscountBenefitsEmployeeCustomerReport";i:20;s:27:"showInsurancesRevenueReport";i:21;s:37:"showInsurancesRevenueReportDatePicker";i:22;s:33:"showInsurancesInsuranceSoldReport";i:23;s:43:"showInsurancesInsuranceSoldReportDatePicker";i:24;s:28:"showInsurancesCustomerReport";i:25;s:38:"showInsurancesCustomerReportDatePicker";i:26;s:28:"showInsurancesEmployeeReport";i:27;s:38:"showInsurancesEmployeeReportDatePicker";i:28;s:36:"showInsurancesEmployeeCustomerReport";i:29;s:28:"showTotalIncomeRevenueReport";i:30;s:38:"showTotalIncomeRevenueReportDatePicker";i:31;s:30:"showServiceBureauRevenueReport";i:32;s:40:"showServiceBureauRevenueReportDatePicker";i:33;s:38:"showDiscountBenefitsSoldCustomerReport";i:34;s:32:"showInsurancesSoldCustomerReport";}');
INSERT INTO `roles_permission` VALUES(106, 5, 'Services', 'Services', 'a:22:{i:0;s:5:"index";i:1;s:8:"benefits";i:2;s:9:"insurance";i:3;s:12:"addinsurance";i:4;s:20:"showPendingInsurance";i:5;s:11:"addbenefits";i:6;s:19:"showPendingBenefits";i:7;s:19:"updateInsuranceInfo";i:8;s:18:"updateBenefitsInfo";i:9;s:17:"insurancePolicies";i:10;s:14:"benefitsOrders";i:11;s:16:"addinsuranceNext";i:12;s:15:"addbenefitsNext";i:13;s:15:"addNewApplicent";i:14;s:19:"showActiveInsurance";i:15;s:22:"showCancelledInsurance";i:16;s:18:"showActiveBenefits";i:17;s:21:"showCancelledBenefits";i:18;s:15:"searchApplicent";i:19;s:31:"changeStatusAndAddInsuranceNote";i:20;s:30:"changeStatusAndAddBenefitsNote";i:21;s:24:"getSelectedApplicentInfo";}');
INSERT INTO `roles_permission` VALUES(107, 5, 'Settings', 'Settings', 'a:21:{i:0;s:5:"index";i:1;s:14:"profileSetting";i:2;s:22:"showCompanyInformation";i:3;s:27:"showSetupCompanyInformation";i:4;s:15:"saveCompanyInfo";i:5;s:20:"saveSetupCompanyInfo";i:6;s:15:"saveProfileInfo";i:7;s:13:"resetPassword";i:8;s:13:"showUserLists";i:9;s:12:"showUserForm";i:10;s:7:"addUser";i:11;s:10:"deleteUser";i:12;s:11:"ShowService";i:13;s:11:"saveService";i:14;s:13:"deleteService";i:15;s:15:"savePaymentInfo";i:16;s:15:"showPaymentInfo";i:17;s:16:"showBankFeesInfo";i:18;s:19:"saveBankingFeesInfo";i:19;s:19:"checkParentEFINInfo";i:20;s:15:"checkSBEFINInfo";}');
INSERT INTO `roles_permission` VALUES(108, 5, 'Training', 'Training', 'a:10:{i:0;s:5:"index";i:1;s:8:"doupload";i:2;s:14:"showCompliance";i:3;s:12:"saveDocument";i:4;s:19:"showReviewMaterials";i:5;s:18:"viewReviewMaterial";i:6;s:20:"saveTrainingExamData";i:7;s:19:"showUsersExamResult";i:8;s:11:"examDetails";i:9;s:11:"certificate";}');
INSERT INTO `roles_permission` VALUES(109, 5, 'Auto Application', 'Autoc', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(110, 5, 'Truncate Commissoin and Orders', 'Autoc', 'a:1:{i:0;s:8:"truncate";}');
INSERT INTO `roles_permission` VALUES(111, 5, 'Download Resource', 'Download', 'a:1:{i:0;s:8:"get_file";}');
INSERT INTO `roles_permission` VALUES(112, 5, 'View ERO List', 'Eros', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(113, 5, 'View ERO', 'Eros', 'a:1:{i:0;s:4:"view";}');
INSERT INTO `roles_permission` VALUES(114, 5, 'Add ERO', 'Eros', 'a:1:{i:0;s:3:"add";}');
INSERT INTO `roles_permission` VALUES(115, 5, 'Delete ERO', 'Eros', 'a:1:{i:0;s:6:"delete";}');
INSERT INTO `roles_permission` VALUES(116, 5, 'Modify ERO', 'Eros', 'a:1:{i:0;s:4:"edit";}');
INSERT INTO `roles_permission` VALUES(117, 5, 'Export Excel ERO', 'Eros', 'a:1:{i:0;s:6:"export";}');
INSERT INTO `roles_permission` VALUES(118, 5, 'Forgot Passwords', 'Getpassword', 'a:2:{i:0;s:5:"index";i:1;s:7:"getpass";}');
INSERT INTO `roles_permission` VALUES(119, 5, 'Home', 'Home', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(120, 5, 'Home Settings', 'Home', 'a:2:{i:0;s:8:"settings";i:1;s:6:"update";}');
INSERT INTO `roles_permission` VALUES(121, 5, 'Login Site', 'Login', 'a:3:{i:0;s:5:"index";i:1;s:10:"checklogin";i:2;s:11:"sendmessage";}');
INSERT INTO `roles_permission` VALUES(122, 5, 'Log Out Site', 'Logout', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(123, 5, 'View my account', 'Myaccount_links', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(124, 5, 'Sign In', 'Signin', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(125, 5, 'Sign up', 'Signup', 'a:5:{i:0;s:5:"index";i:1;s:8:"register";i:2;s:13:"saveUserDatas";i:3;s:14:"checkUserlogin";i:4;s:14:"forgotpassword";}');
INSERT INTO `roles_permission` VALUES(126, 5, 'Default Site', 'Site', 'a:1:{i:0;s:5:"index";}');
INSERT INTO `roles_permission` VALUES(127, 5, 'Clock GMT', 'Timegmt', 'a:2:{i:0;s:5:"index";i:1;s:10:"gettimeGMT";}');
INSERT INTO `roles_permission` VALUES(128, 5, 'View Administrators Listing', 'Administrators', 'a:3:{i:0;s:5:"index";i:1;s:16:"loadDataAccounts";i:2;s:13:"loadDataRoles";}');
INSERT INTO `roles_permission` VALUES(129, 5, 'Add Account', 'Administrators', 'a:2:{i:0;s:3:"add";i:1;s:11:"saveAccount";}');
INSERT INTO `roles_permission` VALUES(130, 5, 'Edit Account', 'Administrators', 'a:2:{i:0;s:4:"edit";i:1;s:13:"updateAccount";}');
INSERT INTO `roles_permission` VALUES(131, 5, 'Delete Account', 'Administrators', 'a:2:{i:0;s:16:"preDeleteAccount";i:1;s:13:"deleteAccount";}');
INSERT INTO `roles_permission` VALUES(132, 5, 'Edit My Account', 'Myaccount', 'a:2:{i:0;s:5:"index";i:1;s:9:"takephoto";}');
INSERT INTO `roles_permission` VALUES(133, 5, 'Management Permissions Role', 'Rolepermission', 'a:2:{i:0;s:5:"index";i:1;s:8:"saveperm";}');
INSERT INTO `roles_permission` VALUES(134, 5, 'Manage Roles', 'Roles', 'a:1:{i:0;s:5:"index";}');

-- --------------------------------------------------------

--
-- Table structure for table `sales_clients`
--

CREATE TABLE `sales_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sid` (`sid`,`cid`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sales_clients`
--


-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `cost` float NOT NULL,
  `sb_saleprice` float NOT NULL,
  `ero_saleprice` float NOT NULL,
  `logo_service` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `service`
--

INSERT INTO `service` VALUES(21, 'Roadside Assistance', 200, 100, 100, 'b695486feeffc78348d132e0afb8da66.jpg', 1);
INSERT INTO `service` VALUES(22, 'Service 2', 300, 100, 200, '.', 1);
INSERT INTO `service` VALUES(23, 'USS', 12, 5, 0.12, '.', 131);
INSERT INTO `service` VALUES(24, 'UBB', 20, 10, 0.5, '.', 131);

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `id` int(2) NOT NULL DEFAULT '0',
  `site_name` text,
  `email` varchar(255) DEFAULT NULL,
  `enroll_email` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `keyword` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` VALUES(0, 'Scale Financial', 'nghiemhiep_18@yahoo.com', 'nghiemhiep_18@yahoo.com', 'ScaleFinancial', 'ScaleFinancial', NULL, NULL, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sysvals`
--

CREATE TABLE `sysvals` (
  `sysval_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sysval_title` varchar(48) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sysval_value` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`sysval_id`),
  UNIQUE KEY `sysval_title` (`sysval_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `sysvals`
--

INSERT INTO `sysvals` VALUES(1, 'Countries', 'AL|Albania\r\nDZ|Algeria\r\nAO|Angola\r\nAG|Antigua and Barbuda\r\nAM|Armenia\r\nAU|Australia\r\nAT|Austria\r\nAZ|Azerbaijan\r\nBS|Bahamas\r\nBH|Bahrain\r\nBD|Bangladesh\r\nBB|Barbados\r\nBY|Belarus\r\nBE|Belgium\r\nBZ|Belize\r\nBJ|Benin\r\nBM|Bermuda\r\nBO|Bolivia\r\nBA|Bosnia and Herzegovina\r\nBW|Botswana\r\nIO|British Indian Ocean Territory\r\nBN|Brunei\r\nBG|Bulgaria\r\nBF|Burkina Faso\r\nKH|Cambodia\r\nCM|Cameroon\r\nCA|Canada\r\nCV|Cape Verde\r\nCL|Chile\r\nCO|Colombia\r\nCR|Costa Rica\r\nCI|C&ocirc;te d''Ivoire\r\nHR|Croatia\r\nCY|Cyprus\r\nCZ|Czech Republic\r\nDK|Denmark\r\nDO|Dominican Republic\r\nEC|Ecuador\r\nSV|El Salvador\r\nGQ|Equatorial Guinea\r\nEE|Estonia\r\nFO|Faroe Islands\r\nFI|Finland\r\nFR|France\r\nGA|Gabon\r\nGE|Georgia\r\nDE|Germany\r\nGH|Ghana\r\nGI|Gibraltar\r\nGR|Greece\r\nGL|Greenland\r\nGT|Guatemala\r\nHT|Haiti\r\nHN|Honduras\r\nHK|Hong Kong\r\nHU|Hungary\r\nIS|Iceland\r\nIN|India\r\nID|Indonesia\r\nIE|Ireland\r\nIL|Israel\r\nIT|Italy\r\nJM|Jamaica\r\nJP|Japan\r\nJO|Jordan\r\nKZ|Kazakhstan\r\nKE|Kenya\r\nKW|Kuwait\r\nLA|Laos\r\nLV|Latvia\r\nLB|Lebanon\r\nLI|Liechtenstein\r\nLT|Lithuania\r\nLU|Luxembourg\r\nMK|Macedonia\r\nMY|Malaysia\r\nMV|Maldives\r\nML|Mali\r\nMT|Malta\r\nMU|Mauritius\r\nMX|Mexico\r\nMD|Moldova\r\nMC|Monaco\r\nMA|Morocco\r\nMZ|Mozambique\r\nNA|Namibia\r\nNP|Nepal\r\nAN|Netherlands Antilles\r\nNL|Netherlands\r\nNZ|New Zealand\r\nNI|Nicaragua\r\nNG|Nigeria\r\nNO|Norway\r\nOM|Oman\r\nPK|Pakistan\r\nPA|Panama\r\nPY|Paraguay\r\nPE|Peru\r\nPH|Philippines\r\nPL|Poland\r\nPT|Portugal\r\nPR|Puerto Rico\r\nQA|Qatar\r\nRO|Romania\r\nRU|Russia\r\nRW|Rwanda\r\nSM|San Marino\r\nSA|Saudi Arabia\r\nSN|Senegal\r\nCS|Serbia and Montenegro\r\nSC|Seychelles\r\nSG|Singapore\r\nSK|Slovakia\r\nSI|Slovenia\r\nZA|South Africa\r\nES|Spain\r\nLK|Sri Lanka\r\nSE|Sweden\r\nCH|Switzerland\r\nTW|Taiwan\r\nTJ|Tajikistan\r\nTZ|Tanzania\r\nTH|Thailand\r\nTG|Togo\r\nTT|Trinidad and Tobago\r\nTN|Tunisia\r\nTR|Turkey\r\nTM|Turkmenistan\r\nUG|Uganda\r\nUA|Ukraine\r\nAE|United Arab Emirates\r\nGB|United Kingdom\r\nUS|United States\r\nUY|Uruguay\r\nUZ|Uzbekistan\r\nVA|Vatican City\r\nVE|Venezuela\r\nVN|Vietnam\r\nVG|Virgin Islands, British\r\nYE|Yemen\r\nZM|Zambia\r\nZW|Zimbabwe\r\n');
INSERT INTO `sysvals` VALUES(2, 'States', 'AS|American Samoa\r\nAL|Alabama\r\nAK|Alaska\r\nAZ|Arizona\r\nAR|Arkansas\r\nCA|California\r\nCO|Colorado\r\nCT|Connecticut\r\nDE|Delaware\r\nDC|District Of Columbia\r\nFL|Florida\r\nGA|Georgia\r\nGU|Guam\r\nHI|Hawaii\r\nID|Idaho\r\nIL|Illinois\r\nIN|Indiana\r\nIA|Iowa\r\nKS|Kansas\r\nKY|Kentucky\r\nLA|Louisiana\r\nME|Maine\r\nMH|Marshall Islands\r\nMD|Maryland\r\nMA|Massachusetts\r\nMI|Michigan\r\nMN|Minnesota\r\nFM|Micronesia\r\nMS|Mississippi\r\nMO|Missouri\r\nMT|Montana\r\nNE|Nebraska\r\nNV|Nevada\r\nNH|New Hampshire\r\nNJ|New Jersey\r\nNM|New Mexico\r\nNY|New York\r\nNC|North Carolina\r\nND|North Dakota\r\nMP|Northern Mariana Islands\r\nOH|Ohio\r\nOK|Oklahoma\r\nOR|Oregon\r\nPW|Palau\r\nPA|Pennsylvania\r\nPR|Puerto Rico\r\nRI|Rhode Island\r\nSC|South Carolina\r\nSD|South Dakota\r\nTN|Tennessee\r\nTX|Texas\r\nUT|Utah\r\nVT|Vermont\r\nVA|Virginia\r\nVI|Virgin Islands\r\nWA|Washington\r\nWV|West Virginia\r\nWI|Wisconsin\r\nWY|Wyoming\r\nOT|Other...');
INSERT INTO `sysvals` VALUES(16, 'OrderEmail', '0|pvm@soleilbrite.com\n1|Bella Media Network Inc');
INSERT INTO `sysvals` VALUES(18, 'Order Status', '1|Pending\r\n2|Processing\r\n3|Completed\r\n4|Canceled');
INSERT INTO `sysvals` VALUES(19, 'Tax rates', 'a:2:{s:2:"id";s:2:"CA";s:5:"value";s:4:"9.75";}');
INSERT INTO `sysvals` VALUES(20, 'Shipping rates', 'a:3:{i:0;a:5:{s:10:"shipEnable";s:1:"1";s:9:"shipLable";s:37:"Standard ground 5 to 10 business days";s:9:"shipValue";s:3:"180";s:14:"shipamountfree";s:0:"";s:12:"shipPosition";s:1:"0";}i:1;a:5:{s:10:"shipEnable";s:1:"1";s:9:"shipLable";s:8:"Two days";s:9:"shipValue";s:2:"15";s:14:"shipamountfree";s:0:"";s:12:"shipPosition";s:1:"0";}i:2;a:5:{s:10:"shipEnable";s:1:"1";s:9:"shipLable";s:8:"Next day";s:9:"shipValue";s:2:"25";s:14:"shipamountfree";s:0:"";s:12:"shipPosition";s:1:"0";}}');
INSERT INTO `sysvals` VALUES(29, 'Cebel settings', '<p>&nbsp;</p>\n<table width=\\"250\\" height=\\"184\\" cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">Julianne Hough</span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 243px; height: 182px;\\" src=\\"/store/data/userfiles/Picture 015(6).jpg\\" alt=\\"\\" /><a href=\\"http://www.imdb.com/name/nm2584600/\\"><span style=\\"font-size: x-small;\\">www.imdb.com/name/nm2584600/</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 207px; height: 219px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">Miss USA 2008: Crystal Steward</span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 250px; height: 333px;\\" src=\\"/store/data/userfiles/Picture 002(5).jpg\\" alt=\\"\\" /><a href=\\"http://en.wikipedia.org/wiki/Crystle_Stewart\\"><span style=\\"font-size: xx-small;\\">en.wikipedia.org/wiki/Crystle_Stewart</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 205px; height: 259px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">Bridget Marquardt</span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 250px; height: 335px;\\" alt=\\"\\" src=\\"/store/data/userfiles/bridget marquardt(1).jpg\\" /><a href=\\"http://www.imdb.com/name/nm1355813/\\"><span style=\\"font-size: x-small;\\">www.imdb.com/name/nm1355</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 208px; height: 293px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">M&yacute;a</span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 250px; height: 333px;\\" alt=\\"\\" src=\\"/store/data/userfiles/Picture 039(2).jpg\\" /></p>\n            <p><a href=\\"http://en.wikipedia.org/wiki/M.I.A._%28artist%29\\"><span style=\\"font-size: xx-small;\\">http://en.wikipedia.org/wiki/M%C3%BDa</span></a><a href=\\"http://en.wikipedia.org/wiki/M.I.A._%28artist%29\\"><span style=\\"font-size: small;\\"><br />\n            </span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 207px; height: 333px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">Stacey Dash</span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 250px; height: 333px;\\" src=\\"/store/data/userfiles/Picture 091(1).jpg\\" alt=\\"\\" /><a href=\\"http://www.imdb.com/name/nm0001107/\\"><span style=\\"font-size: xx-small;\\">www.imdb.com/name/nm0001107/</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<h5>&nbsp;</h5>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 251px; height: 244px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">Estella Warren</span></span></h5>\n            <p><img align=\\"baseline\\" style=\\"width: 252px; height: 189px;\\" alt=\\"\\" src=\\"/store/data/userfiles/Picture 089(2).jpg\\" /><a href=\\"http://www.imdb.com/name/nm0005535/\\"><span style=\\"font-size: x-small;\\">www.imdb.com/name/nm0005535/</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table width=\\"250\\" cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\">\n    <tbody>\n        <tr>\n            <td style=\\"text-align: center;\\">\n            <h5><strong><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">Monique Coleman</span></span></strong></h5>\n            <p><img width=\\"250\\" height=\\"333\\" align=\\"middle\\" src=\\"/store/data/userfiles/monique coleman(6).jpg\\" alt=\\"\\" /></p>\n            <p><a href=\\"http://www.imdb.com/name/nm0170912/\\">www.imdb.com/name/nm0170912/</a></p>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 208px; height: 292px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><span style=\\"font-size: small;\\">Astrid Bryan</span></span></span></h5>\n            <p><span style=\\"font-size: small;\\"><img align=\\"middle\\" style=\\"width: 251px; height: 335px;\\" alt=\\"\\" src=\\"/store/data/userfiles/sen 067.jpg\\" /></span><span style=\\"font-size: xx-small;\\"><a href=\\"http://www.imdb.com/name/nm3055666/\\">www.imdb.com/name/nm3055666/</a></span></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 207px; height: 261px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: smaller;\\"><span style=\\"font-size: small;\\">Sydney Sweeney</span></span></span></h5>\n            <p><img align=\\"middle\\" style=\\"width: 250px; height: 335px;\\" src=\\"/store/data/userfiles/Sidney Sweeley.JPG\\" alt=\\"\\" /><a href=\\"http://www.imdb.com/name/nm2858875/\\"><span style=\\"font-size: x-small;\\">www.imdb.com/name/nm2858875/</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 200px; height: 192px;\\">\n    <thead>\n        <tr>\n            <th scope=\\"col\\">\n            <h5><span style=\\"font-size: smaller;\\"><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"font-size: small;\\">Sianoa Smit-McPhee</span></strong></span></span></h5>\n            <p><strong><img align=\\"middle\\" style=\\"width: 251px; height: 188px;\\" alt=\\"\\" src=\\"/store/data/userfiles/sen 075.jpg\\" /></strong><a href=\\"http://www.imdb.com/name/nm1873154/\\"><span style=\\"font-size: x-small;\\">www.imdb.com/name/nm1873154/</span></a></p>\n            </th>\n        </tr>\n    </thead>\n    <tbody>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table width=\\"250\\" cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\">\n    <tbody>\n        <tr>\n            <td>\n            <h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; George Blodwell</h4>\n            <p><img width=\\"250\\" height=\\"335\\" align=\\"middle\\" alt=\\"\\" src=\\"/store/data/userfiles/george blodwell.jpg\\" /></p>\n            <p><strong><a href=\\"http://www.georgeblodwell.com/\\">&nbsp;www.georgeblodwell.com/</a></strong></p>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 358px; height: 610px;\\" herself.=\\"\\" for=\\"\\" dresses=\\"\\" four=\\"\\" and=\\"\\" dress=\\"\\" sistera=\\"\\" her=\\"\\" bought=\\"\\" hough=\\"\\" show=\\"\\" fashion=\\"\\" laugh-filled=\\"\\" private=\\"\\" an=\\"\\" on=\\"\\" putting=\\"\\" were=\\"\\" they=\\"\\" to=\\"\\" according=\\"\\" dresses.=\\"\\" trying=\\"\\" handbagsand=\\"\\" jewelries=\\"\\" the=\\"\\" checking=\\"\\" right=\\"\\" dove=\\"\\" sister=\\"\\" la.=\\"\\" in=\\"\\" boutique=\\"\\" sen=\\"\\" at=\\"\\" with=\\"\\" shopping=\\"\\" katherine=\\"\\" took=\\"\\" she=\\"\\" season=\\"\\" award=\\"\\" tiem=\\"\\" just=\\"\\" wardrope=\\"\\" updating=\\"\\" hough.=\\"\\" julianne=\\"\\" summary=\\"Press - People.com: \\">\n    <tbody>\n        <tr>\n            <td>\n            <h5><img alt=\\"\\" src=\\"/store/data/userfiles/scan0001(3).jpg\\" style=\\"width: 350px; height: 496px;\\" />PRESS-<br />\n            People.com: &quot;Julianne Hough. updating her wardrope just in  tiem forthe&nbsp;&nbsp; Award Season - and she took her sister Katherine shopping  with her at Sen Boutique in LA. The sister dove right in, checking the  jewelries and handbagsand trying on dresses. According to an onlooker,  they were putting on &quot;an impromptu, private laugh-filled fashion show&quot;.  Hough bought her&nbsp;&nbsp;&nbsp; sistera dress and four dresses for herself.&quot;</h5>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"width: 351px; height: 352px;\\">\n    <tbody>\n        <tr>\n            <td>\n            <pre><img src=\\"/store/data/userfiles/bags 022.jpg\\" alt=\\"\\" style=\\"width: 350px; height: 260px;\\" />\n\nPRESS\nVIP Scene, page 59: &quot;Courtney Love shopping \nat Sen Boutique in Los Angeles&quot;</pre>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table width=\\"350\\" height=\\"29\\" cellspacing=\\"1\\" cellpadding=\\"1\\" border=\\"1\\" align=\\"center\\" style=\\"\\">\n    <tbody>\n        <tr>\n            <td>\n            <p><img width=\\"350\\" height=\\"261\\" src=\\"/store/data/userfiles/bags 023(1).jpg\\" alt=\\"\\" /></p>\n            <p>PRESS<br />\n            Globe Magz - Making The Scene, page 11: &quot; Courtney Love  shopping for handbag at Sen Boutique in LA...&quot;</p>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>');
INSERT INTO `sysvals` VALUES(30, 'Service settings', '<table cellspacing=\\"8\\" cellpadding=\\"8\\" border=\\"8\\" align=\\"center\\" style=\\"width: 284px; height: 44px;\\">\n    <caption>          <br />\n    </caption>\n    <tbody>\n        <tr>\n            <td>\n            <h2><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">&nbsp;&nbsp;&nbsp;&nbsp; SEN Service and Policy</span></span></h2>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<h2>&nbsp;</h2>\n<p><strong>\n<p><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: larger;\\"> CONTACT&nbsp;&nbsp;SEN</span></span></span></span></p>\n</strong></p>\n<ul>\n    <li><span style=\\"color: rgb(128, 128, 128);\\"><strong> </strong></span><span style=\\"font-size: medium;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Phone: 323-782-4320</span></span></strong></span></li>\n    <li>\n    <h5><span style=\\"font-size: medium;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Email:   </span></span></strong></span><a href=\\"mailto:service@sen-collection.com\\"><span style=\\"font-size: medium;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">service@sen-collection.com</span></span></strong></span></a></h5>\n    </li>\n    <li>\n    <h5><span style=\\"font-size: medium;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Mail: 8111 Melrose Ave. Los Angeles, CA&nbsp;90046</span></span></span></h5>\n    </li>\n</ul>\n<table width=\\"800\\" cellspacing=\\"5\\" cellpadding=\\"5\\" border=\\"5\\" align=\\"left\\">\n    <tbody>\n        <tr>\n            <td>\n            <h3><em><span style=\\"font-size: larger;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">Online     Service</span></span></span></em><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\">                           <span style=\\"color: rgb(128, 128, 128);\\">              </span><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span></span>                                                                  <span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span></span><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><strong>              </strong></span></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><strong>\n            <p><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span></span><span style=\\"color: rgb(128, 128, 128);\\">\n            <h4>&bull;    Purchase<span style=\\"color: rgb(128, 128, 128);\\"> </span></h4>\n            </span></p>\n            </strong></span>\n            <h3><strong>             </strong><span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span><strong><span style=\\"font-family: Times New Roman;\\">                           </span></strong><span style=\\"color: rgb(128, 128, 128);\\">After you have added an item to      your Shopping  Cart, you will immediately be able to view your items   and    start the  checkout process. If you are a registered customer,   provide    your  e-mail address and password and click Standard Checkout.   If you  are    not a registered customer, provide your Shipping Address   and  Billing    Address information. Click Continue With  Checkout.Choose  where  to  ship   your order and how you would like it  to be shipped.  Enter  your  payment   information. You may use a credit  card or Gift  Card.  Click  Continue   Checkout. Review your order before  you click  Complete   Your Order. We  will  e-mail you as items in your  order ship,  and as  we  obtain updates  on the  status of your order.  SEN is required by  law to charge all local sales tax on  orders shipped  within the state  of California.</span></h3>\n            </span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"font-family: Times New Roman;\\">             </span></strong></span>                                        <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\">             </span></strong></span></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <p><span style=\\"color: rgb(128, 128, 128);\\">\n            <h4>&bull; Shipping</h4>\n            </span></p>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"font-family: Times New Roman;\\"><strong>             </strong></span>                           <span style=\\"font-family: Times New Roman;\\"><strong>                           </strong></span></h3>\n            <span style=\\"font-family: Times New Roman;\\"><strong>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </strong></span>\n            <h3><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>SEN  offers  FREE Ground shipping     for orders shipp to the United States  over 200  dollars. SEN may choose  to    use USPS as the shipping  method.&nbsp;  International Orders over 6 pounds    may incur an  additional  cost. To  ensure the safe delivery of your    package, we require  an  adult  signature for all deliveries. You will    also receive an e-mail   with a  tracking number so that you may track    your package. All  shipping   charges are non-refundable.</strong></span></span></h3>\n            <h3><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>&nbsp;&nbsp;&nbsp;&nbsp; </strong></span></span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">Shipping Options:</span></span></span><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            </span></h3>\n            <p><span style=\\"font-size: smaller;\\">             </span></p>\n            <span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </span><span style=\\"font-size: smaller;\\">             </span>\n            <h5><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\">                          </span></span></h5>\n            <ul>\n                <li>\n                <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Standard Shipping  is $10 - Take 3   to 8 days to arrive</span>&nbsp;</span></span></h5>\n                </li>\n                <li>\n                <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"> </span></span><span style=\\"font-size: larger;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">Two Business Day is $15</span></span></span></h5>\n                </li>\n                <li>\n                <h5><span style=\\"font-size: larger;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Next Business Day is  $25</span></span></span></h5>\n                </li>\n                <li>\n                <h5><span style=\\"font-size: larger;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">Saturday  Delivery is  $40 - Orders need to be submitted by 3pm,  Friday, Pacific  Time </span></span></span><span style=\\"font-size: larger;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">&nbsp;</span></span></span></h5>\n                </li>\n                <li>\n                <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">International Shipping is $45</span></span></span><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n                </span></span></h5>\n                </li>\n            </ul>\n            <span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>&bull;</strong></span>    <span style=\\"font-family: Times New Roman;\\"><strong>Return/Exchange</strong></span>&nbsp;                                                     </span></span><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            </span></span></h5>\n            </span>                          <span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </span><span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            <span style=\\"color: rgb(128, 128, 128);\\">             </span></span><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: larger;\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">SEN  will issue a full refund for     non-sale merchandise within 10 days from date  of delivery, provided    the  clothing is unworn, unwashed, no defects with all original tags  and   SEN Sealed Tag are attached - no exception. If you receive a   defected   merchandise, you must notify SEN  within 48 hours of  receiving the    product to be eligible for an exchange  or refund.  Please send a    return/exchange&nbsp; item with a tracking number  as to  ensure the package   does not  get lost in the mail. If merchandises are  returned/echange without   meeting the above requirements, they will  not be accepted  and will be shipped   back to customers at their cost.  Refunds will be  credited to the    original form of payment used for  the order. SEN can  only accept   returns  or exchanges with origional  invoice - no exception. SEN can   not waive  or refund shipping charges  for returned or  exchange   merchandise. If an order is returned, SEN  will credit the order  back   minus the cost of shipping. If 3 or more  items are  returned from  any   particular order, a restocking fee of  20% will apply.  Allow 1&mdash;2    billing cycles for the refund to appear on  your credit card  statement.    Sales are qualify for exchange or store  credits only.</span> F<span style=\\"font-family: Times New Roman;\\">or  exchange, please ship  the item(s) back with the origional    invoice  and make a copy for your  records. SEN will ship the item you    want to  receive in exchange, to the  same &quot;Ship To&quot; address on the    packing  slip. Please send online returns/exchanges to the above address.</span></span></h5>\n            </span></span></td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<table width=\\"800\\" cellspacing=\\"5\\" cellpadding=\\"5\\" border=\\"5\\" align=\\"left\\">\n    <tbody>\n        <tr>\n            <td>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            <span style=\\"color: rgb(128, 128, 128);\\">\n            <h3><span style=\\"font-size: medium;\\"><em><span style=\\"font-family: Times New Roman;\\">In Store Service</span></em></span></h3>\n            </span><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\">\n            <h4><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">At SEN, you have access to our personal  stylist who can help... whether you shop for a special occasion or a  gift for someone else...whether you shop for one item or just looking.  We are available to make your shopping at SEN a GREAT experience and  pressure free!</span></span></h4>\n            </span></span><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span>             </span>\n            <h4><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span></span></h4>\n            <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">SEN  will issue a full refund within 7 days or exchange within 14 days from  date of purchase for non-sale merchandise, provide the&nbsp; merchandise is  unworn, unwashed, no defects with original invoice, tag and SEN Sealed  Tag attach (no exception). All sales are final.</span></span></span></h5>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<h4>&nbsp;</h4>\n<h4>&nbsp;</h4>\n<h4>&nbsp;</h4>\n<h4>&nbsp;</h4>\n<h4>&nbsp;</h4>\n<table width=\\"800\\" cellspacing=\\"5\\" cellpadding=\\"5\\" border=\\"5\\" align=\\"left\\">\n    <tbody>\n        <tr>\n            <td>\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: larger;\\">             </span></span></span></h5>\n            <p><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: larger;\\">\n            <h4><em><span style=\\"font-size: medium;\\">Term   of Use</span></em><span style=\\"font-size: small;\\"><br />\n            </span></h4>\n            </span><span style=\\"font-size: small;\\">             </span></span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span>             </span>                           <span style=\\"font-family: Times New Roman;\\">             </span></span></p>\n            <p><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: larger;\\"><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">\n            <h5><span style=\\"font-family: Times New Roman;\\">                             	</span></h5>\n            </span></span></span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span>             </span>                           <span style=\\"font-family: Times New Roman;\\">             </span></span></p>\n            <p><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span></span></h5>\n            </span><span style=\\"font-size: small;\\">             </span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">             <strong>Welcome   to SEN\\''s  website!</strong></span></span></span><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: smaller;\\"><br />\n            </span></span></p>\n            <p><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"color: rgb(128, 128, 128);\\">             </span></h5>\n            </span></span></p>\n            <h5><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: smaller;\\"><span style=\\"color: rgb(128, 128, 128);\\">             </span></span><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><strong>To    use this website you are  agreeing to comply  with  and be bound by  the   following terms of use, which  together  with our privacy policy  govern   SEN\\''s relationship  with you in  relation to this websi</strong></span></span><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: smaller;\\"><strong>te. </strong></span></span></span></h5>\n            <h5><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\">SEN   reserves the right not to  process  orders received for any   reason as   solely determined by SEN  Collection Inc. </span></strong></span><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>By    submitting  an  order with SEN, you accept   our Term of  Use. Should   you  not  agree with  these terms, please  do  not submit your  order.</strong></span></span></span><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong><br />\n            <br />\n            </strong></span></span><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>If the products displayed are no longer available at  the      time of your  order, SEN will inform you of our stocking  position      within 48 hours from submission of your order. SEN&nbsp;will also  refund  the     total purchase amount if the product is  no longer available. <br />\n            <br />\n            SEN&nbsp;does not responsiple for any defect that&nbsp; not caused by     SEN  (such  as abuse, or misuse, or vandalism),  nor to merchandise      which  has been altered or improperly repaired by third party. To     qualify for return or exchange, the customer  must notify SEN in 48     hours upon recieving the  defect merchandise.<br />\n            <br />\n            SEN tries to accurately present and portray  all products       offered. While we try to portray our product as  accurately as    possible,    products viewed on our website may vary  slightly in    comparison to  the   merchandise received in terms of image  and color    due to  manufacturing   techniques or due to differences caused  by    computer   monitors or internet  browsers used. </strong></span></span></span></h5>\n            <h5><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><strong>SEN&nbsp;also reserves  the    right to   change prices without prior notice.</strong></span></span></span><span style=\\"font-size: small;\\"><br />\n            </span></h5>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<table width=\\"800\\" height=\\"515\\" cellspacing=\\"5\\" cellpadding=\\"5\\" border=\\"5\\" align=\\"left\\" style=\\"\\">\n    <tbody>\n        <tr>\n            <td>\n            <h5><span style=\\"font-size: small;\\">                          </span><span style=\\"font-size: smaller;\\">             </span></h5>\n            <span style=\\"font-size: smaller;\\">\n            <h5><span style=\\"font-size: medium;\\"><em><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><strong>Our Commitment to Your Privacy</strong></span></span></em></span></h5>\n            <h3><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: medium;\\"><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\">Your privacy is important  to us. To  better  protect your privacy, SEN provides this notice  explaining our   online information practices and the choices you can make about  the   way your information is collected and used. To make this notice easy to    find, we make it available on our homepage and at every point where   personally  identifiable information may be requested.&nbsp; </span></span></span></span></h3>\n            <span style=\\"font-size: smaller;\\">             </span></span>                                                    <span style=\\"font-family: Times New Roman;\\">\n            <h5><span style=\\"font-size: small;\\">             </span></h5>\n            </span>\n            <p><span style=\\"font-size: medium;\\"><em><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\">The Information  We Collect</span></strong></span></em></span></p>\n            <p><span style=\\"font-family: Times New Roman;\\"><strong><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\">This notice applies to all  information collected or  submitted on the SEN website. The types  of personal  information collected at these pages are:&nbsp;Name,&nbsp;               Address, Email address, Phone number, Credit/Debit Card   Information, Credit/Debit Card  Expiration Date,  Credit/Debit Card Security  Code.</span></span></strong></span></p>\n            <h5><span style=\\"font-size: medium;\\"><em><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">The Way We Use  Information</span></span></em></span><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            </span></span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            SEN uses the  information you provide about  yourself when placing an order only to complete  that order. We do not  share this information with outside parties except to the  extent  necessary to complete that order.</span></span></span></h5>\n            <span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">\n            <h4><span style=\\"color: rgb(128, 128, 128);\\">SEN uses the  information you provide  about someone else when placing an order only to ship  the product and  to confirm delivery. We do not share this information with  outside  parties except to the extent necessary to complete that order. <br />\n            </span></h4>\n            </span></span><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">                          </span></span>                                                                                                        <span style=\\"font-size: larger;\\"><span style=\\"font-family: Times New Roman;\\">\n            <h5><span style=\\"font-size: small;\\"><strong><span style=\\"color: rgb(128, 128, 128);\\">SEN uses return  email addresses to  answer the email we receive. Such addresses are not used for  any other  purpose and are not shared with outside parties.&nbsp; </span></strong></span></h5>\n            </span></span>\n            <h5><span style=\\"font-size: larger;\\"><strong><span style=\\"font-family: Times New Roman;\\">             </span><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: small;\\">You can register with our  website if you would like to  receive updates on our new/upcoming products.  Information you submit  on our website will not be used for this purpose unless  you fill out  the registration form.&nbsp;</span></span></span></strong></span></h5>\n            <span style=\\"font-family: Times New Roman;\\">\n            <h5><span style=\\"font-size: small;\\">             </span></h5>\n            </span>                          <span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">\n            <h4><span style=\\"color: rgb(128, 128, 128);\\">SEN uses  non-identifying and aggregate  information to better design our website and to  share with  advertisers. We may tell an advertiser the number of   individuals visited a certain area on our website, or the number of people filled out our registration form, but we  would not disclose  anything that could be used to identify those  individuals. <br />\n            </span></h4>\n            </span></span>                          <span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">\n            <h5><span style=\\"font-size: larger;\\"><span style=\\"color: rgb(128, 128, 128);\\">Finally, SEN  never uses or shares the  personally identifiable information provided to us  online in ways  unrelated to the ones described above without also providing you  an  opportunity to opt-out or otherwise prohibit such unrelated uses.&nbsp; </span></span></h5>\n            </span></span>\n            <h5><em><span style=\\"font-size: medium;\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\"><strong>Our Commitment To Data  Security</strong></span></span></span></em><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            <br />\n            To prevent unauthorized  access, maintain data accuracy, and  ensure the correct use of information, we have  put in  place appropriate physical, electronic, and managerial procedures  to safeguard and secure  the information we collect online.</span></span></span></h5>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5>&nbsp;</h5>\n<h5><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\"><em><br />\n</em></span></span></span></h5>\n<h5>&nbsp;</h5>\n<p>&nbsp;</p>');
INSERT INTO `sysvals` VALUES(31, 'About settings', '<table cellspacing=\\"8\\" cellpadding=\\"8\\" border=\\"8\\" align=\\"left\\" style=\\"width: 800px; height: 991px;\\">\n    <tbody>\n        <tr>\n            <td>\n            <h3><img width=\\"200\\" height=\\"296\\" alt=\\"\\" longdesc=\\"undefined\\" src=\\"/store/data/userfiles/lotus flower(4).jpg\\" />&nbsp;&nbsp;&nbsp;&nbsp;<span style=\\"font-size: small;\\"> <span style=\\"font-family: Times New Roman;\\"><span style=\\"color: rgb(128, 128, 128);\\">ABOUT &nbsp;SEN</span></span></span><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\"><span style=\\"color: rgb(128, 128, 128);\\"><br />\n            <br />\n            &quot;SEN&quot; means Lotus Flower in Vietnamese. The Lotus Flower is a remarkably  beautiful, eye-catching, and distinctive flower. It &nbsp;starts as a small  bud down at the bottom of a pond in the mud and filth, and then slowly  grows toward the water\\''s surface, continually moving toward the light.  Once it comes to the surface of the water Lotus Flower begins to blossom  with remarkable beauty and elegance. Untouched by adulteration, Lotus  Flower therefore has strong symbolic ties to many Asian cultures. It  represents an overcoming of challenges, renewal, sophistication, and  also symbolizes the purity of heart and mind.<br />\n            <br />\n            SEN Boutique was founded in January 2010, during the most challenging  time in United States economy since the Great Depression. Located in the  high-end fashion district of Melrose Avenue, SEN competes among other  well-known designers and well-established boutiques. Like the Lotus  Flower that raises above the mud to become pure and elegant, SEN has  quickly risen above and made its presence known in Los Angeles in the  toughest economy. It offers a very unique, high quality collection of  women\\''s clothing and accessories which have been worn by many  celebrities and featured at many red carpet events.<br />\n            <br />\n            Before SEN, women who wanted personal assistance and unique designs that  were ahead of the curve were left with limited choices. They had to  choose between mass-produced clothing or costly Couture. At SEN, savvy,  sophisticated ladies can find the most imaginative beautiful clothing  and accessories that are unique, sexy and classy without the outrageous  price. Even more importantly, they have SEN\\''s complete complimentary  in-store services and personal stylist for the complete look. The buying  and designing team at SEN are working extra hard to continuously bring  in a broad variety of styles for all ages, concentrating on cocktail  dresses, evening wear, accessories, handbags for contemporary, urbane  women.<br />\n            <br />\n            Come to SEN, if you are one of the savvy, sophisticated ladies, who want  unique, high quality clothing and accessories without paying exorbitant  prices for designer one-of-a-kind garments. We offer complimentary  consultation from a fashion expert and exceptional customer service.<br />\n            <span style=\\"font-size: medium;\\"><em><br />\n            SEN Service:</em></span><br />\n            <br />\n            Our staff are carefully selected for their fashion knowledge and  experience. They receive on-going education to keep them current with  the latest trends. We pride ourselves on delivering great service and  fashion advice in a warm and accommodating manner. Whether you prefer  hands-on help or browse alone, pressure-free, every customer is regarded  as a VIP. So while our address and products may be posh, our service is  completely attitude-free.<br />\n            <em><span style=\\"font-size: medium;\\"><br />\n            SEN Collection:</span></em><br />\n            <br />\n            SEN showcases famous designer names in accessories as well as our sexy,  classy, and unique Private Collection clothing. Our buyers and designers  travel around the world scouting for outstanding and emerging fashion  trends. At SEN, we find what\\''s best in the Fashion World and you pick  what\\''s best for you!<br />\n            </span></span></span></h3>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<table width=\\"800\\" cellspacing=\\"8\\" cellpadding=\\"8\\" border=\\"8\\" align=\\"left\\">\n    <tbody>\n        <tr>\n            <td>\n            <h4><img width=\\"200\\" height=\\"200\\" alt=\\"\\" longdesc=\\"undefined\\" src=\\"/store/data/userfiles/Trang Phung(5).jpg\\" />&nbsp;&nbsp;&nbsp; <span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: small;\\">ABOUT THE FOUNDER<br />\n            <br />\n            Trang Phung was born and raised in Saigon, Vietnam, during the height of  the Vietnam War. Trang\\''s mother used all of the family savings and  arranged for her to escape Vietnam on a small fishing boat. At the age  of seventeen, penniless and alone, she left her homeland as a part of  the &ldquo;Boat People&rdquo; exodus. Surviving the seas, storms, and struggling  through a year in the Indonesia Refugee Camp, she finally made her way  to The United States.<br />\n            <br />\n            Despite many life challenges, Trang became successful in several careers  and later helped her family to immigrate to The United States. One of  Trang\\''s desires was to apply her creative talent in what she always  loves: the milieu of fashion! Trang is always known for being a  trendsetter with her exquisite taste and fashion sense: sexy yet elegant  with an edge as well as her creative talent. With the ability and  passion for the Art of Fashion, Trang set out to provide a solution for  ladies like herself. She has panache!<br />\n            <br />\n            In January 2010, amidst the crippling economic recession, Trang resigned  from her prestigious position as a Regional Vice President for Pension  Sales with Nationwide, a Fortune 500 Company, to pursue her passion and  opened SEN in the high end fashion district of Melrose Avenue in Los  Angeles. &ldquo;SEN&rdquo; means lotus flower in Vietnamese. Trang explained  &ldquo;Besides its striking beauty, the lotus flower symbolizes purity of the  heart and mind; it also represents overcoming challenges, renewal and  sophistication which are meaningful to me in many ways.&rdquo;<br />\n            <br />\n            With an intensive background and success in Wealth Management, Trang  believed opening a business in a tough economy had many advantages. She  reasoned that &ldquo;when working in the Wall Street arena, I was successful  by buying more shares for clients when the Stock Market shifted downward  and more people were selling. With the same strategy, I opened a  business when competitors are down and vendors and landlords are  negotiable and willing to accept our terms. But most importantly, people  who love fashion will always look for great places to shop, where they  find the elegant and unique look without paying high prices!&rdquo;<br />\n            <br />\n            Shortly after the opening of SEN, Trang immediately gathered a team of  upcoming talents to launch the Private Collection for SEN. Regarding to  SEN Private Collection, Trang said &ldquo;I wouldn\\''t want to compete with  stores that have mass produced clothing, I would rather offer my clients  the WOW-factor of SEN Private Collection that is sexy, classy, high  quality and yet affordable.&rdquo;<br />\n            <br />\n            The outstanding popularity of SEN in such a short time and the toughest  economy has again proven Trang\\''s ability to succeed. With the recent  success, she plans to open SEN in other locations in the near future.<br />\n            <br />\n            Giving back to the community is important to Trang, especially helping  the orphans in third world countries. &nbsp;Beside committing her time to  help, Trang is donating 5% of SEN\\''s profits to the International Kids  Fund (IKF), a non-profit foundation, created by Jackson Memorial  Hospital in Florida. IFK is dedicated to saving the lives of the poor,  critically ill children around the world by giving them immediate access  to essential medical treatments.<br />\n            <br />\n            Trang is fashion forward. Her collection is relentlessly hip and  effortlessly striking!</span></span></span></h4>\n            </td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>');
INSERT INTO `sysvals` VALUES(32, 'Contact settings', '<p>&nbsp;</p>\n<h4>&nbsp;<img vspace=\\"10\\" hspace=\\"10\\" border=\\"10\\" align=\\"left\\" style=\\"width: 300px; height: 374px;\\" alt=\\"\\" src=\\"/store/data/userfiles/sen pic 3(3).jpg\\" longdesc=\\"http://www.sen-collection.com/store/undefined\\" /><span style=\\"color: rgb(128, 128, 128);\\"> &nbsp;&nbsp;</span><span style=\\"color: rgb(128, 128, 128);\\"><br />\n</span></h4>\n<p>&nbsp;</p>\n<p><span style=\\"color: rgb(128, 128, 128);\\">\n<h3><span style=\\"font-family: Times New Roman;\\"><strong>&nbsp;&nbsp;&nbsp;&nbsp; </strong><span style=\\"font-size: medium;\\"><em>Got  something on your mind? Please contact SEN:</em></span></span></h3>\n</span></p>\n<p><span style=\\"color: rgb(128, 128, 128);\\"> </span><span style=\\"color: rgb(128, 128, 128);\\">\n<h4 style=\\"margin-left: 40px; text-align: justify;\\"><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phone: 323-782-4320</span></span></h4>\n</span><span style=\\"color: rgb(128, 128, 128);\\"> </span></p>\n<h4><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Email:&nbsp; </span></span><span style=\\"color: rgb(128, 128, 128);\\"><span style=\\"font-family: Times New Roman;\\"><a href=\\"mailto:service@sen-collection.com\\"><span style=\\"color: rgb(128, 128, 128);\\">service@sen-collection.com</span></a></span></span></h4>\n<p><span style=\\"color: rgb(128, 128, 128);\\">\n<h4><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mail:&nbsp; 8111 Melrose Ave. Los Angeles CA 90046 - Attn: Service Dept.</span></h4>\n</span></p>\n<p><span style=\\"color: rgb(128, 128, 128);\\">\n<h3><span style=\\"font-family: Times New Roman;\\"><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </em><em><span style=\\"font-size: medium;\\">We commit to respond to you in a timely maner!</span></em><br />\n</span></h3>\n</span></p>\n<p><span style=\\"color: rgb(128, 128, 128);\\">\n<h4><span style=\\"font-size: small;\\"><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span style=\\"font-size: medium;\\"><em><span style=\\"font-family: Times New Roman;\\">Or visit SEN for In store Service at:</span></em></span><span style=\\"font-family: Times New Roman;\\"><span style=\\"font-size: 18px;\\"><br />\n</span></span></h4>\n</span></p>\n<p><span style=\\"color: rgb(128, 128, 128);\\">\n<h4><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 8111 Melrose Ave.</span>&nbsp;</h4>\n<h4>&nbsp;&nbsp;&nbsp;&nbsp; <span style=\\"font-family: Times New Roman;\\">Los Angeles, CA&nbsp; 90046</span></h4>\n</span></p>\n<h4><span style=\\"color: rgb(128, 128, 128);\\"><strong><span style=\\"font-family: Times New Roman;\\">&nbsp;&nbsp;&nbsp;&nbsp; THANK&nbsp;&nbsp;YOU!</span></strong></span></h4>\n<p><span style=\\"color: rgb(128, 128, 128);\\">&nbsp;</span></p>');
INSERT INTO `sysvals` VALUES(33, 'Display type', '0|Text field\r\n1|Select box\r\n2|Radio buttons\r\n3|Checkboxes\r\n4|Textarea');
INSERT INTO `sysvals` VALUES(38, 'Venue Location Attach Pictures', '0|Front\n1|Center\n2|Side view left\n3|Side view right');
INSERT INTO `sysvals` VALUES(39, 'Product Unit', 'lb|Pounds\nkg|Kilograms\noz|Ounces\ng|Grams');
INSERT INTO `sysvals` VALUES(35, 'Venue Location Services', 'NailSalon|NailSalon\nHair&Nail|Hair&Nail');
INSERT INTO `sysvals` VALUES(40, 'Authorize', '0|asdfa sdfasdf\n1|asdfasdf\n2|0\n3|1');
INSERT INTO `sysvals` VALUES(41, 'Paypal', '0|admin\n1|123456\n2|\n3|0');
INSERT INTO `sysvals` VALUES(42, '__UPS_Service__', '03<ho^^slDbtSS695hG>01<ho^^slDbtSS695hG>13<ho^^slDbtSS695hG>14<ho^^slDbtSS695hG>02<ho^^slDbtSS695hG>59<ho^^slDbtSS695hG>12<ho^^slDbtSS695hG>11<ho^^slDbtSS695hG>07<ho^^slDbtSS695hG>08<ho^^slDbtSS695hG>54<ho^^slDbtSS695hG>65');
INSERT INTO `sysvals` VALUES(43, 'UPS Access Key', '');
INSERT INTO `sysvals` VALUES(44, 'UPS Shipper', '');
INSERT INTO `sysvals` VALUES(45, 'UPS user ID', '');
INSERT INTO `sysvals` VALUES(46, 'UPS Password', '');
INSERT INTO `sysvals` VALUES(47, 'Server mode', '1');
INSERT INTO `sysvals` VALUES(48, 'Shipping method', 'a:2:{i:0;a:3:{s:5:"title";s:40:"By countries/states module (fixed rates)";s:11:"description";s:63:"Allows to define unique shipping rates for countries and states";s:4:"page";s:8:"manually";}i:1;a:3:{s:5:"title";s:3:"UPS";s:11:"description";s:127:"UPS OnLine Tools. Real-time shipping rate calculations. Your need to have an account with www.ups.com to make this module work.";s:4:"page";s:3:"ups";}}');
INSERT INTO `sysvals` VALUES(49, 'dressing_room', 'a:0:{}');
INSERT INTO `sysvals` VALUES(50, 'Receive E-mail Not Connect', '');
INSERT INTO `sysvals` VALUES(51, 'Time not connect define', 's:1:"5";');
INSERT INTO `sysvals` VALUES(52, 'SMS Not Connect Note', 's:8:"Testing.";');
INSERT INTO `sysvals` VALUES(53, 'Time not connect dv', 's:4:"3600";');
INSERT INTO `sysvals` VALUES(55, 'sale_rep_setting', 'a:13:{s:17:"Minimum_purchased";s:3:"0.5";s:19:"limit_time_purchase";s:1:"7";s:14:"units_purchase";s:1:"1";s:15:"Number_of_level";s:1:"7";s:14:"Direct_sponsor";s:1:"5";s:12:"To_be_active";s:1:"7";s:12:"units_active";s:1:"1";s:10:"date_apply";s:10:"08/21/2013";s:15:"Minimum_payment";s:1:"0";s:18:"limit_time_payment";s:1:"0";s:13:"units_payment";s:1:"1";s:21:"time_purchase_actived";s:1:"3";s:19:"units_time_purchase";s:2:"30";}');
INSERT INTO `sysvals` VALUES(56, '_themes_', 'TRP');

-- --------------------------------------------------------

--
-- Table structure for table `tblcities`
--

CREATE TABLE `tblcities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `city` varchar(100) NOT NULL,
  `idcountry` int(10) NOT NULL,
  `statecode` varchar(10) NOT NULL,
  `rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1785 ;

--
-- Dumping data for table `tblcities`
--

INSERT INTO `tblcities` VALUES(1, 'Acampo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(2, 'Acton', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(3, 'Adelaida', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(4, 'Adelanto', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(5, 'Adin', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(6, 'Agoura', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(7, 'Agoura Hills', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(8, 'Agua Caliente', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(9, 'Agua Caliente Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(10, 'Agua Dulce', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(11, 'Aguanga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(12, 'Ahwahnee', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(13, 'Al Tahoe', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(14, 'Alameda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(15, 'Alamo', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(16, 'Albany', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(17, 'Alberhill Lake Elsinore', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(18, 'Albion', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(19, 'Alderpoint', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(20, 'Alhambra', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(21, 'Aliso Viejo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(22, 'Alleghany', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(23, 'Almaden Valley', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(24, 'Almanor', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(25, 'Almondale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(26, 'Alondra', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(27, 'Alpaugh', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(28, 'Alpine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(29, 'Alta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(30, 'Alta Loma Rancho Cucamonga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(31, 'Altadena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(32, 'Altaville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(33, 'Alton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(34, 'Alturas', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(35, 'Alviso San Jose', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(36, 'Amador City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(37, 'Amargosa Death Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(38, 'Amboy', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(39, 'American Canyon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(40, 'Anaheim', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(41, 'Anderson', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(42, 'Angels Camp', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(43, 'Angelus Oaks', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(44, 'Angwin', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(45, 'Annapolis', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(46, 'Antelope', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(47, 'Antelope Acres', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(48, 'Antioch', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(49, 'Anza', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(50, 'Apple Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(51, 'Applegate', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(52, 'Aptos', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(53, 'Arbuckle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(54, 'Arcadia', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(55, 'Arcata', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(56, 'Argus', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(57, 'Arleta Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(58, 'Arlington Riverside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(59, 'Armona', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(60, 'Army Terminal', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(61, 'Arnold', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(62, 'Aromas', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(63, 'Arrowbear Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(64, 'Arrowhead Highlands', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(65, 'Arroyo Grande', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(66, 'Artesia', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(67, 'Artois', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(68, 'Arvin', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(69, 'Ashland', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(70, 'Asti', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(71, 'Atascadero', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(72, 'Athens', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(73, 'Atherton', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(74, 'Atwater', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(75, 'Atwood', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(76, 'Auberry', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(77, 'Auburn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(78, 'Avalon', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(79, 'Avenal', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(80, 'Avery', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(81, 'Avila Beach', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(82, 'Azusa', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(83, 'Badger', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(84, 'Bailey', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(85, 'Baker', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(86, 'Bakersfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(87, 'Balboa Newport Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(88, 'Balboa Island Newport Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(89, 'Balboa Park San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(90, 'Baldwin Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(91, 'Ballard', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(92, 'Ballico', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(93, 'Ballroad', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(94, 'Bangor', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(95, 'Banning', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(96, 'Banta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(97, 'Bard', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(98, 'Barrington', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(99, 'Barstow', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(100, 'Bartlett', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(101, 'Barton', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(102, 'Base Line', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(103, 'Bass Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(104, 'Bassett', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(105, 'Baxter', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(106, 'Bay Point', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(107, 'Bayside', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(108, 'Baywood Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(109, 'Beale AFB', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(110, 'Bear River Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(111, 'Bear Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(112, 'Bear Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(113, 'Beaumont', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(114, 'Beckwourth', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(115, 'Bel Air Estates', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(116, 'Belden', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(117, 'Bell', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(118, 'Bell Gardens', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(119, 'Bella Vista', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(120, 'Bellflower', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(121, 'Belmont', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(122, 'Belvedere', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(123, 'Ben Lomond', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(124, 'Benicia', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(125, 'Benton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(126, 'Berkeley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(127, 'Bermuda Dunes', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(128, 'Berry Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(129, 'Bethel Island', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(130, 'Betteravia', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(131, 'Beverly Hills', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(132, 'Bieber', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(133, 'Big Bar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(134, 'Big Basin', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(135, 'Big Bear City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(136, 'Big Bear Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(137, 'Big Bend', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(138, 'Big Creek', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(139, 'Big Oak Flat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(140, 'Big Pine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(141, 'Big River', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(142, 'Big Sur', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(143, 'Biggs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(144, 'Bijou', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(145, 'Biola', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(146, 'Biola College La Mirada', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(147, 'Birds Landing', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(148, 'Bishop', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(149, 'Black Hawk', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(150, 'Blairsden', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(151, 'Blocksburg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(152, 'Bloomington', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(153, 'Blossom Hill', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(154, 'Blossom Valley', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(155, 'Blue Jay', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(156, 'Blue Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(157, 'Blythe', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(158, 'Bodega', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(159, 'Bodega Bay', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(160, 'Bodfish', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(161, 'Bolinas', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(162, 'Bolsa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(163, 'Bombay Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(164, 'Bonita', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(165, 'Bonny Doon', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(166, 'Bonsall', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(167, 'Boonville', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(168, 'Boron', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(169, 'Borrego Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(170, 'Bostonia', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(171, 'Boulder Creek', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(172, 'Boulevard', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(173, 'Bouquet Canyon Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(174, 'Bowman', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(175, 'Boyes Hot Springs', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(176, 'Bradbury', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(177, 'Bradford', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(178, 'Bradley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(179, 'Branscomb', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(180, 'Brawley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(181, 'Brea', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(182, 'Brents Junction', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(183, 'Brentwood', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(184, 'Brentwood Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(185, 'Briceland', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(186, 'Bridgeport', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(187, 'Bridgeport', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(188, 'Bridgeville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(189, 'Brisbane', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(190, 'Broderick West Sacramento', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(191, 'Brookdale', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(192, 'Brookhurst Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(193, 'Brooks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(194, 'Browns Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(195, 'Brownsville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(196, 'Bryn Mawr', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(197, 'Bryte West Sacramento', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(198, 'Buellton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(199, 'Buena Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(200, 'Burbank', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(201, 'Burlingame', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(202, 'Burney', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(203, 'Burnt Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(204, 'Burrel', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(205, 'Burson', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(206, 'Butte City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(207, 'Butte Meadows', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(208, 'Buttonwillow', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(209, 'Byron', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(210, 'Cabazon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(211, 'Cabrillo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(212, 'Cadiz', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(213, 'Calabasas', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(214, 'Calabasas Highlands', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(215, 'Calabasas Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(216, 'Calexico', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(217, 'Caliente', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(218, 'California City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(219, 'California Hot Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(220, 'California Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(221, 'Calimesa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(222, 'Calipatria', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(223, 'Calistoga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(224, 'Callahan', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(225, 'Calpella', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(226, 'Calpine', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(227, 'Calwa', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(228, 'Camarillo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(229, 'Cambria', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(230, 'Cambrian Park', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(231, 'Cameron Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(232, 'Camino', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(233, 'Camp Beale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(234, 'Camp Connell', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(235, 'Camp Curry', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(236, 'Camp Kaweah', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(237, 'Camp Meeker', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(238, 'Camp Nelson', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(239, 'Camp Pendleton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(240, 'Camp Roberts', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(241, 'Campbell', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(242, 'Campo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(243, 'Campo Seco', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(244, 'Camptonville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(245, 'Canby', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(246, 'Canoga Annex', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(247, 'Canoga Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(248, 'Cantil', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(249, 'Cantua Creek', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(250, 'Canyon', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(251, 'Canyon Country Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(252, 'Canyon Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(253, 'Canyondam', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(254, 'Capay', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(255, 'Capistrano Beach Dana Point', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(256, 'Capitola', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(257, 'Cardiff By The Sea Encinitas', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(258, 'Cardwell', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(259, 'Carlotta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(260, 'Carlsbad', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(261, 'Carmel', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(262, 'Carmel Rancho', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(263, 'Carmel Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(264, 'Carmichael', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(265, 'Carnelian Bay', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(266, 'Carpinteria', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(267, 'Carson', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(268, 'Cartago', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(269, 'Caruthers', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(270, 'Casitas Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(271, 'Casmalia', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(272, 'Caspar', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(273, 'Cassel', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(274, 'Castaic', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(275, 'Castella', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(276, 'Castle AFB', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(277, 'Castro Valley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(278, 'Castroville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(279, 'Cathedral City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(280, 'Catheys Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(281, 'Cayucos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(282, 'Cazadero', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(283, 'Cecilville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(284, 'Cedar', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(285, 'Cedar Crest', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(286, 'Cedar Glen', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(287, 'Cedar Ridge', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(288, 'Cedarpines Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(289, 'Cedarville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(290, 'Central Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(291, 'Century City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(292, 'Ceres', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(293, 'Cerritos', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(294, 'Challenge', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(295, 'Chambers Lodge', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(296, 'Charter Oak', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(297, 'Chatsworth Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(298, 'Cherry Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(299, 'Chester', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(300, 'Chicago Park', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(301, 'Chico', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(302, 'Chilcoot', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(303, 'China Lake NWC Ridgecrest', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(304, 'Chinese Camp', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(305, 'Chino', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(306, 'Chino Hills', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(307, 'Chiriaco Summit', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(308, 'Cholame', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(309, 'Chowchilla', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(310, 'Chualar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(311, 'Chula Vista', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(312, 'Cima', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(313, 'Citrus Heights', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(314, 'City of Commerce', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(315, 'City of Industry', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(316, 'City Terrace', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(317, 'Claremont', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(318, 'Clarksburg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(319, 'Clayton', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(320, 'Clear Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(321, 'Clearlake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(322, 'Clearlake Highlands Clearlake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(323, 'Clearlake Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(324, 'Clearlake Park Clearlake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(325, 'Clements', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(326, 'Clinter', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(327, 'Clio', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(328, 'Clipper Mills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(329, 'Cloverdale', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(330, 'Clovis', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(331, 'Coachella', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(332, 'Coalinga', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(333, 'Coarsegold', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(334, 'Cobb', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(335, 'Cohasset', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(336, 'Cole', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(337, 'Coleville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(338, 'Colfax', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(339, 'College City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(340, 'College Grove Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(341, 'Colma', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(342, 'Coloma', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(343, 'Colorado', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(344, 'Colton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(345, 'Columbia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(346, 'Colusa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(347, 'Commerce', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(348, 'Comptche', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(349, 'Compton', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(350, 'Concord', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(351, 'Cool', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(352, 'Copperopolis', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(353, 'Corcoran', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(354, 'Cornell', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(355, 'Corning', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(356, 'Corona', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(357, 'Corona Del Mar Newport Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(358, 'Coronado', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(359, 'Corralitos', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(360, 'Corte Madera', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(361, 'Coso Junction', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(362, 'Costa Mesa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(363, 'Cotati', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(364, 'Coto De Caza', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(365, 'Cottonwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(366, 'Coulterville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(367, 'Courtland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(368, 'Covelo', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(369, 'Covina', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(370, 'Cowan Heights', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(371, 'Coyote', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(372, 'Crannell', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(373, 'Crenshaw', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(374, 'Crescent City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(375, 'Crescent Mills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(376, 'Cressey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(377, 'Crest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(378, 'Crest Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(379, 'Cresta Blanca', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(380, 'Crestline', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(381, 'Creston', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(382, 'Crockett', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(383, 'Cromberg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(384, 'Cross Roads', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(385, 'Crowley Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(386, 'Crows Landing', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(387, 'Cucamonga Rancho Cucamonga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(388, 'Cudahy', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(389, 'Culver City', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(390, 'Cummings', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(391, 'Cupertino', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(392, 'Curry Village', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(393, 'Cutler', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(394, 'Cutten', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(395, 'Cuyama', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(396, 'Cypress', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(397, 'Daggett', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(398, 'Dairy Farm', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(399, 'Daly City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(400, 'Dana Point', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(401, 'Danville', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(402, 'Dardanelle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(403, 'Darwin', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(404, 'Davenport', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(405, 'Davis UC Davis campus rate is 750', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(406, 'Davis Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(407, 'Death Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(408, 'Death Valley Junction', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(409, 'Deer Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(410, 'Del Kern Bakersfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(411, 'Del Mar', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(412, 'Del Mar Heights Morro Bay', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(413, 'Del Monte Park Monterey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(414, 'Del Rey', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(415, 'Del Rey Oaks', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(416, 'Del Rosa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(417, 'Del Sur', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(418, 'Delano', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(419, 'Deleven', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(420, 'Delhi', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(421, 'Denair', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(422, 'Denny', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(423, 'Descanso', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(424, 'Desert Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(425, 'Desert Hot Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(426, 'Di Giorgio', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(427, 'Diablo', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(428, 'Diamond Bar', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(429, 'Diamond Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(430, 'Dillon Beach', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(431, 'Dinkey Creek', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(432, 'Dinuba', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(433, 'Discovery Bay', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(434, 'Dixon', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(435, 'Dobbins', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(436, 'Dogtown', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(437, 'Dollar Ranch', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(438, 'Dorris', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(439, 'Dos Palos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(440, 'Dos Rios', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(441, 'Douglas City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(442, 'Douglas Flat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(443, 'Downey', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(444, 'Downieville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(445, 'Doyle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(446, 'Drytown', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(447, 'Duarte', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(448, 'Dublin', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(449, 'Ducor', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(450, 'Dulzura', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(451, 'Duncans Mills', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(452, 'Dunlap', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(453, 'Dunnigan', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(454, 'Dunsmuir', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(455, 'Durham', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(456, 'Dutch Flat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(457, 'Eagle Mountain', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(458, 'Eagle Rock Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(459, 'Eagleville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(460, 'Earlimart', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(461, 'Earp', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(462, 'East Highlands Highland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(463, 'East Irvine Irvine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(464, 'East Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(465, 'East Lynwood Lynwood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(466, 'East Nicolaus', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(467, 'East Palo Alto', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(468, 'East Porterville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(469, 'East Rancho Dominguez', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(470, 'East San Pedro Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(471, 'Eastgate', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(472, 'Easton', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(473, 'Eastside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(474, 'Eastvale', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(475, 'Echo Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(476, 'Echo Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(477, 'Edgemont Moreno Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(478, 'Edgewood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(479, 'Edison', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(480, 'Edwards', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(481, 'Edwards AFB', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(482, 'El Cajon', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(483, 'El Centro', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(484, 'El Cerrito', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(485, 'El Dorado', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(486, 'El Dorado Hills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(487, 'El Granada', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(488, 'El Macero', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(489, 'El Modena', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(490, 'El Monte', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(491, 'El Nido', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(492, 'El Portal', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(493, 'El Segundo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(494, 'El Sobrante', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(495, 'El Toro Lake Forest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(496, 'El Toro MCAS', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(497, 'El Verano', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(498, 'El Viejo', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(499, 'Eldridge', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(500, 'Elizabeth Lake', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(501, 'Elk', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(502, 'Elk Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(503, 'Elk Grove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(504, 'Elmira', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(505, 'Elmwood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(506, 'Elverta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(507, 'Emerald Hills Redwood City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(508, 'Emeryville', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(509, 'Emigrant Gap', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(510, 'Empire', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(511, 'Encinitas', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(512, 'Encino Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(513, 'Enterprise', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(514, 'Escalon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(515, 'Escondido', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(516, 'Esparto', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(517, 'Essex', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(518, 'Etiwanda Rancho Cucamonga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(519, 'Etna', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(520, 'Ettersburg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(521, 'Eureka', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(522, 'Exeter', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(523, 'Fair Oaks', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(524, 'Fairfax', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(525, 'Fairfield', 1, 'CA', 8.625);
INSERT INTO `tblcities` VALUES(526, 'Fairmount', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(527, 'Fall River Mills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(528, 'Fallbrook', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(529, 'Fallbrook Junction', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(530, 'Fallen Leaf', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(531, 'Fallon', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(532, 'Fancher', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(533, 'Farmersville', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(534, 'Farmington', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(535, 'Fawnskin', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(536, 'Feather Falls', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(537, 'Fellows', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(538, 'Felton', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(539, 'Fenner', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(540, 'Fernbridge Fortuna', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(541, 'Ferndale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(542, 'Fiddletown', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(543, 'Fields Landing', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(544, 'Fig Garden Village Fresno', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(545, 'Fillmore', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(546, 'Finley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(547, 'Firebaugh', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(548, 'Fish Camp', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(549, 'Five Points', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(550, 'Flinn Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(551, 'Flintridge LaCanada Flintridge', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(552, 'Florence', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(553, 'Floriston', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(554, 'Flournoy', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(555, 'Folsom', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(556, 'Fontana', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(557, 'Foothill Ranch', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(558, 'Forbestown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(559, 'Forest Falls', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(560, 'Forest Glen', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(561, 'Forest Knolls', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(562, 'Forest Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(563, 'Forest Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(564, 'Foresthill', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(565, 'Forestville', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(566, 'Forks of Salmon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(567, 'Fort Bidwell', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(568, 'Fort Bragg', 1, 'CA', 8.625);
INSERT INTO `tblcities` VALUES(569, 'Fort Dick', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(570, 'Fort Irwin', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(571, 'Fort Jones', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(572, 'Fort Ord Seaside', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(573, 'Fort Seward', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(574, 'Fortuna', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(575, 'Foster City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(576, 'Fountain Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(577, 'Fowler', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(578, 'Frazier Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(579, 'Freedom', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(580, 'Freedom Watsonville', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(581, 'Freeport', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(582, 'Freestone', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(583, 'Fremont', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(584, 'French Camp', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(585, 'French Gulch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(586, 'Freshwater', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(587, 'Fresno', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(588, 'Friant', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(589, 'Friendly Valley Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(590, 'Frontera', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(591, 'Fullerton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(592, 'Fulton', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(593, 'Galt', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(594, 'Garberville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(595, 'Garden Grove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(596, 'Garden Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(597, 'Gardena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(598, 'Garey', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(599, 'Garnet', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(600, 'Gasquet', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(601, 'Gaviota', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(602, 'Gazelle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(603, 'George AFB', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(604, 'Georgetown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(605, 'Gerber', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(606, 'Geyserville', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(607, 'Giant Forest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(608, 'Gillman Hot Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(609, 'Gilroy', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(610, 'Glassell Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(611, 'Glen Avon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(612, 'Glen Ellen', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(613, 'Glenburn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(614, 'Glencoe', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(615, 'Glendale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(616, 'Glendora', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(617, 'Glenhaven', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(618, 'Glenn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(619, 'Glennville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(620, 'Gold River Rancho Cordova', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(621, 'Gold Run', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(622, 'Golden Hills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(623, 'Goleta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(624, 'Gonzales', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(625, 'Goodyears Bar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(626, 'Gorman', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(627, 'Goshen', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(628, 'Government Island', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(629, 'Graeagle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(630, 'Granada Hills Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(631, 'Grand Terrace', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(632, 'Granite Bay', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(633, 'Grass Valley', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(634, 'Graton', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(635, 'Green Valley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(636, 'Green Valley Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(637, 'Greenacres', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(638, 'Greenbrae Larkspur', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(639, 'Greenfield', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(640, 'Greenview', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(641, 'Greenville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(642, 'Greenwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(643, 'Grenada', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(644, 'Gridley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(645, 'Grimes', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(646, 'Grizzly Flats', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(647, 'Groveland', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(648, 'Grover Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(649, 'Guadalupe', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(650, 'Gualala', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(651, 'Guasti Ontario', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(652, 'Guatay', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(653, 'Guerneville', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(654, 'Guinda', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(655, 'Gustine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(656, 'Hacienda Heights', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(657, 'Halcyon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(658, 'Half Moon Bay', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(659, 'Hamilton AFB Novato', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(660, 'Hamilton City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(661, 'Hanford', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(662, 'Happy Camp', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(663, 'Harbison Canyon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(664, 'Harbor City Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(665, 'Harmony', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(666, 'Harris', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(667, 'Hat Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(668, 'Hathaway Pines', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(669, 'Havasu Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(670, 'Hawaiian Gardens', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(671, 'Hawthorne', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(672, 'Hayfork', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(673, 'Hayward', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(674, 'Hazard', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(675, 'Healdsburg', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(676, 'Heber', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(677, 'Helena', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(678, 'Helendale', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(679, 'Helm', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(680, 'Hemet', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(681, 'Herald', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(682, 'Hercules', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(683, 'Herlong', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(684, 'Hermosa Beach', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(685, 'Herndon', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(686, 'Hesperia', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(687, 'Heyer', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(688, 'Hickman', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(689, 'Hidden Hills', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(690, 'Highgrove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(691, 'Highland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(692, 'Highland Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(693, 'Highway City Fresno', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(694, 'Hillcrest San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(695, 'Hillsborough', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(696, 'Hillsdale San Mateo', 1, 'CA', 9.25);
INSERT INTO `tblcities` VALUES(697, 'Hilmar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(698, 'Hilt', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(699, 'Hinkley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(700, 'Hobergs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(701, 'Hollister', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(702, 'Hollywood Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(703, 'Holmes', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(704, 'Holt', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(705, 'Holtville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(706, 'Holy City', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(707, 'Homeland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(708, 'Homestead', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(709, 'Homestead', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(710, 'Homewood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(711, 'Honby', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(712, 'Honeydew', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(713, 'Hood', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(714, 'Hoopa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(715, 'Hope Valley Forest Camp', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(716, 'Hopland', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(717, 'Hornbrook', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(718, 'Hornitos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(719, 'Horse Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(720, 'Horse Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(721, 'Hughson', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(722, 'Hume', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(723, 'Huntington', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(724, 'Huntington Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(725, 'Huntington Lake', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(726, 'Huntington Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(727, 'Huron', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(728, 'Hyampom', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(729, 'Hyde Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(730, 'Hydesville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(731, 'Idria', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(732, 'Idyllwild', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(733, 'Ignacio Novato', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(734, 'Igo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(735, 'Imola Napa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(736, 'Imperial', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(737, 'Imperial Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(738, 'Independence', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(739, 'Indian Wells', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(740, 'Indio', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(741, 'Industry', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(742, 'Inglewood', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(743, 'Inverness', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(744, 'Inyo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(745, 'Inyokern', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(746, 'Ione', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(747, 'Iowa Hill', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(748, 'Irvine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(749, 'Irwindale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(750, 'Isla Vista', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(751, 'Island Mountain', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(752, 'Isleton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(753, 'Ivanhoe', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(754, 'Ivanpah', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(755, 'Jackson', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(756, 'Jacumba', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(757, 'Jamacha', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(758, 'Jamestown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(759, 'Jamul', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(760, 'Janesville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(761, 'Jenner', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(762, 'Johannesburg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(763, 'Johnsondale', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(764, 'Johnstonville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(765, 'Johnstown', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(766, 'Jolon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(767, 'Joshua Tree', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(768, 'Julian', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(769, 'Junction City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(770, 'June Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(771, 'Juniper', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(772, 'Jurupa Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(773, 'Kagel Canyon', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(774, 'Kaweah', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(775, 'Keddie', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(776, 'Keeler', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(777, 'Keene', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(778, 'Kelsey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(779, 'Kelseyville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(780, 'Kelso', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(781, 'Kensington', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(782, 'Kentfield', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(783, 'Kenwood', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(784, 'Kerman', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(785, 'Kernville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(786, 'Keswick', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(787, 'Kettleman City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(788, 'Keyes', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(789, 'King City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(790, 'Kings Beach', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(791, 'Kings Canyon National Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(792, 'Kingsburg', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(793, 'Kinyon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(794, 'Kirkwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(795, 'Kit Carson', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(796, 'Klamath', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(797, 'Klamath River', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(798, 'Kneeland', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(799, 'Knights Ferry', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(800, 'Knights Landing', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(801, 'Knightsen', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(802, 'Korbel', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(803, 'Korbel', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(804, 'Kyburz', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(805, 'LA Airport Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(806, 'La Canada- Flintridge', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(807, 'La Crescenta', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(808, 'La Cresta Village', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(809, 'La Grange', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(810, 'La Habra', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(811, 'La Habra Heights', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(812, 'La Honda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(813, 'La Jolla San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(814, 'La Mesa', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(815, 'La Mirada', 1, 'CA', 10);
INSERT INTO `tblcities` VALUES(816, 'La Palma', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(817, 'La Porte', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(818, 'La Puente', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(819, 'La Quinta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(820, 'La Selva Beach', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(821, 'La Verne', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(822, 'La Vina', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(823, 'Ladera', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(824, 'Ladera Heights', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(825, 'Ladera Ranch', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(826, 'Lafayette', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(827, 'Laguna Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(828, 'Laguna Hills', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(829, 'Laguna Niguel', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(830, 'Laguna Woods', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(831, 'Lagunitas', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(832, 'Lake Alpine', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(833, 'Lake Arrowhead', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(834, 'Lake City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(835, 'Lake City', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(836, 'Lake Elsinore', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(837, 'Lake Forest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(838, 'Lake Hughes', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(839, 'Lake Isabella', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(840, 'Lake Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(841, 'Lake Mary', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(842, 'Lake San Marcos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(843, 'Lake Shastina', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(844, 'Lake Sherwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(845, 'Lakehead', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(846, 'Lakeport', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(847, 'Lakeshore', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(848, 'Lakeside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(849, 'Lakeview', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(850, 'Lakeview Terrace Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(851, 'Lakewood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(852, 'Lamont', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(853, 'Lancaster', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(854, 'Landers', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(855, 'Landscape', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(856, 'Lang', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(857, 'Larkfield', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(858, 'Larkspur', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(859, 'Larwin Plaza', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(860, 'Lathrop', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(861, 'Laton', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(862, 'Lawndale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(863, 'Laws', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(864, 'Laytonville', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(865, 'Le Grand Also Legrand', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(866, 'Lebec', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(867, 'Lee Vining', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(868, 'Leggett', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(869, 'Leisure World', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(870, 'Leisure World Seal Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(871, 'Lemon Grove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(872, 'Lemoncove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(873, 'Lemoore', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(874, 'Lennox', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(875, 'Lenwood', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(876, 'Leona Valley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(877, 'Leucadia Encinitas', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(878, 'Lewiston', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(879, 'Liberty Farms', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(880, 'Likely', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(881, 'Lincoln', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(882, 'Lincoln Acres', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(883, 'Lincoln Heights Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(884, 'Lincoln Village', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(885, 'Linda', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(886, 'Linden', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(887, 'Lindsay', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(888, 'Linnell', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(889, 'Litchfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(890, 'Little Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(891, 'Little Norway', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(892, 'Little Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(893, 'Littleriver', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(894, 'Littlerock Also Little Rock', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(895, 'Live Oak', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(896, 'Live Oak ', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(897, 'Livermore', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(898, 'Livingston', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(899, 'Llano', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(900, 'Loch Lomond', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(901, 'Locke', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(902, 'Lockeford', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(903, 'Lockheed', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(904, 'Lockwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(905, 'Lodi', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(906, 'Loleta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(907, 'Loma Linda', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(908, 'Loma Mar', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(909, 'Loma Rica', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(910, 'Lomita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(911, 'Lompoc', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(912, 'London', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(913, 'Lone Pine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(914, 'Long Barn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(915, 'Long Beach', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(916, 'Longview', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(917, 'Lookout', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(918, 'Loomis', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(919, 'Lorre Estates', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(920, 'Los Alamitos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(921, 'Los Alamos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(922, 'Los Altos', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(923, 'Los Altos Hills', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(924, 'Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(925, 'Los Banos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(926, 'Los Gatos', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(927, 'Los Molinos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(928, 'Los Nietos', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(929, 'Los Olivos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(930, 'Los Osos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(931, 'Los Padres', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(932, 'Los Serranos Chino Hills', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(933, 'Lost Hills', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(934, 'Lost Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(935, 'Lotus', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(936, 'Lower Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(937, 'Loyalton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(938, 'Lucerne', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(939, 'Lucerne Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(940, 'Lucia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(941, 'Ludlow', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(942, 'Lugo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(943, 'Lynwood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(944, 'Lytle Creek', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(945, 'Macdoel', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(946, 'Maclay', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(947, 'Mad River', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(948, 'Madeline', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(949, 'Madera', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(950, 'Madison', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(951, 'Magalia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(952, 'Malaga', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(953, 'Malibu', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(954, 'Mammoth Lakes', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(955, 'Manhattan Beach', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(956, 'Manteca', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(957, 'Manton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(958, 'Manzanita Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(959, 'Mar Vista', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(960, 'Marcelina', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(961, 'March AFB', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(962, 'Mare Island Vallejo', 1, 'CA', 8.625);
INSERT INTO `tblcities` VALUES(963, 'Maricopa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(964, 'Marin City', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(965, 'Marina', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(966, 'Marina Del Rey', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(967, 'Marine Corps Twentynine Palms', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(968, 'Mariner', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(969, 'Mariposa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(970, 'Markleeville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(971, 'Marsh Manor', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(972, 'Marshall', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(973, 'Martell', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(974, 'Martinez', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(975, 'Marysville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(976, 'Mather', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(977, 'Mather ', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(978, 'Maxwell', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(979, 'Maywood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(980, 'McArthur', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(981, 'McClellan', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(982, 'McCloud', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(983, 'McFarland', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(984, 'McKinleyville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(985, 'McKittrick', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(986, 'Mead Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(987, 'Meadow Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(988, 'Meadow Vista', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(989, 'Meadowbrook', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(990, 'Mecca', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(991, 'Meeks Bay', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(992, 'Meiners Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(993, 'Mendocino', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(994, 'Mendota', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(995, 'Menifee', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(996, 'Menlo Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(997, 'Mentone', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(998, 'Merced', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(999, 'Meridian', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1000, 'Mettler', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1001, 'Meyers', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1002, 'Middletown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1003, 'Midland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1004, 'Midpines', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1005, 'Midway City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1006, 'Milford', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1007, 'Mill Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1008, 'Mill Valley', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1009, 'Millbrae', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1010, 'Millville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1011, 'Milpitas', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1012, 'Mineral', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1013, 'Mineral King', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1014, 'Mint Canyon', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1015, 'Mira Loma', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1016, 'Mira Vista', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1017, 'Miracle Hot Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1018, 'Miramar San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1019, 'Miramonte', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1020, 'Miranda', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1021, 'Mission Hills Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1022, 'Mission Viejo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1023, 'Mi-Wuk Village', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1024, 'Moccasin', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1025, 'Modesto', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1026, 'Moffett Field', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1027, 'Mojave', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1028, 'Mokelumne Hill', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1029, 'Monarch Beach Dana Point', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1030, 'Moneta', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1031, 'Mono Hot Springs', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1032, 'Mono Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1033, 'Monolith', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1034, 'Monrovia', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1035, 'Monta Vista', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1036, 'Montague', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1037, 'Montalvo Ventura', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1038, 'Montara', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1039, 'Montclair', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1040, 'Monte Rio', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1041, 'Monte Sereno', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1042, 'Montebello', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1043, 'Montecito', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1044, 'Monterey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1045, 'Monterey Bay Academy', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1046, 'Monterey Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1047, 'Montgomery Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1048, 'Montrose', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1049, 'Mooney', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1050, 'Moonridge', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1051, 'Moorpark', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1052, 'Moraga', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(1053, 'Moreno Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1054, 'Morgan Hill', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1055, 'Morongo Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1056, 'Morro Bay', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1057, 'Morro Plaza', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1058, 'Moss Beach', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1059, 'Moss Landing', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1060, 'Mount Hamilton', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1061, 'Mount Hebron', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1062, 'Mount Hermon', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1063, 'Mount Laguna', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1064, 'Mount Shasta', 1, 'CA', 7.75);
INSERT INTO `tblcities` VALUES(1065, 'Mount Wilson', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1066, 'Mountain Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1067, 'Mountain Mesa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1068, 'Mountain Pass', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1069, 'Mountain Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1070, 'Mountain View', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1071, 'Mt Aukum', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1072, 'Mt Baldy', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1073, 'Murphys', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1074, 'Murrieta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1075, 'Muscoy', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1076, 'Myers Flat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1077, 'Napa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1078, 'Naples', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1079, 'Nashville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1080, 'National City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1081, 'Naval Port Hueneme', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1082, 'Naval San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1083, 'Naval Air Station Alameda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1084, 'Naval Air Station Coronado', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1085, 'Naval Air Station Lemoore', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1086, 'Naval Hospital Oakland', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1087, 'Naval Hospital San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1088, 'Naval Supply Center Oakland', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1089, 'Naval Training Center San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1090, 'Navarro', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1091, 'Needles', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1092, 'Nelson', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1093, 'Nevada City', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1094, 'New Almaden', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1095, 'New Cuyama', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1096, 'New Idria', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1097, 'Newark', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1098, 'Newberry', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1099, 'Newberry Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1100, 'Newbury Park Thousand Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1101, 'Newcastle', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1102, 'Newhall Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1103, 'Newman', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1104, 'Newport Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1105, 'Nicasio', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1106, 'Nice', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1107, 'Nicolaus', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1108, 'Niland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1109, 'Nipomo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1110, 'Nipton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1111, 'Norco', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1112, 'Norden', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1113, 'North Edwards', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1114, 'North Fork', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1115, 'North Gardena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1116, 'North Highlands', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1117, 'North Hills Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1118, 'North Hollywood Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1119, 'North Palm Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1120, 'North San Juan', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1121, 'North Shore', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1122, 'Northridge Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1123, 'Norton AFB San Bernardino', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1124, 'Norwalk', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1125, 'Novato', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1126, 'Nubieber', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1127, 'Nuevo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1128, 'Nyeland Acres', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1129, 'Oak Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1130, 'Oak Run', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1131, 'Oak View', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1132, 'Oakdale', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(1133, 'Oakhurst', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1134, 'Oakland', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1135, 'Oakley', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1136, 'Oakville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1137, 'Oasis', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1138, 'Oban', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1139, 'OBrien', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1140, 'Occidental', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1141, 'Oceano', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1142, 'Oceanside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1143, 'Ocotillo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1144, 'Ocotillo Wells', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1145, 'Oildale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1146, 'Ojai', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1147, 'Olancha', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1148, 'Old Station', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1149, 'Olema', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1150, 'Olinda', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1151, 'Olive View Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1152, 'Olivehurst', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1153, 'Olivenhain Encinitas', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1154, 'Olympic Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1155, 'Omo Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1156, 'ONeals', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1157, 'Ono', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1158, 'Ontario', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1159, 'Onyx', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1160, 'Opal Cliffs', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1161, 'Orange', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1162, 'Orange Cove', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1163, 'Orangevale', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1164, 'Orcutt', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1165, 'Ordbend', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1166, 'Oregon House', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1167, 'Orick', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1168, 'Orinda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1169, 'Orland', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1170, 'Orleans', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1171, 'Oro Grande', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1172, 'Orosi', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1173, 'Oroville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1174, 'Otay Chula Vista', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1175, 'Oxnard', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1176, 'Pacheco', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1177, 'Pacific Grove', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1178, 'Pacific House', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1179, 'Pacific Palisades Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1180, 'Pacifica', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1181, 'Pacoima Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1182, 'Paicines', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1183, 'Pajaro', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1184, 'Pala', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1185, 'Palermo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1186, 'Pallett', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1187, 'Palm City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1188, 'Palm City San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1189, 'Palm Desert', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1190, 'Palm Springs', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1191, 'Palmdale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1192, 'Palo Alto', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1193, 'Palo Cedro', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1194, 'Palo Verde', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1195, 'Palomar Mountain', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1196, 'Palos Verdes Estates', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1197, 'Palos VerdesPeninsula', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1198, 'Panorama City Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1199, 'Paradise', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1200, 'Paramount', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1201, 'Parker Dam', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1202, 'Parkfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1203, 'Parlier', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1204, 'Pasadena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1205, 'Paskenta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1206, 'Paso Robles', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1207, 'Patterson', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1208, 'Patton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1209, 'Pauma Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1210, 'Paynes Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1211, 'Pearblossom', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1212, 'Pearland', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1213, 'Pebble Beach', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1214, 'Pedley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1215, 'Peninsula Village', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1216, 'Penn Valley', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1217, 'Penngrove', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1218, 'Penryn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1219, 'Pepperwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1220, 'Permanente', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1221, 'Perris', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1222, 'Perry Whittier', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1223, 'Pescadero', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1224, 'Petaluma', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1225, 'Petrolia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1226, 'Phelan', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1227, 'Phillipsville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1228, 'Philo', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1229, 'Pico Rivera', 1, 'CA', 10);
INSERT INTO `tblcities` VALUES(1230, 'Piedmont', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1231, 'Piedra', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1232, 'Piercy', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1233, 'Pilot Hill', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1234, 'Pine Grove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1235, 'Pine Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1236, 'Pinecrest', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1237, 'Pinedale Fresno', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1238, 'Pinetree', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1239, 'Pinole', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1240, 'Pinon Hills', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1241, 'Pioneer', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1242, 'Pioneertown', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1243, 'Piru', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1244, 'Pismo Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1245, 'Pittsburg', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1246, 'Pixley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1247, 'Placentia', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1248, 'Placerville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1249, 'Plainview', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1250, 'Planada', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1251, 'Plaster City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1252, 'Platina', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1253, 'Playa Del Rey Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1254, 'Pleasant Grove', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1255, 'Pleasant Hill', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1256, 'Pleasanton', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1257, 'Plymouth', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1258, 'Point Arena', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(1259, 'Point Mugu', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1260, 'Point Pittsburg Pittsburg', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1261, 'Point Reyes Station', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1262, 'Pollock Pines', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1263, 'Pomona', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1264, 'Pond', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1265, 'Pondosa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1266, 'Pope Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1267, 'Poplar', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1268, 'Port Costa', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1269, 'Port Hueneme', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1270, 'Porter Ranch Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1271, 'Porterville', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1272, 'Portola', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1273, 'Portola Valley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1274, 'Portuguese Bend Rancho Palos Verdes', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1275, 'Posey', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1276, 'Potrero', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1277, 'Potter Valley', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1278, 'Poway', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1279, 'Prather', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1280, 'Presidio San Francisco', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1281, 'Presidio of Monterey Monterey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1282, 'Priest Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1283, 'Princeton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1284, 'Proberta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1285, 'Project City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1286, 'Prunedale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1287, 'Pt Dume', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1288, 'Pulga', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1289, 'Pumpkin Center', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1290, 'Quail Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1291, 'Quartz Hill', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1292, 'Quincy', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1293, 'Rackerby', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1294, 'Rail Road Flat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1295, 'Rainbow', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1296, 'Raisin City', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1297, 'Ramona', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1298, 'Ranchita', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1299, 'Rancho Bernardo San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1300, 'Rancho California', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1301, 'Rancho Cordova ', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1302, 'Rancho Cucamonga', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1303, 'Rancho Dominguez', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1304, 'Rancho Mirage', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1305, 'Rancho Murieta', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1306, 'Rancho Palos Verdes', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1307, 'Rancho Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1308, 'Rancho Santa Fe', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1309, 'Rancho Santa Margarita', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1310, 'Randsburg', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1311, 'Ravendale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1312, 'Ravenna', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1313, 'Raymond', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1314, 'Red Bluff', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1315, 'Red Mountain', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1316, 'Red Top', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1317, 'Redcrest', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1318, 'Redding', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1319, 'Redlands', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1320, 'Redondo Beach', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1321, 'Redway', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1322, 'Redwood City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1323, 'Redwood Estates', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1324, 'Redwood Valley', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1325, 'Reedley', 1, 'CA', 8.725);
INSERT INTO `tblcities` VALUES(1326, 'Refugio Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1327, 'Represa Folsom Prison', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1328, 'Requa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1329, 'Rescue', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1330, 'Reseda Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1331, 'Rheem Valley Moraga', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1332, 'Rialto', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1333, 'Richardson Grove', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1334, 'Richardson Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1335, 'Richfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1336, 'Richgrove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1337, 'Richmond', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1338, 'Richvale', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1339, 'Ridgecrest', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1340, 'Rimforest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1341, 'Rimpau Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1342, 'Rio Bravo Bakersfield', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1343, 'Rio Del Mar', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1344, 'Rio Dell', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1345, 'Rio Linda', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1346, 'Rio Nido', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1347, 'Rio Oso', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1348, 'Rio Vista', 1, 'CA', 8.375);
INSERT INTO `tblcities` VALUES(1349, 'Ripley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1350, 'Ripon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1351, 'River Pines', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1352, 'Riverbank', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1353, 'Riverdale', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1354, 'Riverside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1355, 'Robbins', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1356, 'Rocklin', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1357, 'Rodeo', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1358, 'Rohnert Park', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1359, 'Rohnerville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1360, 'Rolling Hills', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1361, 'Rolling Hills Estates', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1362, 'Romoland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1363, 'Rosamond', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1364, 'Rose Bowl Pasadena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1365, 'Roseland', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1366, 'Rosemead', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1367, 'Roseville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1368, 'Ross', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1369, 'Rossmoor', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1370, 'Rough and Ready', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1371, 'Round Mountain', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1372, 'Rowland Heights', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1373, 'Royal Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1374, 'Rubidoux', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1375, 'Ruby Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1376, 'Rumsey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1377, 'Running Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1378, 'Ruth', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1379, 'Rutherford', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1380, 'Ryde', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1381, 'Sacramento', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1382, 'Saint Helena', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1383, 'Salida', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1384, 'Salinas', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1385, 'Salton City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1386, 'Salyer', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1387, 'Samoa', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1388, 'San Andreas', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1389, 'San Anselmo', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1390, 'San Ardo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1391, 'San Benito', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1392, 'San Bernardino', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1393, 'San Bruno', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1394, 'San Carlos San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1395, 'San Carlos', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1396, 'San Clemente', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1397, 'San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1398, 'San Dimas', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1399, 'San Fernando', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(1400, 'San Francisco', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1401, 'San Gabriel', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1402, 'San Geronimo', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1403, 'San Gregorio', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1404, 'San Jacinto', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1405, 'San Joaquin', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1406, 'San Jose', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1407, 'San Juan Bautista', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1408, 'San Juan Capistrano', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1409, 'San Juan Plaza San Juan Capistrano', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1410, 'San Leandro', 1, 'CA', 9.25);
INSERT INTO `tblcities` VALUES(1411, 'San Lorenzo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1412, 'San Lucas', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1413, 'San Luis Obispo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1414, 'San Luis Rey Oceanside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1415, 'San Marcos', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1416, 'San Marino', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1417, 'San Martin', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1418, 'San Mateo', 1, 'CA', 9.25);
INSERT INTO `tblcities` VALUES(1419, 'San Miguel', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1420, 'San Pablo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1421, 'San Pedro Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1422, 'San Quentin', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1423, 'San Rafael', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1424, 'San Ramon', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1425, 'San Simeon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1426, 'San Tomas', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1427, 'San Ysidro San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1428, 'Sand City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1429, 'Sanger', 1, 'CA', 8.975);
INSERT INTO `tblcities` VALUES(1430, 'Santa Ana', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1431, 'Santa Barbara', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1432, 'Santa Clara', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1433, 'Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1434, 'Santa Cruz', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1435, 'Santa Fe Springs', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1436, 'Santa Margarita', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1437, 'Santa Maria', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1438, 'Santa Monica', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(1439, 'Santa Nella', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1440, 'Santa Paula', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1441, 'Santa Rita Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1442, 'Santa Rosa', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1443, 'Santa Rosa Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1444, 'Santa Ynez', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1445, 'Santa Ysabel', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1446, 'Santee', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1447, 'Saratoga', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1448, 'Saticoy', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1449, 'Sattley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1450, 'Saugus Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1451, 'Sausalito', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1452, 'Sawtelle Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1453, 'Sawyers Bar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1454, 'Scotia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1455, 'Scott Bar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1456, 'Scotts Valley', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1457, 'Sea Ranch', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1458, 'Seabright Santa Cruz', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1459, 'Seal Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1460, 'Seaside', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1461, 'Sebastopol', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1462, 'Seeley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1463, 'Seiad Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1464, 'Selby', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1465, 'Selma', 1, 'CA', 8.725);
INSERT INTO `tblcities` VALUES(1466, 'Seminole Hot Springs', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1467, 'Sepulveda Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1468, 'Sequoia National Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1469, 'Shafter', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1470, 'Shandon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1471, 'Sharpe Army Depot', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1472, 'Shasta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1473, 'Shasta Lake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1474, 'Shaver Lake', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1475, 'Sheepranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1476, 'Shell Beach Pismo Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1477, 'Sheridan', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1478, 'Sherman Island', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1479, 'Sherman Oaks Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1480, 'Sherwin Plaza', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1481, 'Shingle Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1482, 'Shingletown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1483, 'Shively', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1484, 'Shore Acres', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1485, 'Shoshone', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1486, 'Sierra City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1487, 'Sierra Madre', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1488, 'Sierraville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1489, 'Signal Hill', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1490, 'Silver Lake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1491, 'Silverado Canyon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1492, 'Simi Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1493, 'Sisquoc', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1494, 'Sites', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1495, 'Sky Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1496, 'Skyforest', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1497, 'Sleepy Valley', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1498, 'Sloat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1499, 'Sloughhouse', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1500, 'Smartsville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1501, 'Smith River', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1502, 'Smithflat', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1503, 'Smoke Tree Palm Springs', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1504, 'Smoke Tree Twentynine Palms', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1505, 'Snelling', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1506, 'Soda Springs', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1507, 'Solana Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1508, 'Soledad', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1509, 'Solemint', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1510, 'Solvang', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1511, 'Somerset', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1512, 'Somes Bar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1513, 'Somis', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1514, 'Sonoma', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1515, 'Sonora', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1516, 'Soquel', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1517, 'Soulsbyville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1518, 'South Dos Palos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1519, 'South El Monte', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(1520, 'South Fork', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1521, 'South Gate', 1, 'CA', 10);
INSERT INTO `tblcities` VALUES(1522, 'South Laguna Laguna Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1523, 'South Lake Tahoe', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1524, 'South Pasadena', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1525, 'South San Francisco', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1526, 'South Shore Alameda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1527, 'South Whittier', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1528, 'Spanish Flat', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1529, 'Spreckels', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1530, 'Spring Garden', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1531, 'Spring Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1532, 'Springville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1533, 'Spyrock', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1534, 'Squaw Valley', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1535, 'St Helena', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1536, 'Standard', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1537, 'Standish', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1538, 'Stanford', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1539, 'Stanislaus', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1540, 'Stanton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1541, 'Steele Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1542, 'Stevenson Ranch', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1543, 'Stevinson', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1544, 'Stewarts Point', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1545, 'Stinson Beach', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1546, 'Stirling City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1547, 'Stockton', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1548, 'Stonyford', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1549, 'Storrie', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1550, 'Stratford', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1551, 'Strathmore', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1552, 'Strawberry', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1553, 'Strawberry Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1554, 'Studio City Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1555, 'Sugarloaf', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1556, 'Suisun City', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1557, 'Sulphur Springs', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1558, 'Sultana', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1559, 'Summerland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1560, 'Summit', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1561, 'Summit City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1562, 'Sun City', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1563, 'Sun Valley Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1564, 'Sunland Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1565, 'Sunnymead Moreno Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1566, 'Sunnyside', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1567, 'Sunnyvale', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1568, 'Sunol', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1569, 'Sunset Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1570, 'Sunset Whitney Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1571, 'Surfside Seal Beach', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1572, 'Susanville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1573, 'Sutter', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1574, 'Sutter Creek', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1575, 'Swall Meadows Bishop', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1576, 'Sylmar Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1577, 'Taft', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1578, 'Tagus Ranch', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1579, 'Tahoe City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1580, 'Tahoe Paradise', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1581, 'Tahoe Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1582, 'Tahoe Vista', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1583, 'Tahoma', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1584, 'Talmage', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1585, 'Tamal San Quentin', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1586, 'Tarzana Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1587, 'Taylorsville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1588, 'Tecate', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1589, 'Tecopa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1590, 'Tehachapi', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1591, 'Tehama', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1592, 'Temecula', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1593, 'Temple City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1594, 'Templeton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1595, 'Terminal Island Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1596, 'Termo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1597, 'Terra Bella', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1598, 'Thermal', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1599, 'Thornton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1600, 'Thousand Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1601, 'Thousand Palms', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1602, 'Three Rivers', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1603, 'Tiburon', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1604, 'Tierra Del Sol', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1605, 'Tierrasanta San Diego', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1606, 'Tipton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1607, 'Tollhouse', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1608, 'Toluca Lake Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1609, 'Tomales', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1610, 'Toms Place', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1611, 'Topanga Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1612, 'Topanga Park Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1613, 'Topaz', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1614, 'Torrance', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1615, 'Town Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1616, 'Trabuco Canyon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1617, 'Tracy', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1618, 'Tranquillity', 1, 'CA', 8.225);
INSERT INTO `tblcities` VALUES(1619, 'Traver', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1620, 'Travis AFB Fairfield', 1, 'CA', 8.625);
INSERT INTO `tblcities` VALUES(1621, 'Tres Pinos', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1622, 'Trinidad', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1623, 'Trinity Center', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1624, 'Trona', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1625, 'Trowbridge', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1626, 'Truckee', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(1627, 'Tujunga Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1628, 'Tulare', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1629, 'Tulelake', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1630, 'Tuolumne', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1631, 'Tuolumne Meadows', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1632, 'Tupman', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1633, 'Turlock', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1634, 'Tustin', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1635, 'Twain', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1636, 'Twain Harte', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1637, 'Twentynine Palms', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1638, 'Twin Bridges', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1639, 'Twin Peaks', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1640, 'Two Rock Coast Guard Station', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1641, 'US Naval Postgrad School Monterey', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1642, 'Ukiah', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(1643, 'Union City', 1, 'CA', 9.5);
INSERT INTO `tblcities` VALUES(1644, 'Universal City', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1645, 'University', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1646, 'University Park Irvine', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1647, 'Upland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1648, 'Upper Lake Upper Lake Valley', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1649, 'Vacaville', 1, 'CA', 7.875);
INSERT INTO `tblcities` VALUES(1650, 'Val Verde Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1651, 'Valencia Santa Clarita', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1652, 'Valinda', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1653, 'Vallecito', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1654, 'Vallejo', 1, 'CA', 8.625);
INSERT INTO `tblcities` VALUES(1655, 'Valley Center', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1656, 'Valley Fair', 1, 'CA', 8.75);
INSERT INTO `tblcities` VALUES(1657, 'Valley Ford', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1658, 'Valley Home', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1659, 'Valley Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1660, 'Valley Village', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1661, 'Valyermo', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1662, 'Van Nuys Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1663, 'Vandenberg AFB', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1664, 'Vasquez Rocks', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1665, 'Venice Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1666, 'Ventucopa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1667, 'Ventura', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1668, 'Verdugo City Glendale', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1669, 'Vernalis', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1670, 'Vernon', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1671, 'Veterans Hospital Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1672, 'Victor', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1673, 'Victorville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1674, 'Vidal', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1675, 'View Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1676, 'Villa Grande', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1677, 'Villa Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1678, 'Vina', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1679, 'Vincent', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1680, 'Vineburg', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1681, 'Vinton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1682, 'Virgilia', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1683, 'Visalia', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1684, 'Vista', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1685, 'Vista Park', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1686, 'Volcano', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1687, 'Volta', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1688, 'Wallace', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1689, 'Walnut', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1690, 'Walnut Creek', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1691, 'Walnut Grove', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1692, 'Walnut Park', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1693, 'Warm Springs Fremont', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1694, 'Warner Springs', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1695, 'Wasco', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1696, 'Waterford', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1697, 'Watsonville', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1698, 'Watts', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1699, 'Waukena', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1700, 'Wawona', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1701, 'Weaverville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1702, 'Weed', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1703, 'Weimar', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1704, 'Weldon', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1705, 'Wendel', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1706, 'Weott', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1707, 'West Covina', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1708, 'West Hills Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1709, 'West Hollywood', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1710, 'West Los Angeles Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1711, 'West Pittsburg', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1712, 'West Point', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1713, 'West Sacramento', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1714, 'Westchester Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1715, 'Westend', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1716, 'Westhaven', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1717, 'Westlake Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1718, 'Westlake Village', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1719, 'Westlake Village Thousand Oaks', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1720, 'Westley', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1721, 'Westminster', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1722, 'Westmorland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1723, 'Westport', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1724, 'Westside', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1725, 'Westwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1726, 'Westwood Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1727, 'Wheatland', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1728, 'Wheeler Ridge', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1729, 'Whiskeytown', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1730, 'Whispering Pines', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1731, 'White Pines', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1732, 'Whitethorn', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1733, 'Whitewater', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1734, 'Whitlow', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1735, 'Whitmore', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1736, 'Whittier', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1737, 'Wildomar', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1738, 'Wildwood', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1739, 'Williams', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1740, 'Willits', 1, 'CA', 8.125);
INSERT INTO `tblcities` VALUES(1741, 'Willow Creek', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1742, 'Willow Ranch', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1743, 'Willowbrook', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1744, 'Willows', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1745, 'Wilmington Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1746, 'Wilseyville', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1747, 'Wilsona Gardens', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1748, 'Wilton', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1749, 'Winchester', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1750, 'Windsor', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1751, 'Windsor Hills', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1752, 'Winnetka Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1753, 'Winterhaven', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1754, 'Winters', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1755, 'Winton', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1756, 'Wishon', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1757, 'Witter Springs', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1758, 'Wofford Heights', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1759, 'Woodacre', 1, 'CA', 8.5);
INSERT INTO `tblcities` VALUES(1760, 'Woodbridge', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1761, 'Woodfords', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1762, 'Woodlake', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1763, 'Woodland', 1, 'CA', 8.25);
INSERT INTO `tblcities` VALUES(1764, 'Woodland Hills Los Angeles', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1765, 'Woodleaf', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1766, 'Woodside', 1, 'CA', 9);
INSERT INTO `tblcities` VALUES(1767, 'Woodville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1768, 'Woody', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1769, 'Wrightwood', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1770, 'Yankee Hill', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1771, 'Yermo', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1772, 'Yettem', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1773, 'Yolo', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1774, 'Yorba Linda', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1775, 'Yorkville', 1, 'CA', 7.625);
INSERT INTO `tblcities` VALUES(1776, 'Yosemite Lodge', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1777, 'Yosemite National Park', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1778, 'Yountville', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1779, 'Yreka', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1780, 'Yuba City', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1781, 'Yucaipa', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1782, 'Yucca Valley', 1, 'CA', 8);
INSERT INTO `tblcities` VALUES(1783, 'Zamora', 1, 'CA', 7.5);
INSERT INTO `tblcities` VALUES(1784, 'Zenia', 1, 'CA', 7.5);

-- --------------------------------------------------------

--
-- Table structure for table `tblcontries`
--

CREATE TABLE `tblcontries` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'mã số contry tự tăng',
  `name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'tên ',
  `code` varchar(200) NOT NULL COMMENT 'mã phân biệt các contry',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'an = 0 hoac hien=1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=250 ;

--
-- Dumping data for table `tblcontries`
--

INSERT INTO `tblcontries` VALUES(1, 'United States', 'US', 1);
INSERT INTO `tblcontries` VALUES(2, 'Canada', 'CA', 1);
INSERT INTO `tblcontries` VALUES(3, 'Việt Nam', 'VN', 1);
INSERT INTO `tblcontries` VALUES(4, 'Andorra', 'AD', 1);
INSERT INTO `tblcontries` VALUES(5, 'United Arab Emirates', 'AE', 1);
INSERT INTO `tblcontries` VALUES(6, 'Afghanistan', 'AF', 0);
INSERT INTO `tblcontries` VALUES(7, 'Antigua and Barbuda', 'AG', 1);
INSERT INTO `tblcontries` VALUES(8, 'Anguilla', 'AI', 1);
INSERT INTO `tblcontries` VALUES(9, 'Albania', 'AL', 0);
INSERT INTO `tblcontries` VALUES(10, 'Armenia', 'AM', 1);
INSERT INTO `tblcontries` VALUES(11, 'Netherlands Antilles', 'AN', 1);
INSERT INTO `tblcontries` VALUES(12, 'Angola', 'AO', 1);
INSERT INTO `tblcontries` VALUES(13, 'Antarctica', 'AQ', 0);
INSERT INTO `tblcontries` VALUES(14, 'Argentina', 'AR', 1);
INSERT INTO `tblcontries` VALUES(15, 'American Samoa', 'AS', 1);
INSERT INTO `tblcontries` VALUES(16, 'Austria', 'AT', 1);
INSERT INTO `tblcontries` VALUES(17, 'Australia', 'AU', 1);
INSERT INTO `tblcontries` VALUES(18, 'Aruba', 'AW', 1);
INSERT INTO `tblcontries` VALUES(19, 'Aland Islands', 'AX', 0);
INSERT INTO `tblcontries` VALUES(20, 'Azerbaijan', 'AZ', 1);
INSERT INTO `tblcontries` VALUES(21, 'Bosnia and Herzegovina', 'BA', 1);
INSERT INTO `tblcontries` VALUES(22, 'Barbados', 'BB', 1);
INSERT INTO `tblcontries` VALUES(23, 'Bangladesh', 'BD', 1);
INSERT INTO `tblcontries` VALUES(24, 'Belgium', 'BE', 1);
INSERT INTO `tblcontries` VALUES(25, 'Burkina Faso', 'BF', 1);
INSERT INTO `tblcontries` VALUES(26, 'Bulgaria', 'BG', 1);
INSERT INTO `tblcontries` VALUES(27, 'Bahrain', 'BH', 1);
INSERT INTO `tblcontries` VALUES(28, 'Burundi', 'BI', 1);
INSERT INTO `tblcontries` VALUES(29, 'Benin', 'BJ', 1);
INSERT INTO `tblcontries` VALUES(30, 'Saint Barthelemy', 'BL', 1);
INSERT INTO `tblcontries` VALUES(31, 'Bermuda', 'BM', 1);
INSERT INTO `tblcontries` VALUES(32, 'Brunei', 'BN', 1);
INSERT INTO `tblcontries` VALUES(33, 'Bolivia', 'BO', 1);
INSERT INTO `tblcontries` VALUES(34, 'Brazil', 'BR', 1);
INSERT INTO `tblcontries` VALUES(35, 'The Bahamas', 'BS', 1);
INSERT INTO `tblcontries` VALUES(36, 'Bhutan', 'BT', 1);
INSERT INTO `tblcontries` VALUES(37, 'Bouvet Island', 'BV', 1);
INSERT INTO `tblcontries` VALUES(38, 'Botswana', 'BW', 1);
INSERT INTO `tblcontries` VALUES(39, 'Belarus', 'BY', 1);
INSERT INTO `tblcontries` VALUES(40, 'Belize', 'BZ', 1);
INSERT INTO `tblcontries` VALUES(41, 'Cocos (Keeling) Islands', 'CC', 1);
INSERT INTO `tblcontries` VALUES(42, 'Democratic Republic of the Congo', 'CS', 1);
INSERT INTO `tblcontries` VALUES(43, 'Central African Republic', 'CF', 1);
INSERT INTO `tblcontries` VALUES(44, 'Republic of the Congo', 'CG', 1);
INSERT INTO `tblcontries` VALUES(45, 'Switzerland', 'CH', 1);
INSERT INTO `tblcontries` VALUES(46, 'Ivory Coast', 'CI', 1);
INSERT INTO `tblcontries` VALUES(47, 'Cook Islands', 'CK', 1);
INSERT INTO `tblcontries` VALUES(48, 'Chile', 'CL', 1);
INSERT INTO `tblcontries` VALUES(49, 'Cameroon', 'CM', 1);
INSERT INTO `tblcontries` VALUES(50, 'China', 'CN', 1);
INSERT INTO `tblcontries` VALUES(51, 'Colombia', 'CO', 1);
INSERT INTO `tblcontries` VALUES(52, 'Costa Rica', 'CR', 1);
INSERT INTO `tblcontries` VALUES(53, 'Cuba', 'CU', 1);
INSERT INTO `tblcontries` VALUES(54, 'Cape Verde', 'CV', 1);
INSERT INTO `tblcontries` VALUES(55, 'Christmas Island', 'CX', 1);
INSERT INTO `tblcontries` VALUES(56, 'Cyprus', 'CY', 1);
INSERT INTO `tblcontries` VALUES(57, 'Czech Republic', 'CZ', 1);
INSERT INTO `tblcontries` VALUES(58, 'Germany', 'DE', 1);
INSERT INTO `tblcontries` VALUES(59, 'Djibouti', 'DJ', 1);
INSERT INTO `tblcontries` VALUES(60, 'Denmark', 'DK', 1);
INSERT INTO `tblcontries` VALUES(61, 'Dominica', 'DM', 1);
INSERT INTO `tblcontries` VALUES(62, 'Dominican Republic', 'DO', 1);
INSERT INTO `tblcontries` VALUES(63, 'Algeria', 'DZ', 0);
INSERT INTO `tblcontries` VALUES(64, 'Ecuador', 'EC', 1);
INSERT INTO `tblcontries` VALUES(65, 'Estonia', 'EE', 1);
INSERT INTO `tblcontries` VALUES(66, 'Egypt', 'EG', 1);
INSERT INTO `tblcontries` VALUES(67, 'Western Sahara', 'EH', 1);
INSERT INTO `tblcontries` VALUES(68, 'Eritrea', 'ER', 1);
INSERT INTO `tblcontries` VALUES(69, 'Spain', 'ES', 1);
INSERT INTO `tblcontries` VALUES(70, 'Ethiopia', 'ET', 1);
INSERT INTO `tblcontries` VALUES(71, 'Finland', 'FI', 1);
INSERT INTO `tblcontries` VALUES(72, 'Fiji', 'FJ', 1);
INSERT INTO `tblcontries` VALUES(73, 'Falkland Islands', 'FK', 1);
INSERT INTO `tblcontries` VALUES(74, 'Micronesia', 'FM', 1);
INSERT INTO `tblcontries` VALUES(75, 'Faroe Islands', 'FO', 1);
INSERT INTO `tblcontries` VALUES(76, 'France', 'FR', 1);
INSERT INTO `tblcontries` VALUES(77, 'Gabon', 'GA', 1);
INSERT INTO `tblcontries` VALUES(78, 'United Kingdom', 'GB', 1);
INSERT INTO `tblcontries` VALUES(79, 'Grenada', 'GD', 1);
INSERT INTO `tblcontries` VALUES(80, 'Georgia', 'GE', 1);
INSERT INTO `tblcontries` VALUES(81, 'French Guiana', 'GF', 1);
INSERT INTO `tblcontries` VALUES(82, 'Guernsey', 'GG', 1);
INSERT INTO `tblcontries` VALUES(83, 'Ghana', 'GH', 1);
INSERT INTO `tblcontries` VALUES(84, 'Gibraltar', 'GI', 1);
INSERT INTO `tblcontries` VALUES(85, 'Greenland', 'GL', 1);
INSERT INTO `tblcontries` VALUES(86, 'The Gambia', 'GM', 1);
INSERT INTO `tblcontries` VALUES(87, 'Guinea', 'GN', 1);
INSERT INTO `tblcontries` VALUES(88, 'Guadeloupe', 'GP', 1);
INSERT INTO `tblcontries` VALUES(89, 'Equatorial Guinea', 'GQ', 1);
INSERT INTO `tblcontries` VALUES(90, 'Greece', 'GR', 1);
INSERT INTO `tblcontries` VALUES(91, 'South Georgia and South Sandwich Islands', 'GS', 1);
INSERT INTO `tblcontries` VALUES(92, 'Guatemala', 'GT', 1);
INSERT INTO `tblcontries` VALUES(93, 'Guam', 'GU', 1);
INSERT INTO `tblcontries` VALUES(94, 'Guinea-Bissau', 'GW', 1);
INSERT INTO `tblcontries` VALUES(95, 'Guyana', 'GY', 1);
INSERT INTO `tblcontries` VALUES(96, 'Hong Kong', 'HK', 1);
INSERT INTO `tblcontries` VALUES(97, 'Heard Island and McDonald Islands', 'HM', 1);
INSERT INTO `tblcontries` VALUES(98, 'Honduras', 'HN', 1);
INSERT INTO `tblcontries` VALUES(99, 'Croatia', 'HR', 1);
INSERT INTO `tblcontries` VALUES(100, 'Haiti', 'HT', 1);
INSERT INTO `tblcontries` VALUES(101, 'Hungary', 'HU', 1);
INSERT INTO `tblcontries` VALUES(102, 'Indonesia', 'ID', 1);
INSERT INTO `tblcontries` VALUES(103, 'Ireland', 'IE', 1);
INSERT INTO `tblcontries` VALUES(104, 'Israel', 'IL', 1);
INSERT INTO `tblcontries` VALUES(105, 'Isle of Man', 'IM', 1);
INSERT INTO `tblcontries` VALUES(106, 'India', 'IN', 1);
INSERT INTO `tblcontries` VALUES(107, 'British Indian Ocean Territory', 'IO', 1);
INSERT INTO `tblcontries` VALUES(108, 'Iraq', 'IQ', 1);
INSERT INTO `tblcontries` VALUES(109, 'Iran', 'IR', 1);
INSERT INTO `tblcontries` VALUES(110, 'Iceland', 'IS', 1);
INSERT INTO `tblcontries` VALUES(111, 'Italy', 'IT', 1);
INSERT INTO `tblcontries` VALUES(112, 'Jersey', 'JE', 1);
INSERT INTO `tblcontries` VALUES(113, 'Jamaica', 'JM', 1);
INSERT INTO `tblcontries` VALUES(114, 'Jordan', 'JO', 1);
INSERT INTO `tblcontries` VALUES(115, 'Japan', 'JP', 1);
INSERT INTO `tblcontries` VALUES(116, 'Kenya', 'KE', 1);
INSERT INTO `tblcontries` VALUES(117, 'Kyrgyzstan', 'KG', 1);
INSERT INTO `tblcontries` VALUES(118, 'Cambodia', 'KH', 1);
INSERT INTO `tblcontries` VALUES(119, 'Kiribati', 'KI', 1);
INSERT INTO `tblcontries` VALUES(120, 'Comoros', 'KM', 1);
INSERT INTO `tblcontries` VALUES(121, 'Saint Kitts and Nevis', 'KN', 1);
INSERT INTO `tblcontries` VALUES(122, 'North Korea', 'KP', 1);
INSERT INTO `tblcontries` VALUES(123, 'South Korea', 'KR', 1);
INSERT INTO `tblcontries` VALUES(124, 'Kuwait', 'KW', 1);
INSERT INTO `tblcontries` VALUES(125, 'Cayman Islands', 'KY', 1);
INSERT INTO `tblcontries` VALUES(126, 'Kazakhstan', 'KZ', 1);
INSERT INTO `tblcontries` VALUES(127, 'Laos', 'LA', 1);
INSERT INTO `tblcontries` VALUES(128, 'Lebanon', 'LB', 1);
INSERT INTO `tblcontries` VALUES(129, 'Saint Lucia', 'LC', 1);
INSERT INTO `tblcontries` VALUES(130, 'Liechtenstein', 'LI', 1);
INSERT INTO `tblcontries` VALUES(131, 'Sri Lanka', 'LK', 1);
INSERT INTO `tblcontries` VALUES(133, 'Liberia', 'LR', 1);
INSERT INTO `tblcontries` VALUES(134, 'Lesotho', 'LS', 1);
INSERT INTO `tblcontries` VALUES(135, 'Lithuania', 'LT', 1);
INSERT INTO `tblcontries` VALUES(136, 'Luxembourg', 'LU', 1);
INSERT INTO `tblcontries` VALUES(137, 'Latvia', 'LV', 1);
INSERT INTO `tblcontries` VALUES(138, 'Libya', 'LY', 1);
INSERT INTO `tblcontries` VALUES(139, 'Morocco', 'MA', 1);
INSERT INTO `tblcontries` VALUES(140, 'Monaco', 'MC', 1);
INSERT INTO `tblcontries` VALUES(141, 'Moldova', 'MD', 1);
INSERT INTO `tblcontries` VALUES(142, 'Montenegro', 'ME', 1);
INSERT INTO `tblcontries` VALUES(143, 'Saint Martin', 'MF', 1);
INSERT INTO `tblcontries` VALUES(144, 'Madagascar', 'MG', 1);
INSERT INTO `tblcontries` VALUES(145, 'Marshall Islands', 'MH', 1);
INSERT INTO `tblcontries` VALUES(148, 'Macedonia', 'MK', 1);
INSERT INTO `tblcontries` VALUES(149, 'Mali', 'ML', 1);
INSERT INTO `tblcontries` VALUES(150, 'Burma', 'MM', 1);
INSERT INTO `tblcontries` VALUES(151, 'Mongolia', 'MN', 1);
INSERT INTO `tblcontries` VALUES(152, 'Macau', 'MO', 1);
INSERT INTO `tblcontries` VALUES(153, 'Northern Mariana Islands', 'MP', 1);
INSERT INTO `tblcontries` VALUES(154, 'Martinique', 'MQ', 1);
INSERT INTO `tblcontries` VALUES(155, 'Mauritania', 'MR', 1);
INSERT INTO `tblcontries` VALUES(156, 'Montserrat', 'MS', 1);
INSERT INTO `tblcontries` VALUES(157, 'Malta', 'MT', 1);
INSERT INTO `tblcontries` VALUES(158, 'Mauritius', 'MU', 1);
INSERT INTO `tblcontries` VALUES(159, 'Maldives', 'MV', 1);
INSERT INTO `tblcontries` VALUES(160, 'Malawi', 'MW', 1);
INSERT INTO `tblcontries` VALUES(161, 'Mexico', 'MX', 1);
INSERT INTO `tblcontries` VALUES(162, 'Malaysia', 'MY', 1);
INSERT INTO `tblcontries` VALUES(163, 'Mozambique', 'MZ', 1);
INSERT INTO `tblcontries` VALUES(164, 'Namibia', 'NA', 1);
INSERT INTO `tblcontries` VALUES(165, 'New Caledonia', 'NC', 1);
INSERT INTO `tblcontries` VALUES(166, 'Niger', 'NE', 1);
INSERT INTO `tblcontries` VALUES(167, 'Norfolk Island', 'NF', 1);
INSERT INTO `tblcontries` VALUES(168, 'Nigeria', 'NG', 1);
INSERT INTO `tblcontries` VALUES(169, 'Nicaragua', 'NI', 1);
INSERT INTO `tblcontries` VALUES(170, 'Netherlands', 'NL', 1);
INSERT INTO `tblcontries` VALUES(171, 'Norway', 'NO', 1);
INSERT INTO `tblcontries` VALUES(172, 'Nepal', 'NP', 1);
INSERT INTO `tblcontries` VALUES(173, 'Nauru', 'NR', 1);
INSERT INTO `tblcontries` VALUES(174, 'Niue', 'NU', 1);
INSERT INTO `tblcontries` VALUES(175, 'New Zealand', 'NZ', 1);
INSERT INTO `tblcontries` VALUES(176, 'Oman', 'OM', 1);
INSERT INTO `tblcontries` VALUES(177, 'Panama', 'PA', 1);
INSERT INTO `tblcontries` VALUES(178, 'Peru', 'PE', 1);
INSERT INTO `tblcontries` VALUES(179, 'French Polynesia', 'PF', 1);
INSERT INTO `tblcontries` VALUES(180, 'Papua New Guinea', 'PG', 1);
INSERT INTO `tblcontries` VALUES(181, 'Philippines', 'PH', 1);
INSERT INTO `tblcontries` VALUES(182, 'Pakistan', 'PK', 1);
INSERT INTO `tblcontries` VALUES(183, 'Poland', 'PL', 1);
INSERT INTO `tblcontries` VALUES(184, 'Saint Pierre and Miquelon', 'PM', 1);
INSERT INTO `tblcontries` VALUES(185, 'Pitcairn Islands', 'PN', 1);
INSERT INTO `tblcontries` VALUES(186, 'Puerto Rico', 'PR', 1);
INSERT INTO `tblcontries` VALUES(187, 'Palesinian Territory', 'PS', 1);
INSERT INTO `tblcontries` VALUES(188, 'Portugal', 'PT', 1);
INSERT INTO `tblcontries` VALUES(189, 'Palau', 'PW', 1);
INSERT INTO `tblcontries` VALUES(190, 'Paraguay', 'PY', 1);
INSERT INTO `tblcontries` VALUES(191, 'Qatar', 'QA', 1);
INSERT INTO `tblcontries` VALUES(192, 'Reunion', 'RE', 1);
INSERT INTO `tblcontries` VALUES(193, 'Romania', 'RO', 1);
INSERT INTO `tblcontries` VALUES(194, 'Serbia', 'RS', 1);
INSERT INTO `tblcontries` VALUES(195, 'Russian Federation', 'RU', 1);
INSERT INTO `tblcontries` VALUES(196, 'Rwanda', 'RW', 1);
INSERT INTO `tblcontries` VALUES(197, 'Saudi Arabia', 'SA', 1);
INSERT INTO `tblcontries` VALUES(198, 'Solomon Islands', 'SB', 1);
INSERT INTO `tblcontries` VALUES(199, 'Seychelles', 'SC', 1);
INSERT INTO `tblcontries` VALUES(200, 'Sudan', 'SD', 1);
INSERT INTO `tblcontries` VALUES(201, 'Sweden', 'SE', 1);
INSERT INTO `tblcontries` VALUES(202, 'Singapore', 'SG', 1);
INSERT INTO `tblcontries` VALUES(203, 'Saint Helena', 'SH', 1);
INSERT INTO `tblcontries` VALUES(204, 'Slovenia', 'SI', 1);
INSERT INTO `tblcontries` VALUES(205, 'Svalbard', 'SJ', 1);
INSERT INTO `tblcontries` VALUES(206, 'Slovakia', 'SK', 1);
INSERT INTO `tblcontries` VALUES(207, 'Sierra Leone', 'SL', 1);
INSERT INTO `tblcontries` VALUES(208, 'San Marino', 'SM', 1);
INSERT INTO `tblcontries` VALUES(209, 'Senegal', 'SN', 1);
INSERT INTO `tblcontries` VALUES(210, 'Somalia', 'SO', 1);
INSERT INTO `tblcontries` VALUES(211, 'Suriname', 'SR', 1);
INSERT INTO `tblcontries` VALUES(212, 'Sao Tome and Principe', 'ST', 1);
INSERT INTO `tblcontries` VALUES(213, 'El Salvador', 'SV', 1);
INSERT INTO `tblcontries` VALUES(214, 'Syria', 'SY', 1);
INSERT INTO `tblcontries` VALUES(215, 'Swaziland', 'SZ', 1);
INSERT INTO `tblcontries` VALUES(216, 'Turks and Caicos Islands', 'TC', 1);
INSERT INTO `tblcontries` VALUES(217, 'Chad', 'TD', 1);
INSERT INTO `tblcontries` VALUES(218, 'French Southern and Antarctic Lands', 'TF', 1);
INSERT INTO `tblcontries` VALUES(219, 'Togo', 'TG', 1);
INSERT INTO `tblcontries` VALUES(220, 'Thailand', 'TH', 1);
INSERT INTO `tblcontries` VALUES(221, 'Tajikistan', 'TJ', 1);
INSERT INTO `tblcontries` VALUES(222, 'Tokelau', 'TK', 1);
INSERT INTO `tblcontries` VALUES(223, 'Timor-Leste', 'TL', 1);
INSERT INTO `tblcontries` VALUES(224, 'Turkmenistan', 'TM', 1);
INSERT INTO `tblcontries` VALUES(225, 'Tunisia', 'TN', 1);
INSERT INTO `tblcontries` VALUES(226, 'Tonga', 'TO', 1);
INSERT INTO `tblcontries` VALUES(227, 'Turkey', 'TR', 1);
INSERT INTO `tblcontries` VALUES(228, 'Trinidad and Tobago', 'TT', 1);
INSERT INTO `tblcontries` VALUES(229, 'Tuvalu', 'TV', 1);
INSERT INTO `tblcontries` VALUES(230, 'Taiwan', 'TW', 1);
INSERT INTO `tblcontries` VALUES(231, 'Tanzania', 'TZ', 1);
INSERT INTO `tblcontries` VALUES(232, 'Ukraine', 'UA', 1);
INSERT INTO `tblcontries` VALUES(233, 'Uganda', 'UG', 1);
INSERT INTO `tblcontries` VALUES(234, 'United States Minor Outlying Islands', 'UM', 1);
INSERT INTO `tblcontries` VALUES(235, 'Uruguay', 'UY', 1);
INSERT INTO `tblcontries` VALUES(236, 'Uzbekistan', 'UZ', 1);
INSERT INTO `tblcontries` VALUES(237, 'Vatican City', 'VA', 1);
INSERT INTO `tblcontries` VALUES(238, 'Saint Vincent and the Grenadines', 'VC', 1);
INSERT INTO `tblcontries` VALUES(239, 'Venezuela', 'VE', 1);
INSERT INTO `tblcontries` VALUES(240, 'British Virgin Islands', 'VG', 1);
INSERT INTO `tblcontries` VALUES(241, 'Virgin Islands', 'VI', 1);
INSERT INTO `tblcontries` VALUES(242, 'Vanuatu', 'VU', 1);
INSERT INTO `tblcontries` VALUES(243, 'Wallis and Futuna', 'WF', 1);
INSERT INTO `tblcontries` VALUES(244, 'Samoa', 'WS', 1);
INSERT INTO `tblcontries` VALUES(245, 'Yemen', 'YE', 1);
INSERT INTO `tblcontries` VALUES(246, 'Mayotte', 'YT', 1);
INSERT INTO `tblcontries` VALUES(247, 'South Africa', 'ZA', 1);
INSERT INTO `tblcontries` VALUES(248, 'Zambia', 'ZM', 1);
INSERT INTO `tblcontries` VALUES(249, 'Zimbabwe', 'ZW', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblsates`
--

CREATE TABLE `tblsates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `idcountry` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `tblsates`
--

INSERT INTO `tblsates` VALUES(21, 'an chung hon', 'ach', 4);
INSERT INTO `tblsates` VALUES(22, 'American Samoa', 'AS', 4);
INSERT INTO `tblsates` VALUES(23, 'Alabama', 'AL', 1);
INSERT INTO `tblsates` VALUES(24, 'Alaska', 'AK', 1);
INSERT INTO `tblsates` VALUES(25, 'Arizona', 'AZ', 1);
INSERT INTO `tblsates` VALUES(26, 'Arkansas', 'AR', 1);
INSERT INTO `tblsates` VALUES(27, 'California', 'CA', 1);
INSERT INTO `tblsates` VALUES(28, 'Colorado', 'CO', 1);
INSERT INTO `tblsates` VALUES(29, 'Connecticut', 'CT', 1);
INSERT INTO `tblsates` VALUES(30, 'Delaware', 'DE', 1);
INSERT INTO `tblsates` VALUES(31, 'District Of Columbia', 'DC', 1);
INSERT INTO `tblsates` VALUES(32, 'Florida', 'FL', 1);
INSERT INTO `tblsates` VALUES(33, 'Georgia', 'GA', 1);
INSERT INTO `tblsates` VALUES(34, 'Guam', 'GU', 1);
INSERT INTO `tblsates` VALUES(35, 'Hawaii', 'HI', 1);
INSERT INTO `tblsates` VALUES(36, 'Idaho', 'ID', 1);
INSERT INTO `tblsates` VALUES(37, 'Illinois', 'IL', 1);
INSERT INTO `tblsates` VALUES(38, 'Indiana', 'IN', 1);
INSERT INTO `tblsates` VALUES(39, 'Iowa', 'IA', 1);
INSERT INTO `tblsates` VALUES(40, 'Kansas', 'KS', 1);
INSERT INTO `tblsates` VALUES(41, 'Kentucky', 'KY', 1);
INSERT INTO `tblsates` VALUES(42, 'Louisiana', 'LA', 1);
INSERT INTO `tblsates` VALUES(43, 'Maine', 'ME', 1);
INSERT INTO `tblsates` VALUES(44, 'Marshall Islands', 'MH', 4);
INSERT INTO `tblsates` VALUES(45, 'Maryland', 'MD', 1);
INSERT INTO `tblsates` VALUES(46, 'Massachusetts', 'MA', 1);
INSERT INTO `tblsates` VALUES(47, 'Michigan', 'MI', 1);
INSERT INTO `tblsates` VALUES(48, 'Minnesota', 'MN', 1);
INSERT INTO `tblsates` VALUES(49, 'Micronesia', 'FM', 4);
INSERT INTO `tblsates` VALUES(50, 'Mississippi', 'MS', 1);
INSERT INTO `tblsates` VALUES(51, 'Missouri', 'MO', 1);
INSERT INTO `tblsates` VALUES(52, 'Montana', 'MT', 1);
INSERT INTO `tblsates` VALUES(53, 'Nebraska', 'NE', 1);
INSERT INTO `tblsates` VALUES(54, 'Nevada', 'NV', 1);
INSERT INTO `tblsates` VALUES(55, 'New Hampshire', 'NH', 1);
INSERT INTO `tblsates` VALUES(56, 'New Jersey', 'NJ', 1);
INSERT INTO `tblsates` VALUES(57, 'New Mexico', 'NM', 1);
INSERT INTO `tblsates` VALUES(58, 'New York', 'NY', 1);
INSERT INTO `tblsates` VALUES(59, 'North Carolina', 'NC', 1);
INSERT INTO `tblsates` VALUES(60, 'North Dakota', 'ND', 1);
INSERT INTO `tblsates` VALUES(61, 'Northern Mariana Islands', 'MP', 4);
INSERT INTO `tblsates` VALUES(62, 'Ohio', 'OH', 1);
INSERT INTO `tblsates` VALUES(63, 'Oklahoma', 'OK', 1);
INSERT INTO `tblsates` VALUES(64, 'Oregon', 'OR', 1);
INSERT INTO `tblsates` VALUES(65, 'Palau', 'PW', 4);
INSERT INTO `tblsates` VALUES(66, 'Pennsylvania', 'PA', 1);
INSERT INTO `tblsates` VALUES(67, 'Puerto Rico', 'PR', 1);
INSERT INTO `tblsates` VALUES(68, 'Rhode Island', 'RI', 1);
INSERT INTO `tblsates` VALUES(69, 'South Carolina', 'SC', 1);
INSERT INTO `tblsates` VALUES(70, 'South Dakota', 'SD', 1);
INSERT INTO `tblsates` VALUES(71, 'Tennessee', 'TN', 1);
INSERT INTO `tblsates` VALUES(72, 'Texas', 'TX', 1);
INSERT INTO `tblsates` VALUES(73, 'Utah', 'UT', 1);
INSERT INTO `tblsates` VALUES(74, 'Vermont', 'VT', 1);
INSERT INTO `tblsates` VALUES(75, 'Virginia', 'VA', 1);
INSERT INTO `tblsates` VALUES(76, 'Virgin Islands', 'VI', 1);
INSERT INTO `tblsates` VALUES(77, 'Washington', 'WA', 1);
INSERT INTO `tblsates` VALUES(78, 'West Virginia', 'WV', 1);
INSERT INTO `tblsates` VALUES(79, 'Wisconsin', 'WI', 1);
INSERT INTO `tblsates` VALUES(80, 'Wyoming', 'WY', 1);
INSERT INTO `tblsates` VALUES(81, 'Alberta', 'AB', 2);
INSERT INTO `tblsates` VALUES(82, 'British Columbia', 'BC', 2);
INSERT INTO `tblsates` VALUES(83, 'Manitoba', 'MB', 2);
INSERT INTO `tblsates` VALUES(84, 'Newfoundland', 'NF', 2);
INSERT INTO `tblsates` VALUES(85, 'New Brunswick', 'NB', 2);
INSERT INTO `tblsates` VALUES(86, 'Nova Scotia', 'NS', 2);
INSERT INTO `tblsates` VALUES(87, 'Northwest Territories', 'NT', 2);
INSERT INTO `tblsates` VALUES(88, 'Nunavut', 'NU', 2);
INSERT INTO `tblsates` VALUES(89, 'Ontario', 'ON', 2);
INSERT INTO `tblsates` VALUES(90, 'Prince Edward Island', 'PE', 2);
INSERT INTO `tblsates` VALUES(91, 'Quebec', 'QC', 2);
INSERT INTO `tblsates` VALUES(92, 'Saskatchewan', 'SK', 2);
INSERT INTO `tblsates` VALUES(93, 'Yukon Territory', 'YT', 2);
INSERT INTO `tblsates` VALUES(94, 'Nam Dinh', 'NĐ', 3);
INSERT INTO `tblsates` VALUES(95, 'TPHCM', 'hcm', 3);
INSERT INTO `tblsates` VALUES(97, '1', '2', 33);
INSERT INTO `tblsates` VALUES(98, 'RETRTR', '34', 249);
INSERT INTO `tblsates` VALUES(99, 'Ha Noi', 'HN', 3);
INSERT INTO `tblsates` VALUES(100, 'california', 'g', 6);
INSERT INTO `tblsates` VALUES(101, 'gfdsgf', 'dfgd', 6);

-- --------------------------------------------------------

--
-- Table structure for table `title`
--

CREATE TABLE `title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tkey` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `weight` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tkey` (`tkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `title`
--

INSERT INTO `title` VALUES(1, 'Q5CyBlExhshUpaodh9wg', 'Owner', '', 37, 1);
INSERT INTO `title` VALUES(2, 'QER8eLMvv3UzBBQVZZFM', 'CEO', '', 35, 1);
INSERT INTO `title` VALUES(3, '7uvkci44LXe3jHGQOMwM', 'President', '', 34, 1);
INSERT INTO `title` VALUES(5, 'vG21Me4I4SFk2G7JXgZf', 'Manager', '', 0, 1);
INSERT INTO `title` VALUES(4, '2tJsUfsnXRXALfUTfXIh', 'Vice President', 'sdf asdfad', 28, -1);
INSERT INTO `title` VALUES(6, 'KgKsvQFsPvE3NxoOARcH', 'Vice President', '', 0, 1);
INSERT INTO `title` VALUES(7, 'V9lotVUo9x9N9wRrjR9G', 'Executive Director', '', 33, 1);
INSERT INTO `title` VALUES(8, 'LHYSBsOnuxYzZIn9DjG7', 'Chairman', '', 32, 1);
INSERT INTO `title` VALUES(9, 'oKcN6orG33EvfFdjmIGK', 'Director', 'Director', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ukey` varchar(20) DEFAULT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `pass` varchar(200) NOT NULL DEFAULT '',
  `mail` varchar(100) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zipcode` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'US',
  `created` int(11) DEFAULT '0',
  `access` int(11) DEFAULT '0',
  `login` int(11) DEFAULT '0',
  `role` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:pending,2:approved,3:rejected,4:pending Registration , 5=Deactivated',
  `picture` varchar(255) DEFAULT '',
  `data` longtext,
  `efin` varchar(100) NOT NULL,
  `ptin` varchar(100) NOT NULL,
  `bookmark` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `ukey` (`ukey`),
  KEY `access` (`access`),
  KEY `created` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=186 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(163, 'cMxIT6RKifgZUbXtZ4Gt', 'tom', 'czozOiJ0b20iOw==', 'tom@tom.com', '', NULL, '', '(654) 315-3213', NULL, '', '', '', '', 'US', 1398957881, 1398957881, 1398957882, '', 4, '', NULL, '321564', '', 0);
INSERT INTO `users` VALUES(164, 'FG1_kkBmWYUFTAe2GkQp', 'jerry', 'czo1OiJqZXJyeSI7', 'jerry@tom.com', '', NULL, '', '(631) 534-3213', NULL, '', '', '', '', 'US', 1398957951, 1398957951, 1398957951, '', 4, '', NULL, '954632', '', 0);
INSERT INTO `users` VALUES(1, '9fMTmMDufIOsCOPmKu2D', 'admin', 'czoxMToiYWRtaW4xMjM0NTYiOw==', 'anifmomin@yahoo.com', 'Test', '', 'test 3', '1234567890', '', '', '', '', '', '', 1410746047, 1410779265, 1410779265, '', 0, '', '1', '1234567', '', 1);
INSERT INTO `users` VALUES(147, 'TKdHhfNmbgvW53g8bCrq', 'test2', 'czo1OiJ0ZXN0MiI7', 'test2@example.com', 'Test', NULL, '2', '8979834533', NULL, '', '', '', '', 'US', 1404422920, 1404647436, 1404647436, '', 2, '', '1', '234567', '', 0);
INSERT INTO `users` VALUES(149, 'oYz1obTo1igivmzMQIRN', 'anifmomin', 'czo1OiJtb21pbiI7', 'anifmomin@yahoo.com', 'Test ', NULL, '3', '1234567890', NULL, '', '', '', '', 'US', 1397287496, 1397287496, 1397287496, '', 4, '', '1', '111111', '', 0);
INSERT INTO `users` VALUES(159, 'LPEt5MizjXhX40OZajc_', 'mominmomin', 'czo1OiJtb21pbiI7', 'rahanasunesara@gmail.com', '', NULL, '', '(123) 455-6789', NULL, '', '', '', '', 'US', 1398525252, 1398525252, 1398525253, '', 4, '', NULL, '987896', '', 0);
INSERT INTO `users` VALUES(151, 'ge0l1pSDsSmxbmtOc0gp', 'anifm', 'czozOiIxMjMiOw==', 'anifmomi@gmail.com', '', NULL, '', '123', NULL, '', '', '', '', 'US', 1397589890, 1397589890, 1397589890, '', 4, '', NULL, '123456', '', 0);
INSERT INTO `users` VALUES(152, 'nurjrZvVHOmlfaILb5cy', 'momin', 'czo1OiJhbmlmbSI7', 'a@a.com', '', NULL, '', '123', NULL, '', '', '', '', 'US', 1397590429, 1397590429, 1397590429, '', 4, '', NULL, '123458', '', 0);
INSERT INTO `users` VALUES(153, 'QVgFg2bUemZeUAfOCE5b', 'rahana', 'czo2OiJyYWhhbmEiOw==', 'a@gmail.com', '', NULL, '', '123', NULL, '', '', '', '', 'US', 1397591370, 1397591370, 1397591399, '', 4, '', '1', '777888', '', 0);
INSERT INTO `users` VALUES(154, 'Sz8MwSFW6TRBwQAgBKlH', 'abcd', 'czo0OiJhYmNkIjs=', 'a@b.com', '', NULL, '', '233', NULL, '', '', '', '', 'US', 1410739650, 1410739650, 1410739650, '', 4, '', '1', '000000', '', 0);
INSERT INTO `users` VALUES(144, 'gAAp39gi3gApx1E0qH6y', 'zishan', 'czo2OiJ6aXNoYW4iOw==', 'zishanmomin@gmail.com', 'Zishan', NULL, 'momin', '(344) 444-4444', NULL, '', '', '', '', 'US', 1405852407, 1410408876, 1410408876, '', 1, '', '1', '888888', '', 0);
INSERT INTO `users` VALUES(145, 'L_rhGxH7NA6TIY09dmZI', 'shuvro', 'czo2OiJzaHV2cm8iOw==', 'shuvro@osourcebd.com', 'Shuvro', NULL, 'Ahmed', '3243423423', NULL, '', '', '', '', 'US', 1403677747, 1410417848, 1410417848, '', 1, '', '1', '987654', '', 0);
INSERT INTO `users` VALUES(146, '7DiUZIvcivtp2RXYtmTc', 'test1', 'czo1OiJ0ZXN0MSI7', 'ero@example.com', 'Test', NULL, '1', '3423434534', NULL, '', '', '', '', 'US', 1397173657, 1397878987, 1397878987, '', 4, '', '1', '456789', '', 0);
INSERT INTO `users` VALUES(148, 'Uto8VpTK6tF6p2Vtlog1', 'anif', 'czo4OiJSYWhhbmExMiI7', 'anifmomin@gmail.com', '', NULL, '', '1234567890', NULL, '', '', '', '', 'US', 1397286163, 1397286163, 1397286163, '', 4, '', NULL, '852963', '', 0);
INSERT INTO `users` VALUES(162, NULL, 'anif', 'czo1OiIxMjM0NSI7', 'anif@anif.com', 'anif', NULL, 'momin', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1398717659, 1398717659, 1398717659, '', 0, '', NULL, '', '12345', 0);
INSERT INTO `users` VALUES(161, NULL, '', 'czowOiIiOw==', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1398717409, 1398717409, 1398717409, '', 0, '', NULL, '', '2', 0);
INSERT INTO `users` VALUES(160, 'NKFQm0wVm9LD8XH8QUaJ', 'rahanas', 'czo2OiJyYWhhbmEiOw==', 'sunesara@bcm.edu', 'afdad', NULL, 'Momin', '(546) 345-3453', NULL, '', '', '', '', 'US', 1398716995, 1398716995, 1398716995, '', 4, '', '1', '345678', '', 0);
INSERT INTO `users` VALUES(158, 'ESMy6Sph0FIPSNgS26kF', 'abcdef', 'czo0OiJhYmNkIjs=', 'anif@anif.com', '', NULL, '', '(231) 231-3123', NULL, '', '', '', '', 'US', 1398525171, 1398525171, 1398525171, '', 4, '', NULL, '232132', '', 0);
INSERT INTO `users` VALUES(157, 'uf7Ut56Rc9up3SKT3KE6', 'fmomin', 'czo4OiJzbmFrZXllcyI7', 'fahimmomin@outlook.com', 'Fahim', NULL, 'Momin', '(281) 999-9999', NULL, '', '', '', '', 'US', 1398525155, 1399985898, 1399985898, '', 4, '', '1', '346987', '', 0);
INSERT INTO `users` VALUES(165, 'xeuFmtaSZ2S_bilY76tQ', 'ztom', 'czo0OiJ6dG9tIjs=', 'ztom@z.com', 'asdf', NULL, 'adf', '(653) 531-2152', NULL, '', '', '', '', 'US', 1398959444, 1398959444, 1398959444, '', 4, '', '1', '635946', '', 0);
INSERT INTO `users` VALUES(166, 'QVeUeJ6xKOmBvlDDxqzt', 'tycman', 'czo2OiIxMjM0NTYiOw==', 'tyc@tyc.com', '', NULL, '', '(626) 131-5132', NULL, '', '', '', '', 'US', 1398958141, 1398958141, 1398958141, '', 4, '', NULL, '965321', '', 0);
INSERT INTO `users` VALUES(167, 'Pc_Bw_ljBCPDnXpGNx5H', 'anifmo', 'czo2OiJhbmlmbW8iOw==', 'a@ab.com', 'Anif', NULL, 'Momin', '(456) 132-1341', NULL, '', '', '', '', 'US', 1399471016, 1399471016, 1399471016, '', 4, '', '1', '963852', '', 0);
INSERT INTO `users` VALUES(168, 'rW7uK3IGOmqT6RH1KjJP', 'test', 'czo0OiJ0ZXN0Ijs=', 'test@test.com', 'anif', NULL, 'Momin', '(232) 232-4234', NULL, '', '', '', '', 'US', 1400082245, 1400082245, 1400082245, '', 4, '', '1', '121211', '', 0);
INSERT INTO `users` VALUES(169, NULL, 'anifmer', 'czo2OiIxMjM0NTYiOw==', 'test@test.com', 'anif', NULL, 'momin', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1400083752, 1400083752, 1400083752, '', 0, '', NULL, '', '231231', 0);
INSERT INTO `users` VALUES(170, NULL, 'anifm1', 'czo2OiIxMjM0NTYiOw==', 'test@test.com', 'anif', NULL, 'momin', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1400083798, 1400083798, 1400083798, '', 0, '', NULL, '', '123456', 0);
INSERT INTO `users` VALUES(171, NULL, 'test4', 'czo1OiJ0ZXN0NCI7', 'test4@test.com', 'test4', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1400107577, 1400107577, 1400107577, '', 0, '', NULL, '', '23423', 0);
INSERT INTO `users` VALUES(172, 'ZfSL0XyG1xsOnR44WViI', 'SamPaiz', 'czo4OiJXZWxjb21lMSI7', 'spaiz@lmsgroup.com', 'Sam', NULL, 'Paiz', '(281) 977-6517', NULL, '', '', '', '', 'US', 1400689680, 1400691626, 1400691626, '', 2, '', '1', '098767', '9999999', 0);
INSERT INTO `users` VALUES(173, NULL, 'dinaP', 'czo1OiJEaW5hUCI7', 'dina23423@yahoo.com', 'Dina', NULL, 'Paiz', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1400691439, 1400691439, 1400691439, '', 0, '', NULL, '', '12345566', 0);
INSERT INTO `users` VALUES(174, 'Mcc6ot1yuWOsa32M0zg7', 'DP', 'czo4OiJXZWxjb21lMSI7', 'DinaPaiz@yahoo.cvom', '', NULL, '', '(123) 121-2312', NULL, '', '', '', '', 'US', 1400691516, 1400691516, 1400691517, '', 4, '', NULL, '123412', '', 0);
INSERT INTO `users` VALUES(175, 'lKU5CCKvM4OKt2MX2J4u', 'ajaym', 'czo1OiJhamF5bSI7', 'a@bcd.com', 'anif', NULL, 'momin', '(233) 221-3121', NULL, '', '', '', '', 'US', 1400789908, 1400789908, 1400789908, '', 4, '', '1', '342234', '', 0);
INSERT INTO `users` VALUES(176, 'r9D1Zu9W2fx7fHgsh4iC', 'abcdef3', 'czo1OiJhYmNkZSI7', 'af@bcd.com', 'adf', NULL, 'df', '(234) 234-2342', NULL, '', '', '', '', 'US', 1400791166, 1400791166, 1400791166, '', 1, '', '1', '434534', '', 0);
INSERT INTO `users` VALUES(177, NULL, 'anifm34', 'czo1OiJtb21pbiI7', 'anifmomin@gmail.com', 'anif', NULL, 'momin', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1400791232, 1400791232, 1400791232, '', 0, '', NULL, '', '23123', 0);
INSERT INTO `users` VALUES(178, '6p6Ff8kqX9zuQj0DNTuP', 'Nim', 'czo4OiJXZWxjb21lMSI7', 'nim@yahoo.com', '', NULL, '', '(281) 687-0092', NULL, '', '', '', '', 'US', 1402517861, 1402517861, 1402517861, '', 4, '', NULL, '777777', '', 0);
INSERT INTO `users` VALUES(179, 'O5s8VcTvDuugwgpnbQww', 'zmomin', 'czo2OiIxMjM0NTYiOw==', 'shawn.momin@gmail.com', 'John', NULL, 'Doe', '(832) 858-1144', NULL, '', '', '', '', 'US', 1403939587, 1404275929, 1404275929, '', 1, '', '1', '098765', '', 0);
INSERT INTO `users` VALUES(180, NULL, 'shuvro1', 'czo3OiJzaHV2cm8xIjs=', 'shuvrovai@shuvro.com', 'Shuvro', NULL, 'ah', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1404334128, 1404334128, 1404334128, '', 0, '', NULL, '', '123456', 0);
INSERT INTO `users` VALUES(181, NULL, 'shuvro2', 'czo3OiJzaHV2cm8yIjs=', 'shuvrovai@shuvro.com', 'Shuvro', NULL, 'ah', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1404334152, 1404334152, 1404334152, '', 0, '', NULL, '', '123456', 0);
INSERT INTO `users` VALUES(182, NULL, 'shuvro3', 'czo2OiIxMjM0NTMiOw==', 'shuvro2@shuvro.com', 'shuvro2', NULL, 'ah', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1404335112, 1404335112, 1404335112, '', 0, '', NULL, '', '123456', 0);
INSERT INTO `users` VALUES(183, NULL, 'shuvro4', 'czo2OiIxMjM0NTQiOw==', 'shuvro2@shuvro.com', 'shuvro2', NULL, 'ah', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1404335188, 1404335188, 1404335188, '', 0, '', NULL, '', '565657', 0);
INSERT INTO `users` VALUES(184, '9nZpFN0KTgATEk7VNNhy', 'zmomin789', 'czoxMToiemlzaGFubTBtaW4iOw==', 'shawn@igotblinds.com', '', NULL, '', '(374) 727-1666', NULL, '', '', '', '', 'US', 1410417337, 1410417337, 1410417337, '', 4, '', '1', '984736', '', 0);
INSERT INTO `users` VALUES(185, NULL, '', 'czowOiIiOw==', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 1410417162, 1410417162, 1410417162, '', 0, '', NULL, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_permission`
--

CREATE TABLE `users_permission` (
  `uid` int(10) unsigned NOT NULL COMMENT 'Foreign Key: users.uid.',
  `permission` varchar(255) NOT NULL DEFAULT '' COMMENT 'A single permission granted to the users identified by uid.',
  `controller` varchar(255) NOT NULL DEFAULT '' COMMENT 'The controllers declaring the permission.',
  `functions` text,
  PRIMARY KEY (`uid`,`permission`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the permissions assigned to users.';

--
-- Dumping data for table `users_permission`
--


-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`rid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` VALUES(1, 3);
INSERT INTO `users_roles` VALUES(144, 5);
INSERT INTO `users_roles` VALUES(145, 5);
INSERT INTO `users_roles` VALUES(146, 5);
INSERT INTO `users_roles` VALUES(147, 5);
INSERT INTO `users_roles` VALUES(148, 5);
INSERT INTO `users_roles` VALUES(149, 5);
INSERT INTO `users_roles` VALUES(150, 6);
INSERT INTO `users_roles` VALUES(151, 5);
INSERT INTO `users_roles` VALUES(152, 5);
INSERT INTO `users_roles` VALUES(153, 5);
INSERT INTO `users_roles` VALUES(154, 5);
INSERT INTO `users_roles` VALUES(155, 6);
INSERT INTO `users_roles` VALUES(156, 6);
INSERT INTO `users_roles` VALUES(157, 5);
INSERT INTO `users_roles` VALUES(158, 5);
INSERT INTO `users_roles` VALUES(159, 5);
INSERT INTO `users_roles` VALUES(160, 5);
INSERT INTO `users_roles` VALUES(161, 6);
INSERT INTO `users_roles` VALUES(162, 6);
INSERT INTO `users_roles` VALUES(163, 5);
INSERT INTO `users_roles` VALUES(164, 5);
INSERT INTO `users_roles` VALUES(165, 5);
INSERT INTO `users_roles` VALUES(166, 5);
INSERT INTO `users_roles` VALUES(167, 5);
INSERT INTO `users_roles` VALUES(168, 5);
INSERT INTO `users_roles` VALUES(169, 6);
INSERT INTO `users_roles` VALUES(170, 6);
INSERT INTO `users_roles` VALUES(171, 6);
INSERT INTO `users_roles` VALUES(172, 5);
INSERT INTO `users_roles` VALUES(173, 5);
INSERT INTO `users_roles` VALUES(174, 5);
INSERT INTO `users_roles` VALUES(175, 5);
INSERT INTO `users_roles` VALUES(176, 5);
INSERT INTO `users_roles` VALUES(177, 5);
INSERT INTO `users_roles` VALUES(178, 5);
INSERT INTO `users_roles` VALUES(179, 5);
INSERT INTO `users_roles` VALUES(183, 5);
INSERT INTO `users_roles` VALUES(184, 5);
INSERT INTO `users_roles` VALUES(185, 6);

-- --------------------------------------------------------

--
-- Table structure for table `web_content`
--

CREATE TABLE `web_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_content` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `web_content`
--

INSERT INTO `web_content` VALUES(1, 'charity', '', '<p>\\n	BVN Relationship Network is not just another innovation or just another business; we truly care and include all of our Affiliates who are Consumers, Vendors, Business Owners, and Employees through our channel to participate in our Charity Mission.</p>\\n<p>\\n	In BVN Relationship Network, we consider donating some percentages of the revenues to some charity organizations, to help the poorest of the poor, the helpless victims of natural disasters, the elders and orphans who are neglected by society and many more. Together, we are all blessed for giving back, for sharing and mostly for caring. Together, we can make a difference to the less fortunate in the world. Thank you for being Part of our journey with BVN Relationship Network of Bella Vie Network, LLC.</p>');
INSERT INTO `web_content` VALUES(2, 'business', '', '<div class=\\"heading\\">\\n	<h3>\\n		Code Of Ethics</h3>\\n</div>\\n<div>\\n	<p style=\\"color: #FE8F01;text-transform:uppercase;margin:15px 0 10px;\\">\\n		As a BVN Independent Representative:</p>\\n	<ul>\\n		<li>\\n			I will constantly strive to build my business on a foundation of integrity, respect and responsibility.</li>\\n		<li>\\n			I will, to the best of my ability, protect the reputation and image of BVN, LLC. with honor, transparency and consistency.</li>\\n		<li>\\n			I will uphold, support and advocate BVN&#39;s Policies &amp; Procedures, as well as the intent in which they were written.</li>\\n		<li>\\n			I will not engage in any unlawful, unethical or deceptive consumer or recruiting practice that may reflect poorly on BVN, LLC.., the direct sales industry or me.</li>\\n		<li>\\n			I will continually strive to represent BVN&#39;s products and services to my customers and prospective Independent Representatives realistically, never misleading or making false claims. All claims, representations or statements that I make will be only those included in the BVN literature.</li>\\n		<li>\\n			I will fulfill commitments I make to my customers, fellow Independent representatives and BVN Company.</li>\\n		<li>\\n			I will communicate the BVN marketing opportunity to prospects with the highest level of integrity and honesty. I will not misrepresent actual or potential sales earnings. I realize that an individual&#39;s success is accomplished through his or her own individual efforts.</li>\\n		<li>\\n			I will maintain a basic loyalty and professionalism to the direct selling industry as a whole and to BVN, in no way discriminating against others or engaging in criticism of other direct selling companies.</li>\\n		<li>\\n			I will abide by local, state and federal laws, as well as BVN&#39;s Policies &amp; Procedures as they may be amended from time to time.</li>\\n		<li>\\n			I will uphold BVN&#39;s Code of Ethics and BVN&#39;s Policies &amp; Procedures. I will not attempt to persuade or compel another party to breach the aforementioned ethical statements. I realize that any such action is considered a violation of this Code of Ethics.</li>\\n	</ul>\\n</div>\\n<div class=\\"heading\\">\\n	<h3>\\n		Policies And Procedures</h3>\\n	<div>\\n		<strong>Bella Vie Network, LLC</strong><br />\\n		&nbsp;<br />\\n		<strong>STATEMENT OF POLICIES</strong><br />\\n		<strong><em>and</em></strong><br />\\n		<strong>PROCEDURES</strong><br />\\n		<strong><em>Effective __________</em></strong><br />\\n		&nbsp;<br />\\n		<strong>TABLE OF CONTENTS</strong><br />\\n		&nbsp;<br />\\n		<a href=\\"#_Toc333246781\\">SECTION 1 &ndash; COMPANY MISSION STATEMENT.. 1</a><br />\\n		<a href=\\"#_Toc333246782\\">SECTION 2 - INTRODUCTION.. 1</a><br />\\n		<a href=\\"#_Toc333246783\\"><strong>2.1 - Policies and Compensation Plan Incorporated into Member Agreement</strong>. 1</a><br />\\n		<a href=\\"#_Toc333246784\\"><strong>2.2 - Changes to the Agreement</strong>. 1</a><br />\\n		<a href=\\"#_Toc333246785\\"><strong>2.3 - Policies and Provisions Severable</strong>. 2</a><br />\\n		<a href=\\"#_Toc333246786\\"><strong>2.4 - Waiver</strong>.. 2</a><br />\\n		<a href=\\"#_Toc333246787\\">SECTION 3 - BECOMING A MEMBER.. 2</a><br />\\n		<a href=\\"#_Toc333246788\\"><strong>3.1 - Requirements to Become a Member</strong>.. 2</a><br />\\n		<a href=\\"#_Toc333246789\\"><strong>3.2 - Product Purchases</strong>. 2</a><br />\\n		<a href=\\"#_Toc333246790\\"><strong>3.3 - Member Benefits</strong>. 2</a><br />\\n		<a href=\\"#_Toc333246791\\"><strong>3.4 - Term and Renewal of Your Bella Vie Network Business</strong>. 3</a><br />\\n		<a href=\\"#_Toc333246792\\">SECTION 4 - OPERATING A Bella Vie Network BUSINESS. 3</a><br />\\n		<a href=\\"#_Toc333246793\\"><strong>4.1 - Adherence to the Bella Vie Network Compensation Plan</strong>.. 3</a><br />\\n		<a href=\\"#_Toc333246794\\"><strong>4.2 - Advertising</strong>.. 3</a><br />\\n		<a href=\\"#_Toc333246795\\"><strong>4.2.1 - General</strong> 3</a><br />\\n		<a href=\\"#_Toc333246796\\"><strong>4.2.2 - Trademarks and Copyrights</strong>. 4</a><br />\\n		<a href=\\"#_Toc333246798\\"><strong>4.2.3 - Media and Media Inquiries</strong>. 4</a><br />\\n		<a href=\\"#_Toc333246799\\"><strong>4.2.4 - Unsolicited Email</strong> 5</a><br />\\n		<a href=\\"#_Toc333246800\\"><strong>4.2.5 - Unsolicited Faxes</strong>. 5</a><br />\\n		<a href=\\"#_Toc333246801\\"><strong>4.2.6 - Telephone Directory Listings</strong>. 5</a><br />\\n		<a href=\\"#_Toc333246804\\"><strong>4.2.7 - Television and Radio Advertising</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246805\\"><strong>4.2.8 - Advertised Prices</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246806\\"><strong>4.3.0 - Online Conduct</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246818\\"><strong>4.3.1 - Online Classifieds</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246820\\"><strong>4.3.2 - eBay / Online Auctions</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246821\\"><strong>4.3.3 - Online Retailing</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246824\\"><strong>4.3.4 - Spam Linking</strong>. 6</a><br />\\n		<a href=\\"#_Toc333246826\\"><strong>4.3.5 - Digital Media Submission (YouTube, iTunes, PhotoBucket etc.)</strong> 7</a><br />\\n		<a href=\\"#_Toc333246828\\"><strong>4.3.6 - Domain Names and Email Addresses</strong>. 7</a><br />\\n		<a href=\\"#_Toc333246829\\"><strong>4.3.7 - Social Media</strong>. 7</a><br />\\n		<a href=\\"#_Toc333246851\\"><strong>4.4.0 - Business Entities</strong>. 8</a><br />\\n		<a href=\\"#_Toc333246853\\"><strong>4.5.0 - Change of Sponsor</strong>.. 8</a><br />\\n		<a href=\\"#_Toc333246857\\"><strong>4.6.0 - Waiver of Claims</strong>. 8</a><br />\\n		<a href=\\"#_Toc333246859\\"><strong>4.7.0 - Unauthorized Claims and Actions</strong>. 9</a><br />\\n		<a href=\\"#_Toc333246860\\"><strong>4.7.1 - Indemnification</strong>. 9</a><br />\\n		<a href=\\"#_Toc333246861\\"><strong>4.7.2 - Product Claims</strong>. 9</a><br />\\n		<a href=\\"#_Toc333246862\\"><strong>4.7.3 - Compensation Plan Claims</strong>. 9</a><br />\\n		<a href=\\"#_Toc333246872\\"><strong>4.7.4 - Income Claims</strong>. 10</a><br />\\n		<a href=\\"#_Toc333246874\\"><strong>4.7.5 - Income Disclosure Statement</strong> 10</a><br />\\n		<a href=\\"#_Toc333246876\\"><strong>4.8.0 - Repackaging and Re-labeling Prohibited</strong>.. 11</a><br />\\n		<a href=\\"#_Toc333246877\\"><strong>4.9.0 - Commercial Outlets</strong>. 11</a><br />\\n		<a href=\\"#_Toc333246879\\"><strong>4.10 - Conflicts of Interest</strong>. 11</a><br />\\n		<a href=\\"#_Toc333246880\\"><strong>4.10.1- Nonsolicitation</strong>. 11</a><br />\\n		<a href=\\"#_Toc333246881\\"><strong>4.10.2 - Member Participation in Other Network Marketing Programs</strong>. 12</a><br />\\n		<a href=\\"#_Toc333246882\\"><strong>4.10.3 - Confidential Information</strong>. 12</a><br />\\n		<a href=\\"#_Toc333246883\\"><strong>4.11.0 - Targeting Other Direct Sellers</strong>. 13</a><br />\\n		<a href=\\"#_Toc333246884\\"><strong>4.12.0 - Errors or Questions</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246885\\"><strong>4.13 - Governmental Approval or Endorsement</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246886\\"><strong>4.14 - Holding Applications or Orders</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246887\\"><strong>4.15 - Income Taxes</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246888\\"><strong>4.16 - Independent Contractor Status</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246890\\"><strong>4.17 - International</strong> <strong>Marketing</strong>.. 14</a><br />\\n		<a href=\\"#_Toc333246892\\"><strong>4.18 - Adherence to Laws and Ordinances</strong>. 14</a><br />\\n		<a href=\\"#_Toc333246898\\"><strong>4.19 - Separation of a Bella Vie Network Business</strong>. 15</a><br />\\n		<a href=\\"#_Toc333246899\\"><strong>4.20 - Sponsoring Online</strong>. 15</a><br />\\n		<a href=\\"#_Toc333246903\\"><strong>4.21 - Telemarketing Techniques</strong>. 15</a><br />\\n		<a href=\\"#_Toc333246904\\"><strong>4.22 - Back Office Access</strong>. 16</a><br />\\n		<a href=\\"#_Toc333246906\\">SECTION 5 - RESPONSIBILITIES OF MEMBERS. 17</a><br />\\n		<a href=\\"#_Toc333246907\\"><strong>5.1 - Change of Address, Telephone, and E-Mail Addresses</strong>. 17</a><br />\\n		<a href=\\"#_Toc333246908\\"><strong>5.2 - Continuing Development Obligations</strong>. 17</a><br />\\n		<a href=\\"#_Toc333246909\\"><strong>5.2.1 - Ongoing Training</strong>. 17</a><br />\\n		<a href=\\"#_Toc333246910\\"><strong>5.2.2 - Increased Training Responsibilities</strong>. 17</a><br />\\n		<a href=\\"#_Toc333246911\\"><strong>5.2.3 - Ongoing Sales Responsibilities</strong>. 17</a><br />\\n		<a href=\\"#_Toc333246912\\"><strong>5.3 - Nondisparagement</strong>. 18</a><br />\\n		<a href=\\"#_Toc333246913\\"><strong>5.4 - Providing Documentation to Applicants</strong>. 18</a><br />\\n		<a href=\\"#_Toc333246914\\">SECTION 6 - SALES REQUIREMENTS. 18</a><br />\\n		<a href=\\"#_Toc333246915\\"><strong>6.1 - Product Sales</strong>. 18</a><br />\\n		<a href=\\"#_Toc333246916\\"><strong>6.2 - No Territory Restrictions</strong>. 18</a><br />\\n		<a href=\\"#_Toc333246917\\"><strong>6.3 - Sales Receipts</strong>. 18</a><br />\\n		<a href=\\"#_Toc333246918\\">SECTION 7 - BONUSES AND COMMISSIONS. 19</a><br />\\n		<a href=\\"#_Toc333246919\\"><strong>7.1 - Bonus and Commission Qualifications and Accrual</strong>. 19</a><br />\\n		<a href=\\"#_Toc333246920\\"><strong>7.2 - Adjustment to Bonuses and Commissions</strong>. 19</a><br />\\n		<a href=\\"#_Toc333246921\\"><strong>7.2.1 - Adjustments for Returned Products and Cancelled Services</strong>. 19</a><br />\\n		<a href=\\"#_Toc333246922\\"><strong>7.2.2 - Commissions</strong>. 19</a><br />\\n		<a href=\\"#_Toc333246924\\"><strong>7.2.3 - Tax Withholdings</strong>. 20</a><br />\\n		<a href=\\"#_Toc333246925\\"><strong>7.3 - Reports</strong>. 20</a><br />\\n		<a href=\\"#_Toc333246926\\">SECTION 8 - PRODUCT GUARANTEES, RETURNS AND INVENTORY REPURCHASE.. 21</a><br />\\n		<a href=\\"#_Toc333246927\\"><strong>8.1 - Rescission</strong>.. 21</a><br />\\n		<a href=\\"#_Toc333246928\\"><strong>8.2 - Returns by Retail Customers</strong>. 21</a><br />\\n		<a href=\\"#_Toc333246929\\"><strong>8.3 - Montana Residents</strong>. 21</a><br />\\n		<a href=\\"#_Toc333246931\\"><strong>8.4 - Procedures for All Returns</strong>. 21</a><br />\\n		<a href=\\"#_Toc333246932\\">SECTION 9 - DISPUTE RESOLUTION AND DISCIPLINARY PROCEEDINGS. 22</a><br />\\n		<a href=\\"#_Toc333246933\\"><strong>9.1 - Disciplinary Sanctions</strong>. 22</a><br />\\n		<a href=\\"#_Toc333246934\\"><strong>9.2 - Grievances and Complaints</strong>. 23</a><br />\\n		<a href=\\"#_Toc333246935\\"><strong>9.3 - Mediation</strong>.. 23</a><br />\\n		<a href=\\"#_Toc333246937\\"><strong>9.4 - Arbitration</strong>.. 23</a><br />\\n		<a href=\\"#_Toc333246938\\"><strong>9.5 - Governing Law, Jurisdiction and Venue</strong>. 24</a><br />\\n		<a href=\\"#_Toc333246940\\"><strong>9.5.1 - Louisiana Residents</strong>. 25</a><br />\\n		<a href=\\"#_Toc333246941\\">SECTION 10 - PAYMENTS. 25</a><br />\\n		<a href=\\"#_Toc333246942\\"><strong>10.1 - </strong></a><a href=\\"#_Toc333246943\\"><strong>Restrictions on Third Party Use of Credit Cards and Checking Account Access</strong>. 25</a><br />\\n		<a href=\\"#_Toc333246944\\"><strong>10.2 - Sales Taxes</strong>. 25</a><br />\\n		<a href=\\"#_Toc333246945\\">SECTION 11 - INACTIVITY AND CANCELLATION.. 25</a><br />\\n		<a href=\\"#_Toc333246946\\"><strong>11.1 - Effect of Cancellation</strong>.. 25</a><br />\\n		<a href=\\"#_Toc333246947\\"><strong>11.2 - Cancellation Due to Inactivity</strong>.. 26</a><br />\\n		<a href=\\"#_Toc333246948\\"><strong>11.2.1 - Failure to Meet PV Quota</strong>. 26</a><br />\\n		<a href=\\"#_Toc333246952\\"><strong>11.2.2 - Failure to Earn Commissions</strong>. 26</a><br />\\n		<a href=\\"#_Toc333246953\\"><strong>11.2.3 &ndash; Continuation of Autoship</strong>. 26</a><br />\\n		<a href=\\"#_Toc333246954\\"><strong>11.3 - Involuntary Cancellation</strong>.. 26</a><br />\\n		<a href=\\"#_Toc333246955\\"><strong>11.4 - Voluntary Cancellation</strong>.. 26</a><br />\\n		<a href=\\"#_Toc333246962\\">SECTION 12 - DEFINITIONS. 27</a><br />\\n		&nbsp;</div>\\n	<br clear=\\"all\\" />\\n	&nbsp;<br />\\n	<h1>\\n		&nbsp;</h1>\\n	<h1>\\n		SECTION 1 - COMPANY MISSION STATEMENT</h1>\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>Bella Vie Network Mission Statement</strong><br />\\n	&nbsp;<br />\\n	<strong><em>Bella Vie Network is not just another MLM business, but it is driven by the eagerness of helping members making life better and more beautiful. Sometimes, just a little positive thinking to put into action can make a difference. As we all need to purchase things for our daily needs, members can still do just that but with a return of extra benefits by sharing their goodness with others. It is a sharing and caring network so together we can help each other save and earn some supplemental income without stocking or selling. It is a simple system which can make a difference when our major focus is to help each other making simple life beautiful and meaningful.</em></strong><br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 2 - </strong><strong>INTRODUCTION</strong><br />\\n	&nbsp;<br />\\n	<strong>2.1 - </strong><strong>Policies and Compensation Plan Incorporated into Member Agreement</strong><br />\\n	These Policies and Procedures and the Compensation Plan, in their present form and as amended by Bella Vie Network, LLC (hereafter &ldquo;Bella Vie Network&rdquo; or the &ldquo;Company&rdquo;), are incorporated into, and form an integral part of, the Bella Vie Network Member Agreement.&nbsp; It is the responsibility of each Member to read, understand, adhere to, and insure that he or she is aware of and operating under the most current version of these Policies and Procedures.&nbsp; Throughout these Policies, when the term &ldquo;Agreement&rdquo; is used, it collectively refers to the Bella Vie Network Member Application and Agreement, these Policies and Procedures, the Bella Vie Network Compensation Plan, and the Bella Vie Network Business Entity Application (if applicable).&nbsp; These documents are incorporated by reference into the Bella Vie Network Member Agreement (all in their current form and as amended by Bella Vie Network).&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>2.2 - </strong><strong>Changes to the Agreement</strong><br />\\n	Bella Vie Network reserves the right to amend the Agreement and its prices in its sole and absolute discretion.&nbsp; By executing the Member Agreement, a Member agrees to abide by all amendments or modifications that Bella Vie Network elects to make.&nbsp; Amendments shall be effective thirty (30) days after publication of notice that the Agreement has been modified.&nbsp; Amendments shall not apply retroactively to conduct that occurred prior to the effective date of the amendment.&nbsp; Notification of amendments shall be published by one or more of the following methods: (1) posting on the Company&rsquo;s official web site; (2) electronic mail (e-mail); (3) posting in Members&rsquo; back-offices; (4) inclusion in Company periodicals; (5) inclusion in product orders or bonus checks; or (6) special mailings.&nbsp; The continuation of a Member&rsquo;s Bella Vie Network business, the acceptance of any benefits under the Agreement, or a Member&rsquo;s acceptance of bonuses or commissions constitutes acceptance of all amendments.<br />\\n	&nbsp;<br />\\n	<strong>2.3 - </strong><strong>Policies and Provisions Severable</strong><br />\\n	If any provision of the Agreement, in its current form or as may be amended, is found to be invalid, or unenforceable for any reason, only the invalid portion(s) of the provision shall be severed and the remaining terms and provisions shall remain in full force and effect.&nbsp; The severed provision, or portion thereof, shall be reformed to reflect the purpose of the provision as closely as possible.<br />\\n	&nbsp;<br />\\n	<strong>2.4 - </strong><strong>Waiver</strong><br />\\n	The Company never gives up its right to insist on compliance with the Agreement and with the applicable laws governing the conduct of a business.&nbsp; No failure of Bella Vie Network to exercise any right or power under the Agreement or to insist upon strict compliance by a Member with any obligation or provision of the Agreement, and no custom or practice of the parties at variance with the terms of the Agreement, shall constitute a waiver of Bella Vie Network&rsquo;s right to demand exact compliance with the Agreement.&nbsp; The existence of any claim or cause of action of a Member against Bella Vie Network shall not constitute a defense to Bella Vie Network&rsquo;s enforcement of any term or provision of the Agreement.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 3 - </strong>&nbsp;&nbsp;<strong>BECOMING A MEMBER</strong><br />\\n	&nbsp;<br />\\n	<strong>3.1 - </strong>&nbsp;<strong>Requirements to Become a Member</strong><br />\\n	To become a Bella Vie Network Member, each applicant must:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Be at least 18 years of age;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reside in the United States or U.S. Territories or country that Bella Vie Network has officially announced is open for business;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Provide Bella Vie Network with his/her valid Social Security or Federal Tax ID number;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit a properly completed Member Application and Agreement to Bella Vie Network online;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit an IRS Form W-9.<br />\\n	&nbsp;<br />\\n	Bella Vie Network reserves the right to accept or reject any Member Application and Agreement for any reason or for no reason.<br />\\n	&nbsp;<br />\\n	<strong>3.2 - </strong>&nbsp;<strong>Product Purchases</strong><br />\\n	No person is required to purchase Bella Vie Network products, services or sales aids, or to pay any charge or fee to become a Member.<br />\\n	&nbsp;<br />\\n	<strong>3.3 - </strong><strong>Member Benefits</strong><br />\\n	Once a Member Application and Agreement has been accepted by Bella Vie Network, the benefits of the Compensation Plan and the Member Agreement are available to the new Member.&nbsp; These benefits include the right to:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sell/Buy Bella Vie Network products and services;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in the Bella Vie Network Compensation Plan (receive bonuses and commissions, if eligible);<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sponsor other individuals as Customers or Members into the Bella Vie Network business and thereby, build a marketing organization and progress through the Bella Vie Network Compensation Plan;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receive periodic Bella Vie Network literature and other Bella Vie Network communications;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in Bella Vie Network-sponsored support, service, training, motivational and recognition functions, upon payment of appropriate charges, if applicable; and<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in promotional and incentive contests and programs sponsored by Bella Vie Network for its Members.<br />\\n	&nbsp;<br />\\n	<strong>3.4 - </strong><strong>Term and Renewal of Your Bella Vie Network Business</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The term of the Member Agreement is month-to-month, and is automatically renewed upon the payment of the monthly Member minimum sales order.&nbsp; Should a Member fail to pay his/her monthly sales order, the Member&rsquo;s business will be put on suspension, and will not be eligible for commissions or bonuses for that month.&nbsp; If the Member fails to generate the monthly minimum sales order for three consecutive months, the Member&rsquo;s Agreement shall be permanently cancelled.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 4 - </strong><strong>OPERATING A BELLA VIE NETWORK BUSINESS</strong><br />\\n	&nbsp;<br />\\n	<strong>4.1 - </strong><strong>Adherence to the Bella Vie Network Compensation Plan</strong><br />\\n	Members must adhere to the terms of the Bella Vie Network Compensation Plan as set forth in official Bella Vie Network literature.&nbsp; Members shall not offer the Bella Vie Network opportunity through, or in combination with, any other system, program, sales tools, or method of marketing other than that specifically set forth in official Bella Vie Network literature.&nbsp; Members shall not require or encourage other current or prospective Customers or Members to execute any agreement or contract other than official Bella Vie Network agreements and contracts in order to become a Bella Vie Network Member.&nbsp; Similarly, Members shall not require or encourage other current or prospective Customers or Members to make any purchase from, or payment to, any individual or other entity to participate in the Bella Vie Network Compensation Plan other than those purchases or payments identified as recommended or required in official Bella Vie Network literature.<br />\\n	&nbsp;<br />\\n	<strong>4.2 - </strong><strong>Advertising</strong><br />\\n	<strong>4.2.1 - </strong><strong>General</strong><br />\\n	All Members shall safeguard and promote the good reputation of Bella Vie Network and its products.&nbsp; The marketing and promotion of Bella Vie Network, the Bella Vie Network opportunity, the Compensation Plan, and Bella Vie Network products must avoid all discourteous, deceptive, misleading, unethical or immoral conduct or practices.<br />\\n	&nbsp;<br />\\n	To promote both the products and services, and the tremendous opportunity Bella Vie Network offers, Members should use the sales aids, business tools, and support materials produced by Bella Vie Network.&nbsp; The Company has carefully designed its products, product labels, Compensation Plan, and promotional materials to ensure that they are promoted in a fair and truthful manner, that they are substantiated, and the materials comply with the legal requirements of federal and state laws.<br />\\n	&nbsp;<br />\\n	Accordingly, Members must not produce or use the literature, advertisements, sales aids, business tools, promotional materials, or Internet web pages of themselves or other third parties.<br />\\n	&nbsp;<br />\\n	<strong>4.2.2 - </strong><strong>Trademarks and Copyrights</strong><br />\\n	The name of Bella Vie Network and other names as may be adopted by Bella Vie Network are proprietary trade names, trademarks and service marks of Bella Vie Network (collectively &ldquo;marks&rdquo;).&nbsp; As such, these marks are of great value to Bella Vie Network and are supplied to Members for their use only in an expressly authorized manner.&nbsp; Bella Vie Network will not allow the use of its trade names, trademarks, designs, or symbols, or any derivatives of such marks, by any person, including Bella Vie Network Members, in any unauthorized manner without its prior, written permission.&nbsp;<br />\\n	&nbsp;<br />\\n	The content of all Company sponsored events is copyrighted material.&nbsp; Members may not produce for sale or distribution any recorded Company events and speeches without written permission from Bella Vie Network, nor may Members reproduce for sale or for personal use any recording of Company-produced audio or video tape presentations.<br />\\n	&nbsp;<br />\\n	As an independent Member, you may use the Bella Vie Network name in the following manner<br />\\n	&nbsp;<br />\\n	Member&rsquo;s Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	<em>Example:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </em><br />\\n	Alice Smith&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not use the name Bella Vie Network in any form in your team name, a tagline, an external website name, your personal website address or extension, in an e-mail address, as a personal name, or as a nickname. Additionally, only use the phrase <em>Independent Bella Vie Network Member </em>in your phone greeting or on your answering machine to clearly separate your independent Bella Vie Network business from Bella Vie Network. For example, you may not secure the domain name www.buyBellaVieNetwork.com, nor may you create an email address such as <a href=\\"mailto:BellaVieNetworksales@hotmail.com\\">BellaVieNetworksales@hotmail.com</a>.<br />\\n	&nbsp;<br />\\n	<strong>4.2.3 - </strong><strong>Media and Media Inquiries</strong><br />\\n	Members must not attempt to respond to media inquiries regarding Bella Vie Network, its products or services, or their independent Bella Vie Network business.&nbsp; All inquiries by any type of media must be immediately referred to Bella Vie Network&rsquo;s Support Department.&nbsp; This policy is designed to assure that accurate and consistent information is provided to the public as well as a proper public image.<br />\\n	&nbsp;<br />\\n	<strong>4.2.4 - </strong><strong>&nbsp;Unsolicited Email</strong><br />\\n	&nbsp;Bella Vie Network does not permit Members to send unsolicited commercial emails unless such emails strictly comply with applicable laws and regulations including, without limitation, the federal CAN SPAM Act. Any email sent by a Member that promotes Bella Vie Network, the Bella Vie Network opportunity, or Bella Vie Network products and services must comply with the following:<br />\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			There must be a functioning return email address to the sender.</li>\\n		<li>\\n			There must be a notice in the email that advises the recipient that he or she may reply to the email, via the functioning return email address, to request that future email solicitations or correspondence not be sent to him or her (a functioning &ldquo;opt-out&rdquo; notice).</li>\\n		<li>\\n			The email must include the Member&rsquo;s physical mailing address.</li>\\n		<li>\\n			The email must clearly and conspicuously disclose that the message is an advertisement or solicitation.</li>\\n		<li>\\n			The use of deceptive subject lines and/or false header information is prohibited.</li>\\n		<li>\\n			All opt-out requests, whether received by email or regular mail, must be honored. If a Member receives an opt-out request from a recipient of an email, the Member must forward the opt-out request to the Company.</li>\\n	</ul>\\n	&nbsp;<br />\\n	Bella Vie Network may periodically send commercial emails on behalf of Members.&nbsp; By entering into the Member Agreement, Member agrees that the Company may send such emails and that the Member&rsquo;s physical and email addresses will be included in such emails as outlined above.&nbsp; Members shall honor opt-out requests generated as a result of such emails sent by the Company.<br />\\n	&nbsp;<br />\\n	<strong>4.2.5 - </strong><strong>Unsolicited Faxes</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Except as provided in this section, Members may not use or transmit unsolicited faxes in connection with their Bella Vie Network business.&nbsp; The term &quot;unsolicited faxes&quot; means the transmission via telephone facsimile or computer of any material or information advertising or promoting Bella Vie Network, its products, its compensation plan or any other aspect of the company which is transmitted to any person, except that these terms do not include a fax: (a) to any person with that person&#39;s prior express invitation or permission; or (b) to any person with whom the Member has an established business or personal relationship.&nbsp; The term &quot;established business or personal relationship&quot; means a prior or existing relationship formed by a voluntary two way communication between a Member and a person, on the basis of: (a) an inquiry, application, purchase or transaction by the person regarding products offered by such Member; or (b) a personal or familial relationship, which relationship has not been previously terminated by either party.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.2.6 - </strong><strong>Telephone Directory Listings</strong><br />\\n	Members may list themselves as an &ldquo;Independent Bella Vie Network Member&rdquo; in the white or yellow pages of the telephone directory, or with online directories, under their own name.&nbsp; No Member may place telephone or online directory display ads using Bella Vie Network&#39;s name or logo.&nbsp; Members may not answer the telephone by saying &ldquo;Bella Vie Network&rdquo;, &ldquo;Bella Vie Network Incorporated&rdquo;, or in any other manner that would lead the caller to believe that he or she has reached corporate offices of Bella Vie Network.&nbsp; If a Member wishes to post his/her name in a telephone or online directory, it must be listed in the following format:<br />\\n	&nbsp;<br />\\n	Member&#39;s Name<br />\\n	Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	<strong>4.2.7 - </strong><strong>Television and Radio Advertising</strong><br />\\n	Members may not advertise on television and radio except with Bella Vie Network&rsquo;s express written approval.<br />\\n	&nbsp;<br />\\n	<strong>4.2.8 - </strong><strong>Advertised Prices</strong><br />\\n	Members may not create their own marketing or advertising material offering any Bella Vie Network products at a price less than the current Autoship price plus shipping and applicable taxes.<br />\\n	&nbsp;<br />\\n	<strong>4.3 - </strong><strong>&nbsp;Online Conduct</strong><br />\\n	<strong>4.3.1 - </strong><strong>Online Classifieds</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may not use online classifieds (including Craigslist) to list, sell or retail specific Bella Vie Network products or product bundles. You <u>may</u> use online classifieds (including Craigslist) for prospecting, recruiting, sponsoring and informing the public about the Bella Vie Network business opportunity, provided Bella Vie Network-approved templates/images are used. These templates will identify you as an Independent Bella Vie Network Member. If a link or URL is provided, it must link to your Replicated Website or your Registered External Website.<br />\\n	&nbsp;<br />\\n	<strong>4.3.2 - </strong><strong>eBay / Online Auctions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network&rsquo;s products and services may not be listed on eBay or other online auctions, nor may Members enlist or knowingly allow a third party to sell Bella Vie Network products on eBay or other online auction.<br />\\n	&nbsp;<br />\\n	<strong>4.3.3 - </strong><strong>Online Retailing</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not list or sell Bella Vie Network products on any online retail store or ecommerce site, nor may you enlist or knowingly allow a third party to sell Bella Vie Network products on any online retail store or ecommerce site.<br />\\n	&nbsp;<br />\\n	<strong>4.3.4 - </strong><strong>Spam Linking</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Spam linking is defined as multiple consecutive submissions of the same or similar content into blogs, wikis, guest books, websites or other publicly accessible online discussion boards or forums and is not allowed. This includes blog spamming, blog comment spamming and/or spamdexing. Any comments you make on blogs, forums, guest books, etc., must be unique, informative and relevant.<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.3.5 - </strong><strong>Digital Media Submission (YouTube, iTunes, PhotoBucket etc.)</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may upload, submit or publish Bella Vie Network-related video, audio or photo content that they develop and create so long as it aligns with Bella Vie Network values, contributes to the Bella Vie Network community greater good and is in compliance with Bella Vie Network&rsquo;s Policies and Procedures. All submissions must clearly identify you as an Independent Bella Vie Network Member in the content itself and in the content description tag, must comply with all copyright/legal requirements, and must state that you are solely responsible for this content. Members may not upload, submit or publish any content (video, audio, presentations or any computer files) received from Bella Vie Network or captured at official Bella Vie Network events or in buildings owned or operated by Bella Vie Network without prior written permission.<br />\\n	&nbsp;<br />\\n	<strong>4.3.6 - </strong><strong>Domain Names and Email Addresses</strong><br />\\n	Except as set forth in the Member Website Application and Agreement, Members may not use or attempt to register any of Bella Vie Network&rsquo;s trade names, trademarks, service names, service marks, product names, the Company&rsquo;s name, or any derivative of the foregoing, for any Internet domain name, email address, or social media name or address.<br />\\n	&nbsp;<br />\\n	<strong>4.3.7 - </strong><strong>&nbsp;Social Media</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In addition to meeting all other requirements specified in these Policies and Procedures, should a Member utilize any form of social media, including but not limited to Facebook, Twitter, Linkedin, YouTube, or Pinterest, the Member agrees to each of the following:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No product sales or enrollments may occur on any social media site.&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; It is each Member&rsquo;s responsibility to follow the social media site&rsquo;s terms of use.&nbsp; If the social media site does not allow its site to be used for commercial activity, you must abide by the site&rsquo;s terms of use.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any social media site that is directly or indirectly operated or controlled by a Member that is used to discuss or promote Bella Vie Network&rsquo;s products or the Bella Vie Network opportunity may not link to any website, social media site, or site of any other nature, other than the Member&rsquo;s Bella Vie Network replicated website.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; During the term of this Agreement and for a period of 12 calendar months thereafter, a Member may not use any social media site on which they discuss or promote, or have discussed or promoted, the Bella Vie Network business or Bella Vie Network&rsquo;s products to directly or indirectly solicit Bella Vie Network Members for another direct selling or network marketing program (collectively, &ldquo;direct selling&rdquo;).&nbsp; In furtherance of this provision, a Member shall not take any action that may reasonably be foreseen to result in drawing an inquiry from other Members relating to the Member&rsquo;s other direct selling business activities.&nbsp; Violation of this provision shall constitute a violation of the non-solicitation provision in Section 4.11 below.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member may post or &ldquo;pin&rdquo; photographs of Bella Vie Network products on a social media site, but only photos that are provided by Bella Vie Network and downloaded from the Member&rsquo;s Back-Office may be used.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member creates a business profile page on any social media site that promotes or relates to Bella Vie Network, its products, or opportunity, the business profile page must relate exclusively to the Member&rsquo;s Bella Vie Network business and Bella Vie Network products.&nbsp; If the Member&rsquo;s Bella Vie Network business is cancelled for any reason or if the Member becomes inactive, the Member must deactivate the business profile page.<br />\\n	&nbsp;<br />\\n	<strong>4.4 - </strong><strong>Business Entities</strong><br />\\n	A corporation, limited liability company, partnership or trust (collectively referred to in this section as a &ldquo;Business Entity&rdquo;) may apply to be a Bella Vie Network Member by submitting a Member Application and Agreement along with a properly completed Business Entity Application and Agreement and a properly completed <st1:stockticker w:st=\\"on\\">IRS</st1:stockticker> form W-9. &nbsp;The equitable ownership of a Business Entity is limited to one individual (natural person).&nbsp; That is to say, the Business Entity that owns and operates a Bella Vie membership may only have one shareholder, member, manager, trustee, etc..&nbsp; The Business Entity, as well as its shareholder, member, manager, trustee, or other party with any ownership interest in, or management responsibilities for, the Business Entity (collectively &ldquo;Affiliated Parties&rdquo;) are individually, jointly and severally liable for any indebtedness to Bella Vie Network, compliance with the Bella Vie Network Policies and Procedures, the Bella Vie Network Member Agreement, and other obligations to Bella Vie Network.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To prevent the circumvention of Section 4.5, (regarding Sponsorship Changes), if any Affiliated Party wants to terminate his or her relationship with the Business Entity or Bella Vie Network, the Affiliated Party must terminate his or her affiliation with the Business Entity, notify Bella Vie Network in writing that he or she has terminated his/her affiliation with the Business Entity.&nbsp; In addition, the Affiliated Party foregoing their interest in the Business Entity may not participate in any other Bella Vie Network business for six consecutive calendar months.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The modifications permitted within the scope of this paragraph <em>do not</em> include a change of sponsorship.&nbsp; Each Member must immediately notify Bella Vie Network of all changes to type of business entity they utilize in operating their businesses and the addition or removal of business Affiliated Parties.<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	<strong>4.5 - </strong><strong>Change of Sponsor</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network strongly discourages changes in sponsorship.&nbsp; Accordingly, the transfer of a Bella Vie Network business from one sponsor to another is rarely permitted. &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.6 - </strong>&nbsp;<strong>Waiver of Claims</strong><br />\\n	In cases in which the appropriate sponsorship change procedures have not been followed, and a downline organization has been developed in the second business developed by a Member, Bella Vie Network reserves the sole and exclusive right to determine the final disposition of the downline organization.&nbsp; Resolving conflicts over the proper placement of a downline that has developed under an organization that has improperly switched sponsors is often extremely difficult.&nbsp; Therefore, <strong>MEMBERS WAIVE ANY </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> <st1:stockticker w:st=\\"on\\">ALL</st1:stockticker> CLAIMS AGAINST BELLA VIE NETWORK, ITS OFFICERS, DIRECTORS, OWNERS, EMPLOYEES, AND AGENTS THAT RELATE TO OR ARISE FROM BELLA VIE NETWORK&rsquo;S DECISION REGARDING THE DISPOSITION OF ANY DOWNLINE ORGANIZATION THAT DEVELOPS BELOW AN ORGANIZATION THAT HAS IMPROPERLY CHANGED LINES OF SPONSORSHIP.</strong>&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.7 - </strong><strong>Unauthorized Claims</strong><strong>and Actions</strong><br />\\n	<strong>4.7.1 - </strong><strong>Indemnification</strong><br />\\n	A Member is fully responsible for all of his or her verbal and written statements made regarding Bella Vie Network products, services, and the Compensation Plan that are not expressly contained in official Bella Vie Network materials.&nbsp; This includes statements and representations made through all sources of communication media, whether person-to-person, in meetings, online, through Social Media, in print, or any other means of communication.&nbsp; Members agree to indemnify Bella Vie Network and Bella Vie Network&rsquo;s directors, officers, employees, and agents, and hold them harmless from all liability including judgments, civil penalties, refunds, attorney fees, court costs, or lost business incurred by Bella Vie Network as a result of the Member&rsquo;s unauthorized representations or actions.&nbsp; This provision shall survive the termination of the Member Agreement.<br />\\n	&nbsp;<br />\\n	<strong>4.7.2 - </strong><strong>Product Claims</strong><br />\\n	No claims (which include personal testimonials) as to therapeutic, curative or beneficial properties of any products offered by Bella Vie Network may be made except those contained in official Bella Vie Network literature.&nbsp; In particular, no Member may make any claim that Bella Vie Network products are useful in the cure, treatment, diagnosis, mitigation or prevention of any diseases.&nbsp; Such statements can be perceived as medical or drug claims, and they may lack adequate substantiation. Not only are such claims in violation of the Member Agreement, they also violate the laws and regulations of the United States, Canada, and other jurisdictions.<br />\\n	&nbsp;<br />\\n	<strong>4.7.3 - </strong><strong>Compensation Plan Claims</strong><br />\\n	When presenting or discussing the Bella Vie Network Compensation Plan, you must make it clear to prospects that financial success with Bella Vie Network requires commitment, effort, and sales skill.&nbsp; Conversely, you must never represent that one can be successful without diligently applying themselves.&nbsp; Examples of misrepresentations in this area include:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; It&rsquo;s a turnkey system;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The system will do the work for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Just get in and your downline will build through spillover;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Just join and I&rsquo;ll build your downline for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The company does all the work for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You don&rsquo;t have to sell anything; or<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All you have to do is buy your products every month.<br />\\n	&nbsp;<br />\\n	The above are just examples of improper representations about the Compensation Plan.&nbsp; It is important that you do not make these or any other representations that could lead a prospect to believe that they can be successful as a Bella Vie Network Member without commitment, effort, and sales skill.<br />\\n	&nbsp;<br />\\n	<strong>4.7.4 - </strong><strong>Income Claims</strong><br />\\n	A Member, when presenting or discussing the Bella Vie Network opportunity or Compensation Plan to a prospective Member, may not make income projections, income claims, or disclose his or her Bella Vie Network income (including the showing of checks, copies of checks, bank statements, or tax records) unless, at the time the presentation is made, the Member provides a current copy of the Bella Vie Network Income Disclosure Statement (<st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>) to the person(s) to whom he or she is making the presentation.<br />\\n	&nbsp;<br />\\n	<strong>4.7.5 - </strong><strong>&nbsp;Income Disclosure Statement </strong><br />\\n	Bella Vie Network&rsquo;s corporate ethics compel us to do not merely what is legally required, but rather, to conduct the absolute best business practices. To this end, we have developed the Bella Vie Network Income Disclosure Statement (&ldquo;<st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>&rdquo;). The Bella Vie Network <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> is designed to convey truthful, timely, and comprehensive information regarding the income that Bella Vie Network Members earn. In order to accomplish this objective, a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be presented to all prospective Members.&nbsp; The failure to comply with this policy constitutes a significant and material breach of the Bella Vie Network Member Agreement and will be grounds for disciplinary sanctions, including termination, pursuant to Section 9.<br />\\n	&nbsp;<br />\\n	A copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be presented to a prospective Member (someone who is not a party to a current Bella Vie Network Member Agreement) anytime the Compensation Plan is presented or discussed, or any type of income claim or earnings representation is made.<br />\\n	&nbsp;<br />\\n	The terms &ldquo;income claim&rdquo; and/or &ldquo;earnings representation&rdquo; (collectively &ldquo;income claim&rdquo;) include: (1) statements of actual earnings, (2) statements of projected earnings, (3) statements of earnings ranges, (4) income testimonials, (5) lifestyle claims, and (6) hypothetical claims.<br />\\n	&nbsp;<br />\\n	A lifestyle income claim typically includes statements (or pictures) involving large homes, luxury cars, exotic vacations, or other items suggesting or implying wealth. They also consist of references to the achievement of one&#39;s dreams, having everything one always wanted, and are phrased in terms of &ldquo;opportunity&rdquo; or &ldquo;possibility&rdquo; or &ldquo;chance.&rdquo; Claims such as &ldquo;My Bella Vie Network income exceeded my salary after six months in the business,&rdquo; or &ldquo;Our Bella Vie Network business has allowed my wife to come home and be a full-time mom&rdquo; also fall within the purview of &ldquo;lifestyle&rdquo; claims.<br />\\n	&nbsp;<br />\\n	A hypothetical income claim exists when you attempt to explain the operation of the compensation plan through the use of a hypothetical example.&nbsp; Certain assumptions are made regarding some or all of the following: (1) number of personally-enrolled Customers and Members; (2) number of downline Customers and Members; (3) average sales/purchase volume/sales volume per Customer and Member; and (4) total organizational volume.&nbsp; Applying these assumptions through the compensation plan yields income figures which constitute hypothetical income claims.<br />\\n	&nbsp;<br />\\n	In any non-public meeting (e.g., a home meeting, one-on-one, regardless of venue) with a prospective Member or Members in which the Compensation Plan is discussed or any type of income claim is made, you must provide the prospect(s) with a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>. In any meeting that is open to the public in which the Compensation Plan is discussed or any type of income claims is made, you must provide every prospective Member with a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> and you must display at least one (3 foot x 5 foot poster board) in the front of the room in reasonably close proximity to the presenter(s). In any meeting in which any type of video display is utilized (e.g., monitor, television, projector, etc.) a slide of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be displayed continuously throughout the duration of any discussion of the Compensation Plan or the making of an income claim.<br />\\n	&nbsp;<br />\\n	Copies of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> may be printed or downloaded without charge from the corporate website at <a href=\\"http://www.bellavienetwork.com/IDS\\">http://www.BellaVieNetwork.com/IDS</a>.<br />\\n	&nbsp;<br />\\n	Members who develop sales aids and tools in which the Compensation Plan or income claims are present must incorporate the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> into each such sales aid or tool prior to submission to the Company for review.<br />\\n	&nbsp;<br />\\n	<strong>4.8 - </strong><strong>&nbsp;Repackaging and Re-labeling Prohibited</strong><br />\\n	Bella Vie Network products may only be sold in their original packaging.&nbsp; Members may not repackage, re-label, or alter the labels on Bella Vie Network products. Tampering with labels/packaging could be a violation of federal and state laws, and may result in civil or criminal liability. Members may affix a personalized sticker with your personal/contact information to each product or product container, as long as you do so without removing existing labels or covering any text, graphics, or other material on the product label.<br />\\n	&nbsp;<br />\\n	<strong>4.9 - </strong><strong>Commercial Ou</strong><strong>tl</strong><strong>ets</strong><br />\\n	Members may not sell Bella Vie Network products from a commercial outlet, nor may Members display or sell Bella Vie Network products or literature in any retail or service establishment.&nbsp; Online auction and/or sales facilitation websites, including but not limited to eBay and Craig&rsquo;s List constitute Commercial Outlets, and may not be used to sell Bella Vie Network products.&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	<strong>4.10 - </strong><strong>Conflicts of Interest</strong><br />\\n	<strong>4.10.1 - </strong><strong>Nonsolicitation</strong><br />\\n	Bella Vie Network Members are free to participate in other multilevel or network marketing business ventures or marketing opportunities (collectively &ldquo;network marketing&rdquo;).&nbsp; However, during the term of this Agreement, Members may not directly or indirectly Recruit other Bella Vie Network Members or Customers for any other network marketing business.&nbsp;<br />\\n	&nbsp;<br />\\n	Following the cancellation of a Member&rsquo;s Independent Member Agreement, and for a period of six calendar months thereafter, with the exception of a Member who is personally sponsored by the former Member, a former Member may not Recruit any Bella Vie Network Member or Customer for another network marketing business.&nbsp; Members and the Company recognize that because network marketing is conducted through networks of independent contractors dispersed across the entire United States and internationally, and business is commonly conducted via the internet and telephone, an effort to narrowly limit the geographic scope of this non-solicitation provision would render it wholly ineffective.&nbsp; Therefore, Members and Bella Vie Network agree that this non-solicitation provision shall apply nationwide and to all international markets in which Members are located. &nbsp;This provision shall survive the termination or expiration of the Member Agreement.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The term &ldquo;Recruit&rdquo; means the actual or attempted sponsorship, solicitation, enrollment, encouragement, or effort to influence in any other way, either directly, indirectly, or through a third party, another Bella Vie Network Member or Customer to enroll or participate in another multilevel marketing, network marketing or direct sales opportunity.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.10.2 - </strong><strong>Member Participation in Other Network Marketing Programs</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is engaged in other non-Bella Vie Network direct selling programs, it is the responsibility of the Member to ensure that his or her Bella Vie Network business is operated entirely separate and apart from any other program.&nbsp; To this end, the following must be adhered to:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members must not sell, or attempt to sell, any competing non-Bella Vie Network programs, products or services to Bella Vie Network Customers or Members.&nbsp; Any program, product or services in the same generic categories as Bella Vie Network products or services is deemed to be competing, regardless of differences in cost, quality or other distinguishing factors.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not display Bella Vie Network promotional material, sales aids, products or services with or in the same location as, any non-Bella Vie Network promotional material or sales aids, products or services.&nbsp;<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not offer the Bella Vie Network opportunity, products or services to prospective or existing Customers or Members in conjunction with any non-Bella Vie Network program, opportunity, product or service.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not offer any non-Bella Vie Network opportunity, products, services or opportunity at any Bella Vie Network-related meeting, seminar, convention, webinar, teleconference, or other function.<br />\\n	&nbsp;<br />\\n	<strong>4.10.3 - </strong><strong>Confidential Information</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ldquo;Confidential Information&rdquo; includes, but is not limited to, Downline Genealogy Reports, the identities of Bella Vie Network customers and Members, contact information of Bella Vie Network customers and Members, Members&rsquo; personal sales volumes, and Member rank and/or achievement levels.&nbsp; Confidential Information is, or may be available, to Members in their respective back-offices.&nbsp; Member access to such Confidential Information is password protected, and is confidential and constitutes proprietary information and business trade secrets belonging to Bella Vie Network.&nbsp; Such Confidential Information is provided to Members in strictest confidence and is made available to Members for the sole purpose of assisting Members in working with their respective downline organizations in the development of their Bella Vie Network business.&nbsp; Members may not use the reports for any purpose other than for developing their Bella Vie Network business. Where a Member participates in other multi-level marketing ventures, he/she is not eligible to have access to Downline Genealogy Reports.&nbsp; Members should use the Confidential Information to assist, motivate, and train their downline Members. The Member and Bella Vie Network agree that, but for this agreement of confidentiality and nondisclosure, Bella Vie Network would not provide Confidential Information to the Member.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To protect the Confidential Information, Members shall not, on his or her own behalf, or on behalf of any other person, partnership, association, corporation or other entity:<br />\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			Directly or indirectly disclose any Confidential Information to any third party;</li>\\n		<li>\\n			Directly or indirectly disclose the password or other access code to his or her back-office;</li>\\n		<li>\\n			Use any Confidential Information to compete with Bella Vie Network or for any purpose other than promoting his or her Bella Vie Network business;&nbsp;</li>\\n		<li>\\n			Recruit or solicit any Member or Customer of Bella Vie Network listed on any report or in the Member&rsquo;s back-office, or in any manner attempt to influence or induce any Member or Preferred Customer of Bella Vie Network, to alter their business relationship with Bella Vie Network; or</li>\\n		<li>\\n			Use or disclose to any person, partnership, association, corporation, or other entity any Confidential Information.</li>\\n	</ul>\\n	&nbsp;<br />\\n	The obligation not to disclose Confidential Information shall survive cancellation or termination of the Agreement, and shall remain effective and binding irrespective of whether a Member&rsquo;s Agreement has been terminated, or whether the Member is or is not otherwise affiliated with the Company.<br />\\n	&nbsp;<br />\\n	<strong>4.11 - </strong><strong>Targeting Other Direct Sellers</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network does not condone Members specifically or consciously targeting the sales force of another direct sales company to sell Bella Vie Network products or to become Members for Bella Vie Network, nor does Bella Vie Network condone Members solicitation or enticement of members of the sales force of another direct sales company to violate the terms of their contract with such other company.&nbsp; Should Members engage in such activity, they bear the risk of being sued by the other direct sales company.&nbsp; If any lawsuit, arbitration or mediation is brought against a Member alleging that he or she engaged in inappropriate recruiting activity of its sales force or customers, Bella Vie Network will not pay any of the Member&rsquo;s defense costs or legal fees, nor will Bella Vie Network indemnify the Member for any judgment, award, or settlement.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.12 - </strong><strong>Errors or Questions</strong><br />\\n	If a Member has questions about or believes any errors have been made regarding commissions, bonuses, genealogy lists, or charges, the Member must notify Bella Vie Network online through his or her Back Office within 60 days of the date of the purported error or incident in question.&nbsp; Bella Vie Network will not be responsible for any errors, omissions or problems not reported to the Company within 60 days.<br />\\n	&nbsp;<br />\\n	<strong>4.13 - </strong><strong>Governmental Approval or Endorsement</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Neither federal nor state regulatory agencies or officials approve or endorse any direct selling or network marketing companies or programs.&nbsp; Therefore, Members shall not represent or imply that Bella Vie Network or its Compensation Plan have been &quot;approved,&quot; &quot;endorsed&quot; or otherwise sanctioned by any government agency.<br />\\n	&nbsp;<br />\\n	<strong>4.14 - </strong><strong>Holding Applications or Orders</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members must not manipulate enrollments of new applicants and purchases of products.<br />\\n	&nbsp;<br />\\n	<strong>4.15 - </strong><strong>Income Taxes</strong><br />\\n	Each Member is responsible for paying local, state, and federal taxes on any income generated as an Independent Member.&nbsp; Unfortunately, we cannot provide you with any personal tax advice.&nbsp; Please consult your own tax accountant, tax attorney, or other tax professional.&nbsp; If a Member&rsquo;s Bella Vie Network business is tax exempt, the Federal tax identification number must be provided to Bella Vie Network.&nbsp; Every year, Bella Vie Network will provide an <st1:stockticker w:st=\\"on\\">IRS</st1:stockticker> Form 1099 MISC (Non-employee Compensation) earnings statement to each U.S. resident who: 1) Had earnings of over $600 in the previous calendar year; or 2) Made purchases during the previous calendar year in excess of $5,000.<br />\\n	&nbsp;<br />\\n	<strong>4.16 - </strong><strong>Independent Contractor Status</strong><br />\\n	Members are independent contractors.&nbsp; The agreement between Bella Vie Network and its Members does not create an employer/employee relationship, agency, partnership, or joint venture between the Company and the Member. Members shall not be treated as an employee for his or her services or for Federal or State tax purposes.&nbsp; All Members are responsible for paying local, state, and federal taxes due from all compensation earned as a Member of the Company.&nbsp; The Member has no authority (expressed or implied), to bind the Company to any obligation.&nbsp; Each Member shall establish his or her own goals, hours, and methods of sale, so long as he or she complies with the terms of the Member Agreement, these Policies and Procedures, and applicable laws.<br />\\n	&nbsp;<br />\\n	<strong>4.17 - </strong><strong>International</strong> <strong>Marketing</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members are authorized to sell Bella Vie Network products and services, and enroll Customers or Members only in the countries in which Bella Vie Network is authorized to conduct business, as announced in official Company literature. &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.18 - </strong><strong>Adherence to Laws and Ordinances</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall comply with all federal, state, and local laws and regulations in the conduct of their businesses.&nbsp; Many cities and counties have laws regulating certain home-based businesses.&nbsp; In most cases these ordinances are not applicable to Members because of the nature of their business.&nbsp; However, Members must obey those laws that do apply to them.&nbsp; If a city or county official tells a Member that an ordinance applies to him or her, the Member shall be polite and cooperative, and immediately send a copy of the ordinance to the Compliance Department of Bella Vie Network.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.19 - </strong><strong>Separation of a Bella Vie Network Business</strong><br />\\n	A divorcing husband and wife parties may continue to operate the Bella Vie Network business jointly on a &ldquo;business-as-usual&rdquo; basis, whereupon all compensation paid by Bella Vie Network will be paid according to the status quo as it existed prior to the divorce filing or dissolution proceedings.&nbsp; This is the default procedure if the parties do not agree on the format set forth above.<br />\\n	&nbsp;<br />\\n	Under no circumstances will the Downline Organization of divorcing spouses or a dissolving business entity be divided.&nbsp; Similarly, under no circumstances will Bella Vie Network split commission and bonus checks between divorcing spouses or members of dissolving entities.&nbsp; Bella Vie Network will recognize only one Downline Organization and will issue only one commission check per Bella Vie Network business per commission cycle.&nbsp; Commission checks shall always be issued to the same individual or entity.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a former spouse has completely relinquished all rights in the original Bella Vie Network business pursuant to a divorce, he or she is thereafter free to enroll under any sponsor of his or her choosing without waiting six calendar months.&nbsp; In the case of business entity dissolutions, the former partner, shareholder, member, or other entity affiliate who retains no interest in the business must wait six calendar months from the date of the final dissolution before re-enrolling as a Member.&nbsp; In either case, the former spouse or business affiliate shall have no rights to any Members in their former organization or to any former retail customer.&nbsp; They must develop the new business in the same manner as would any other new Member.<br />\\n	&nbsp;<br />\\n	<strong>4.20 - </strong><strong>Sponsoring Online</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; When sponsoring a new Member through the online enrollment process, the sponsor may assist the new applicant in filling out the enrollment materials.&nbsp; However, the applicant must personally review and agree to the online application and agreement, Bella Vie Network&rsquo;s Policies and Procedures, and the Bella Vie Network Compensation Plan.&nbsp; The sponsor may not fill out the online application and agreement on behalf of the applicant and agree to these materials on behalf of the applicant.<br />\\n	&nbsp;<br />\\n	<strong>4.21 - </strong><strong>Telemarketing Techniques</strong> &nbsp;<br />\\n	The Federal Trade Commission and the Federal Communications Commission each have laws that restrict telemarketing practices.&nbsp; Both federal agencies (as well as a number of states) have &ldquo;do not call&rdquo; regulations as part of their telemarketing laws.&nbsp; Although Bella Vie Network does not consider Members to be &ldquo;telemarketers&rdquo; in the traditional sense of the word, these government regulations broadly define the term &ldquo;telemarketer&rdquo; and &ldquo;telemarketing&rdquo; so that your inadvertent action of calling someone whose telephone number is listed on the federal &ldquo;do not call&rdquo; registry could cause you to violate the law.&nbsp; Moreover, these regulations must not be taken lightly, as they carry significant penalties.&nbsp;<br />\\n	&nbsp;<br />\\n	Therefore, Members must not engage in telemarketing in the operation of their Bella Vie Network businesses.&nbsp; The term &ldquo;telemarketing&rdquo; means the placing of one or more telephone calls to an individual or entity to induce the purchase of a Bella Vie Network product or service, or to recruit them for the Bella Vie Network opportunity.&nbsp; &ldquo;Cold calls&quot; made to prospective customers or Members that promote either Bella Vie Network&rsquo;s products or services or the Bella Vie Network opportunity constitute telemarketing and are prohibited.&nbsp; However, a telephone call(s) placed to a prospective customer or Member (a &quot;prospect&quot;) is permissible under the following situations:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			If the Member has an established business relationship with the prospect.&nbsp; An &ldquo;established business relationship&rdquo; is a relationship between a Member and a prospect&nbsp;based on the prospect&rsquo;s purchase, rental, or lease of goods or services from the Member, or a financial transaction between the prospect and the Member, within the eighteen (18) months immediately preceding the date of a telephone call to induce the prospect&#39;s purchase of a product or service.&nbsp;&nbsp;&nbsp; &nbsp;</li>\\n	</ul>\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			The prospect&rsquo;s personal inquiry or application regarding a product or service offered by the Member, within the three (3) months immediately preceding the date of such a call. &nbsp;</li>\\n	</ul>\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			If the Member receives written and signed permission from the prospect authorizing the Member to call.&nbsp; The authorization must specify the telephone number(s) which the Member is authorized to call.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</li>\\n	</ul>\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			You may call family members, personal friends, and acquaintances.&nbsp; An &ldquo;acquaintance&rdquo; is someone with whom you have at least a recent first-hand relationship within the preceding three months.&nbsp; Bear in mind, however, that if you engage in &ldquo;card collecting&rdquo; with everyone you meet and subsequently calling them, the FTC may consider this a form of telemarketing that is not subject to this exemption.&nbsp;&nbsp; Thus, if you engage in calling &ldquo;acquaintances,&rdquo; you must make such calls on an occasional basis only and not make this a routine practice.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</li>\\n	</ul>\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			Members shall not use automatic telephone dialing systems or software relative to the operation of their Bella Vie Network businesses.</li>\\n	</ul>\\n	&nbsp;<br />\\n	<ul>\\n		<li>\\n			Members shall not place or initiate any outbound telephone call to any person that delivers any pre-recorded message (a &quot;robocall&quot;) regarding or relating to the Bella Vie Network products, services or opportunity.</li>\\n	</ul>\\n	&nbsp;<br />\\n	<strong>4.22 - </strong><strong>Back Office Access</strong><br />\\n	Bella Vie Network makes online back offices available to its Members.&nbsp; Back offices provide Members access to confidential and proprietary information that may be used solely and exclusively to promote the development of a Member&rsquo;s Bella Vie Network business and to increase sales of Bella Vie Network products.&nbsp; However, access to a back office is a privilege, and not a right.&nbsp; Bella Vie Network reserves the right to deny Members&rsquo; access to the back office at its sole discretion.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 5 - </strong><strong>RESPONSIBILITIES OF MEMBERS</strong><br />\\n	&nbsp;<br />\\n	<strong>5.1 - </strong><strong>Change of Address, Telephone, and E-Mail Addresses</strong><br />\\n	To ensure timely delivery of products, support materials, commission, and tax documents, it is important that the Bella Vie Network&rsquo;s files are current.&nbsp; Street addresses are required for shipping since <st1:stockticker w:st=\\"on\\">UPS</st1:stockticker> cannot deliver to a post office box.&nbsp; Members planning to change their e-mail address or move must provide their new address and telephone numbers to Bella Vie Network via their Back Office.&nbsp; To guarantee proper delivery, two weeks advance notice must be provided to Bella Vie Network on all changes.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.2 - </strong><strong>Continuing Development Obligations</strong><br />\\n	<strong>5.2.1 - </strong><strong>Ongoing Training</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any Member who sponsors another Member into Bella Vie Network must perform a bona fide assistance and training function to ensure that his or her downline is properly operating his or her Bella Vie Network business.&nbsp; Members must have ongoing contact and communication with the Members in their Downline Organizations.&nbsp; Examples of such contact and communication may include, but are not limited to:&nbsp; newsletters, written correspondence, personal meetings, telephone contact, voice mail, electronic mail, and the accompaniment of downline Members to Bella Vie Network meetings, training sessions, and other functions.&nbsp; Upline Members are also responsible to motivate and train new Members in Bella Vie Network product knowledge, effective sales techniques, the Bella Vie Network Compensation Plan, and compliance with Company Policies and Procedures and applicable laws.&nbsp; Communication with and the training of downline Members must not, however, violate Sections 4.1 and/or 4.2 (regarding the development of Member-produced sales aids and promotional materials).<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members should monitor the Members in their Downline Organizations to guard against downline Members making improper product or business claims, violation of the Policies and Procedures, or engaging in any illegal or inappropriate conduct.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.2.2 - </strong><strong>Increased Training Responsibilities</strong><br />\\n	As Members progress through the various levels of leadership, they will become more experienced in sales techniques, product knowledge, and understanding of the Bella Vie Network program.&nbsp; They will be called upon to share this knowledge with lesser experienced Members within their organization.<br />\\n	&nbsp;<br />\\n	<strong>5.2.3 - </strong><strong>Ongoing Sales Responsibilities</strong><br />\\n	Regardless of their level of achievement, Members have an ongoing obligation to continue to personally promote sales through the generation of new customers and through servicing their existing customers.<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.3 - </strong><strong>Nondisparagement</strong><br />\\n	Bella Vie Network wants to provide its independent Members with the best products, compensation plan, and service in the industry.&nbsp; Accordingly, we value your constructive criticisms and comments.&nbsp; All such comments should be submitted in writing to the Support Department.&nbsp; Remember, to best serve you, we must hear from you!&nbsp; While Bella Vie Network welcomes constructive input, negative comments and remarks made in the field by Members about the Company, its products, or compensation plan serve no purpose other than to sour the enthusiasm of other Bella Vie Network Members.&nbsp; For this reason, and to set the proper example for their downline, Members must not disparage, demean, or make negative remarks about Bella Vie Network, other Bella Vie Network Members, Bella Vie Network&rsquo;s products, the Marketing and Compensation plan, or Bella Vie Network&rsquo;s directors, officers, or employees.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.4 - </strong><strong>Providing Documentation to Applicants</strong><br />\\n	Members must provide the most current version of the Policies and Procedures and the Compensation Plan to individuals whom they are sponsoring to become Members before the applicant signs a Member Agreement, or ensure that they have online access to these materials.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 6 - </strong><strong>SALES REQUIREMENTS</strong><br />\\n	&nbsp;<br />\\n	<strong>6.1 - </strong><strong>Product Sales</strong><br />\\n	The Bella Vie Network Compensation Plan is based on the sale of Bella Vie Network products and services to end consumers.&nbsp; Members must fulfill personal and Downline Organization retail sales requirements (as well as meet other responsibilities set forth in the Agreement) to be eligible for bonuses, commissions and advancement to higher levels of achievement.&nbsp; The following sales requirements must be satisfied for Members to be eligible for commissions:<br />\\n	&nbsp;<br />\\n	Members must satisfy the Personal Volume requirements to fulfill the requirements associated with their rank as specified in the Bella Vie Network Compensation Plan.&nbsp; &ldquo;Personal Sales Volume&rdquo; includes purchases made by the Member and purchases made by the Member&rsquo;s personal customers. &nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Members must generate their monthly minimum orders in order to be qualified for commission of their downline).<br />\\n	&nbsp;<br />\\n	Members must develop or maintain at least five Customers who must multiply by 5 for each member. Please see the Compensation Chart.<br />\\n	&nbsp;<br />\\n	<strong>6.2 - </strong><strong>No Territory Restrictions</strong><br />\\n	There are no exclusive territories granted to anyone.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>6.3 - </strong><strong>Sales Receipts</strong><br />\\n	Retail customers must receive two copies of an official Bella Vie Network sales receipt at the time of the sale.&nbsp; These receipts set forth the Customer Satisfaction Guarantee as well as any consumer protection rights afforded by federal or state law.&nbsp; Upon completion of the order process, the sales receipt will be available for printing.&nbsp; In addition, Members must orally inform the buyer of his or her cancellation rights.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 7 - </strong><strong>BONUSES </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> COMMISSIONS</strong><br />\\n	&nbsp;<br />\\n	<strong>7.1 - </strong><strong>Bonus and Commission Qualifications and Accrual</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member must be active and in compliance with the Agreement to qualify for bonuses and commissions.&nbsp; So long as a Member complies with the terms of the Agreement, Bella Vie Network shall pay commissions to such Member in accordance with the Marketing and Compensation plan.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member can see all of his or her pending and actual commissions in the &quot;MyWallet&quot; section on the Back Office. &nbsp;A Member may apply for a Paylution Debit Card. Company will deduct from Member the initial fee of the issuance of their Debit Bank Card.&nbsp; In order to transfer funds the Member&rsquo;s &quot;MyWallet&quot; account to his or her Paylution Debit Card, Company will process transfers once a month after the 20th of each month, as long as the Member requests the transfer through his or her Back Office by the 20th of each month.&nbsp; There will be a processing fee from BankCorp for every transfer transaction from MyWallet to the Paylution Debit Card.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, all commissions owed a Member, regardless of the amount accrued, will be paid at the end of each fiscal year or upon the termination of a Member&rsquo;s business.<br />\\n	&nbsp;<br />\\n	<strong>7.2 - </strong><strong>Adjustment to Bonuses and Commissions</strong><br />\\n	&nbsp;<br />\\n	<strong>7.2.1 - </strong><strong>Adjustments for Returned Products </strong><strong>and Cancelled Services</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members receive bonuses, commissions, or overrides based on the actual sales of products and services to end consumers.&nbsp; When a service is cancelled or a product is returned to Bella Vie Network for a refund or is repurchased by the Company, any of the following may occur at the Company&rsquo;s discretion: (1) the bonuses, commissions, or overrides attributable to the returned or repurchased product(s) or cancelled service will be deducted from payments to the Member and upline Members who received bonuses, commissions, or overrides on the sales of the refunded product(s) or cancelled service, in the month in which the refund is given, and continuing every pay period thereafter until the commission is recovered; (2) the Member or upline Members who earned bonuses, commissions, or overrides based on the sale of the returned product(s) or cancelled service will have the corresponding points deducted from earnings in the next month and all subsequent months until it is completely recovered; or (3) the bonuses, commissions, or overrides attributable to the returned or repurchased product(s) or cancelled service may be deducted from any refunds or credits to the Member who received the bonuses, commissions, or overrides on the sales of the refunded product(s) or cancelled service.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>7.2.2 - </strong><strong>Commissions </strong><br />\\n	The Company pays commissions via HyperWallet system. Bella Vie Network will deduct the fee for the 1st Paylution Debit Card. &nbsp;A Member must to go to his or her Profile/Back Office to place the order for the Paylution debit card so commissions can be &nbsp;transferred on to that card upon Member&#39;s discretion. Member is responsible for any transaction fee thereafter. &nbsp;There may be a processing fee charged by Hyperwallet for transactions into and out of the account &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>7.2.3 - </strong><strong>Tax Withholdings</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member fails to submit a W-9 form, Bella Vie Network will deduct the necessary withholdings from the Member&rsquo;s commission checks as required by law.<br />\\n	&nbsp;<br />\\n	<strong>7.3 - </strong><strong>Reports</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All information provided by Bella Vie Network in downline activity reports, including but not limited to personal sales volume (or any part thereof), and downline sponsoring activity is believed to be accurate and reliable.&nbsp; Nevertheless, due to various factors including but not limited to the inherent possibility of human, digital, and mechanical error; the accuracy, completeness, and timeliness of orders; denial of credit card and electronic check payments; returned products; credit card and electronic check charge-backs; the information is not guaranteed by Bella Vie Network or any persons creating or transmitting the information.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <st1:stockticker w:st=\\"on\\">ALL</st1:stockticker> PERSONAL SALES VOLUME INFORMATION IS PROVIDED &quot;AS IS&quot; WITHOUT WARRANTIES, EXPRESS OR IMPLIED, OR REPRESENTATIONS OF ANY KIND WHATSOEVER.&nbsp; IN PARTICULAR BUT WITHOUT LIMITATION THERE SHALL BE NO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR USE, OR NON‑INFRINGEMENT.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TO THE FULLEST EXTENT PERMISSIBLE UNDER APPLICABLE LAW, BELLA VIE NETWORK AND/OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION WILL IN NO EVENT BE LIABLE TO ANY MEMBER OR ANYONE ELSE FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES THAT ARISE OUT OF THE USE OF OR ACCESS TO PERSONAL SALES VOLUME INFORMATION (INCLUDING BUT NOT LIMITED TO LOST PROFITS, BONUSES, OR COMMISSIONS, LOSS OF OPPORTUNITY, AND DAMAGES THAT MAY RESULT FROM INACCURACY, INCOMPLETENESS, INCONVENIENCE, DELAY, OR LOSS OF THE USE OF THE INFORMATION), EVEN IF BELLA VIE NETWORK OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION SHALL HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.&nbsp; TO THE FULLEST EXTENT PERMITTED BY LAW, BELLA VIE NETWORK OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION SHALL HAVE NO RESPONSIBILITY OR LIABILITY TO YOU OR ANYONE ELSE UNDER ANY TORT, CONTRACT, NEGLIGENCE, STRICT LIABILITY, PRODUCTS LIABILITY OR OTHER THEORY WITH RESPECT TO ANY SUBJECT MATTER OF THIS AGREEMENT OR TERMS AND CONDITIONS RELATED THERETO.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Access to and use of Bella Vie Network&rsquo; online and telephone reporting services and your reliance upon such information is at your own risk.&nbsp; All such information is provided to you &quot;as is&quot;.&nbsp; If you are dissatisfied with the accuracy or quality of the information, your sole and exclusive remedy is to discontinue use of and access to Bella Vie Network&rsquo; online and telephone reporting services and your reliance upon the information.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 8 - </strong><strong>&nbsp;PRODUCT GUARANTEES, RETURNS, <st1:stockticker w:st=\\"on\\">AND</st1:stockticker> INVENTORY REPURCHASE</strong><br />\\n	&nbsp;<br />\\n	<strong>8.1 - </strong><strong>Rescission</strong><br />\\n	Federal and state law requires that Members notify their retail customers that they have three business days (5 business days for Alaska residents.&nbsp; Saturday is a business day, Sundays and legal holidays are not business days) within which to cancel their purchase and receive a full refund upon return of the products in substantially as good condition as when they were delivered.&nbsp; Members <strong><em>MUST</em></strong> verbally inform their customers of this right and <strong><em>MUST</em></strong> point out this cancellation right stated on the receipt.&nbsp;<br />\\n	<strong>8.2 - </strong><strong>Returns by Retail Customers</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A retail customer who makes a purchase of $25.00 or more has three business days (72 hours, excluding Sundays and legal holidays) after the sale or execution of a contract to cancel the order and receive a full refund consistent with the cancellation notice on the order form or sales receipt (5 days for Alaska residents).&nbsp; When a Member makes a sale or takes an order from a retail customer who cancels or requests a refund within the 72 hour period, the Member must promptly refund the customer&#39;s money as long as the products are returned to the Member in substantially as good condition as when received (5 days for Alaska residents).&nbsp; Members must orally inform customers of their right to rescind a purchase or an order within 72 hours (5 days for Alaska residents), and ensure that the date of the order or purchase is entered on the order form.&nbsp; All retail customers must be provided with two copies of an official Bella Vie Network sales receipt at the time of the sale.&nbsp; The back of the receipt provides the customer with written notice of his or her rights to cancel the sales agreement.<br />\\n	&nbsp;<br />\\n	<strong>8.3 - </strong><strong>Montana Residents </strong><br />\\n	&nbsp;&nbsp;<br />\\n	A Montana resident may cancel his or her Member Agreement within 15 days from the date of enrollment.<br />\\n	&nbsp;<br />\\n	<strong>8.4 - </strong><strong>Procedures for All Returns</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The following procedures apply to all returns for exchange:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All merchandise must be returned by the Member or customer who purchased it directly from Bella Vie Network to the Product&#39;s Supplier.&nbsp; No products may be returned to Bella Vie Network.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All products to be returned must have a Return Authorization Number Product&rsquo;s Supplier which is obtained by contacting the Supplier.&nbsp; This Return Authorization Number must be written on each carton returned.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The return is accompanied by:<br />\\n	&nbsp;<br />\\n	o&nbsp;&nbsp; The original packing slip with the completed and signed Consumer Return information;<br />\\n	o&nbsp;&nbsp; the unused portion of the product in its original container.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Proper shipping carton(s) and packing materials are to be used in packaging the product(s) being returned for replacement, and the best and most economical means of shipping is suggested.&nbsp; All returns must be shipped to the Supplier shipping pre-paid.&nbsp; A Supplier will not accept shipping-collect packages.&nbsp; The risk of loss in shipping for returned product shall be on the Member.&nbsp; If returned product is not received by the Supplier, it is the responsibility of the Member to trace the shipment.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is returning merchandise to a Supplier, the product(s) must be received by the Supplier within ten (10) days from the date of order.<br />\\n	&nbsp;<br />\\n	No refund or replacement of product will be made if the conditions of these rules are not met.<br />\\n	<br />\\n	<strong>SECTION 9 - </strong><strong>DISPUTE RESOLUTION </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> DISCIPLINARY PROCEEDINGS</strong><br />\\n	&nbsp;<br />\\n	<strong>9.1 - </strong><strong>Disciplinary Sanctions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Violation of the Agreement, these Policies and Procedures, violation of any common law duty, including but not limited to any applicable duty of loyalty, any illegal, fraudulent, deceptive or unethical business conduct, or any act or omission by a Member that, in the sole discretion of the Company may damage its reputation or goodwill (such damaging act or omission need not be related to the Member&rsquo;s Bella Vie Network business), may result, at Bella Vie Network&#39;s discretion, in one or more of the following corrective measures:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issuance of a written warning or admonition;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Requiring the Member to take immediate corrective measures;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Imposition of a fine, which may be withheld from bonus and commission checks;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Loss of rights to one or more bonus and commission checks;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network may withhold from a Member all or part of the Member&rsquo;s bonuses and commissions during the period that Bella Vie Network is investigating any conduct allegedly violative of the Agreement.&nbsp; If a Member&rsquo;s business is canceled for disciplinary reasons, the Member will not be entitled to recover any commissions withheld during the investigation period;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suspension of the individual&rsquo;s Member Agreement for one or more pay periods;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Permanent or temporary loss of, or reduction in, the current and/or lifetime rank of a Member (which may subsequently be re-earned by the Member);<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transfer or removal of some or all of a Member&rsquo;s downline Members from the offending Member&rsquo;s downline organization.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Involuntary termination of the offender&rsquo;s Member Agreement;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suspension and/or termination of the offending Member&rsquo;s Bella Vie Network website or website access;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any other measure expressly allowed within any provision of the Agreement or which Bella Vie Network deems practicable to implement and appropriate to equitably resolve injuries caused partially or exclusively by the Member&rsquo;s policy violation or contractual breach;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In situations deemed appropriate by Bella Vie Network, the Company may institute legal proceedings for monetary and/or equitable relief.<br />\\n	&nbsp;<br />\\n	<strong>9.2 - </strong><strong>Grievances and Complaints</strong><br />\\n	When a Member has a grievance or complaint with another Member regarding any practice or conduct in relationship to their respective Bella Vie Network businesses, the complaining Member should first report the problem to his or her Sponsor who should review the matter and try to resolve it with the other party&#39;s upline sponsor.&nbsp; If the matter involves interpretation or violation of Company policy, it must be reported in writing to the Member Services Department at the Company.&nbsp; The Member Services Department will review the facts and attempt to resolve it.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.3 - </strong><strong>&nbsp;Mediation</strong><br />\\n	Prior to instituting an arbitration, the parties shall meet in good faith and attempt to resolve any dispute arising from or relating to the Agreement through non-binding mediation.&nbsp; One individual who is mutually acceptable to the parties shall be appointed as mediator. &nbsp;The mediation shall occur within 60 days from the date on which the mediator is appointed. &nbsp;The mediator&rsquo;s fees and costs, as well as the costs of holding and conducting the mediation, shall be divided equally between the parties.&nbsp; Each party shall pay its portion of the anticipated shared fees and costs at least 10 days in advance of the mediation.&nbsp; Each party shall pay its own attorneys fees, costs, and individual expenses associated with conducting and attending the mediation.&nbsp; Mediation shall be held in the City of Las Vegas, Nevada, and shall last no more than two business days.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.4 - </strong><strong>Arbitration</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If mediation is unsuccessful, <strong>any controversy or claim arising out of or relating to the Agreement, or the breach thereof, shall be settled by arbitration.&nbsp; The Parties waive all rights to trial by jury or to any court</strong>.&nbsp; The arbitration shall be filed with, and administered by, the American Arbitration Association (&ldquo;AAA&rdquo;) or <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker> Endispute (&ldquo;<st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker>&rdquo;) under their respective rules and procedures.&nbsp; The <em>Commercial Arbitration Rules and Mediation Procedures</em> of the AAA are available on the AAA&rsquo;s website at <a href=\\"http://www.adr.org/\\">www.adr.org</a>.&nbsp; The<strong> <strong><em>Streamlined Arbitration Rules&nbsp;&amp; Procedures</em></strong> </strong>are available on the <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker> website at <a href=\\"http://www.jamsadr.com/\\">www.jamsadr.com</a>.&nbsp; Copies of AAA&rsquo;s <em>Commercial Arbitration Rules and Mediation Procedures</em> or JAM&rsquo;s <strong><em>Streamlined Arbitration Rules&nbsp;&amp; Procedures</em></strong> will also be emailed to Members upon request to Bella Vie Network&rsquo;s Support Department. &nbsp;&nbsp;<br />\\n	Notwithstanding the rules of the AAA or <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker>, the following shall apply to all Arbitration actions:<br />\\n	<ul>\\n		<li>\\n			The Federal Rules of Evidence shall apply in all cases;&nbsp;</li>\\n		<li>\\n			The Parties shall be entitled to all discovery rights permitted by the Federal Rules of Civil Procedure;</li>\\n		<li>\\n			The Parties shall be entitled to bring motions under Rules 12 and/or 56 of the Federal Rules of Civil Procedure;</li>\\n		<li>\\n			The arbitration shall occur within 180 days from the date on which the arbitrator is appointed, and shall last no more than five business days;</li>\\n		<li>\\n			The Parties shall be allotted equal time to present their respective cases, including cross-examinations.&nbsp;</li>\\n	</ul>\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All arbitration proceedings shall be held in Las Vegas, Nevada.&nbsp; There shall be one arbitrator selected from the panel that the Alternate Dispute Resolution service provides. Each party to the arbitration shall be responsible for its own costs and expenses of arbitration, including legal and filing fees.&nbsp; The arbitration shall occur within 180 days from the date on which the arbitration is filed, and shall last no more than five business days.&nbsp; The parties shall be allotted equal time to present their respective cases.&nbsp; The decision of the arbitrator shall be final and binding on the parties and may if necessary, be reduced to a judgment in any court of competent jurisdiction.&nbsp; This agreement to arbitrate shall survive the cancellation or termination of the Agreement.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The parties and the arbitrator shall maintain the confidentiality of the entire arbitration process and shall not disclose to any person not directly involved in the arbitration process:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The substance of, or basis for, the controversy, dispute, or claim;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The content of any testimony or other evidence presented at an arbitration hearing or obtained through discovery in arbitration;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The terms or amount of any arbitration award;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The rulings of the arbitrator on the procedural and/or substantive issues involved in the case.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, nothing in these Policies and Procedures shall prevent either party from applying to and obtaining from any court having jurisdiction a writ of attachment, a temporary injunction, preliminary injunction, permanent injunction or other relief available to safeguard and protect its intellectual property rights, and/or to enforce its rights under the nonsolicitation provision of the Agreement.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.5 - </strong><strong>&nbsp;Governing Law, Jurisdiction and Venue</strong><br />\\n	Jurisdiction and venue of any matter not subject to arbitration shall reside exclusively in Clark County, Nevada.&nbsp; The Federal Arbitration Act shall govern all matters relating to arbitration.&nbsp; The law of the State of Nevada shall govern all other matters relating to or arising from the Agreement.&nbsp; &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.5.1 - </strong><strong>Louisiana</strong><strong> Residents</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, and the arbitration provision in Section 9.4, residents of the State of Louisiana shall be entitled to bring an action against Bella Vie Network in their home forum and pursuant to Louisiana law.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 10 - </strong><strong>PAYMENTS</strong><br />\\n	&nbsp;<br />\\n	<strong>10.1 - </strong><strong>Restrictions on Third Party Use of Credit Cards and Checking Account Access</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not permit other Members or Customers to use his or her credit card, or permit debits to their checking accounts, to enroll or to make purchases from the Company<strong>.</strong><br />\\n	&nbsp;<br />\\n	<strong>10.2 - </strong><strong>Sales Taxes</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network is required to charge sales taxes on all purchases made by Members and Customers, and remit the taxes charged to the respective states.&nbsp; Accordingly, Bella Vie Network will collect and remit sales taxes on behalf of Members, based on the suggested retail price of the products, according to applicable tax rates in the state or province to which the shipment is destined.&nbsp; If a Member has submitted, and Bella Vie Network has accepted, a current Sales Tax Exemption Certificate and Sales Tax Registration License, sales taxes will not be added to the invoice and the responsibility of collecting and remitting sales taxes to the appropriate authorities shall be on the Member.&nbsp; Exemption from the payment of sales tax is applicable only to orders which are shipped to a state for which the proper tax exemption papers have been filed and accepted.&nbsp; Applicable sales taxes will be charged on orders that are drop-shipped to another state.&nbsp; Any sales tax exemption accepted by Bella Vie Network is not retroactive.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 11 - </strong><strong>INACTIVITY AND CANCELLATION</strong><br />\\n	&nbsp;<br />\\n	<strong>11.1 - </strong><strong>Effect of Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; So long as a Member remains active and complies with the terms of the Member Agreement and these Policies and Procedures, Bella Vie Network shall pay commissions to such Member in accordance with the Compensation Plan.&nbsp; A Member&rsquo;s bonuses and commissions constitute the entire consideration for the Member&#39;s efforts in generating sales and all activities related to generating sales (including building a downline organization).&nbsp; Following a Member&rsquo;s non-renewal of his or her Member Agreement, cancellation for inactivity, or voluntary or involuntary cancellation of his or her Member Agreement (all of these methods are collectively referred to as &ldquo;cancellation&rdquo;), the former Member shall have no right, title, claim or interest to the marketing organization which he or she operated, or any commission or bonus from the sales generated by the organization.&nbsp; <strong>A Member whose business is cancelled will lose all rights as a Member.&nbsp; This includes the right to sell Bella Vie Network products and services and the right to receive future commissions, bonuses, or other income resulting from the sales and other activities of the Member&rsquo;s former downline sales organization.&nbsp; In the event of cancellation, Members agree to waive all rights they may have, including but not limited to property rights, to their former downline organization and to any bonuses, commissions or other remuneration derived from the sales and other activities of his or her former downline organization.</strong><br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Following a Member&rsquo;s cancellation of his or her Member Agreement, the former Member shall not hold himself or herself out as a Bella Vie Network Member and shall not have the right to sell Bella Vie Network products or services.&nbsp; A Member whose business is canceled shall receive commissions and bonuses only for the last full pay period he or she was active prior to cancellation (less any amounts withheld during an investigation preceding an involuntary termination).<br />\\n	&nbsp;<br />\\n	<strong>11.2 - </strong><strong>Cancellation Due to Inactivity</strong><br />\\n	&nbsp;<br />\\n	<strong>11.2.1 - </strong><strong>Failure to Meet PV Quota</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member fails to personally generate at one order per month for 3 consecutive months, his or her Member Agreement shall be canceled for inactivity.&nbsp;&nbsp;<br />\\n	<br />\\n	<strong>11.2.2 - </strong><strong>Failure to Earn Commissions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member has not earned a commission for three consecutive months (and thus become &ldquo;inactive&rdquo;), his or her Member Agreement shall be canceled for inactivity.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>11.2.3 - </strong><strong>Continuation of Autoship</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is cancelled for inactivity, his or her Member Agreement will be cancelled for inactivity.&nbsp; If he or she is on the Company&rsquo;s autoship program, the autoship agreement shall remain in force.<br />\\n	&nbsp;<br />\\n	<strong>11.3 - </strong><strong>Involuntary Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member&rsquo;s violation of any of the terms of the Agreement, including any amendments that may be made by Bella Vie Network in its sole discretion, may result in any of the sanctions listed in Section 9.1, including the involuntary cancellation of his or her Member Agreement.&nbsp; Cancellation shall be effective on the date on which written notice is mailed, emailed, faxed, or delivered to an express courier, to the Member&rsquo;s last known address, email address, or fax number, or to his/her attorney, or when the Member receives actual notice of cancellation, whichever occurs first.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network reserves the right to terminate all Member Agreements upon thirty (30) days written notice in the event that it elects to: (1) cease business operations; (2) dissolve as a corporate entity; or (3) terminate distribution of its products via direct selling.<br />\\n	&nbsp;<br />\\n	<strong>11.4 - </strong><strong>Voluntary Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A participant in this network marketing plan has a right to cancel at any time, regardless of reason.&nbsp; Cancellation must be submitted in writing to the Company at its principal business address. The written notice must include the Member&rsquo;s signature, printed name, address, and Member I.D. Number.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In addition to written cancellation, Members who have consented to Electronic Contracting will cancel their Member Agreement should they withdraw their consent to contract electronically.&nbsp; If a Member is also on the Autoship program, the Member&rsquo;s Autoship order shall continue unless the Member also specifically requests that his or her Autoship Agreement also be canceled.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 12 - </strong><strong>DEFINITIONS</strong><br />\\n	&nbsp;<br />\\n	Active Customer &mdash; A Customer who purchases Bella Vie Network products and whose account has been paid for the ensuing year.<br />\\n	&nbsp;<br />\\n	Active Member &mdash; A Member who satisfies the minimum Personal Sales Volume requirements, as set forth in the Bella Vie Network Compensation Plan, to ensure that he or she is eligible to receive bonuses and commissions.<br />\\n	&nbsp;<br />\\n	Affiliated Party - A shareholder, member, partner, manager, trustee, or other parties with any ownership interest in, or management responsibilities for, a Business Entity.<br />\\n	&nbsp;<br />\\n	Agreement - The contract between the Company and each Member includes the Member Application and Agreement, the Bella Vie Network Policies and Procedures, the Bella Vie Network Compensation Plan, and the Business Entity Form (where appropriate), all in their current form and as amended by Bella Vie Network in its sole discretion.&nbsp; These documents are collectively referred to as the &ldquo;Agreement.&rdquo;<br />\\n	&nbsp;<br />\\n	Cancel &mdash; The termination of a Member&rsquo;s business.&nbsp; Cancellation may be either voluntary, involuntary, through non-renewal or inactivity.<br />\\n	&nbsp;<br />\\n	Downline &mdash; See &ldquo;Marketing Organization&rdquo; below.&nbsp;<br />\\n	&nbsp;<br />\\n	Downline Leg &mdash; Each one of the individuals enrolled immediately underneath you and their respective marketing organizations represents one &ldquo;leg&rdquo; in your marketing organization.<br />\\n	&nbsp;<br />\\n	Household - Spouses, heads-of-household, and dependent family members residing in the same residence.<br />\\n	&nbsp;<br />\\n	Immediate Household &mdash; Spouses, heads-of-household, and dependent family members residing in the same residence.<br />\\n	&nbsp;<br />\\n	Level &mdash; The layers of downline Customers and Members in a particular Member&rsquo;s Marketing Organization.&nbsp; This term refers to the relationship of a Member relative to a particular upline Member, determined by the number of Members between them who are related by sponsorship.&nbsp; For example, if A sponsors B, who sponsors C, who sponsors D, who sponsors E, then E is on A&rsquo;s fourth level.<br />\\n	&nbsp;<br />\\n	Marketing Organization &mdash; The Customers and Members sponsored up to seven levels and five generations below a particular Member, depending on the Member&rsquo;s rank qualification.&nbsp; The word &ldquo;downline&rdquo; is used interchangeably with Marketing Organization.<br />\\n	&nbsp;<br />\\n	Official Bella Vie Network Material &mdash; Literature, audio or video tapes, websites, and other materials developed, printed, published and/or distributed by Bella Vie Network to Members.<br />\\n	&nbsp;<br />\\n	Personal Production &mdash; Moving Bella Vie Network products or services to an end consumer for actual use.<br />\\n	&nbsp;<br />\\n	Personal Volume&mdash; The commissionable value of services and products purchased by: (1) a Member; and (2) the Member&rsquo;s downline who are on the autoship program or who purchase from the Member&rsquo;s Bella Vie Network.<br />\\n	&nbsp;<br />\\n	Rank &mdash; The &ldquo;title&rdquo; that a Member holds pursuant to the Bella Vie Network Compensation Plan.&nbsp; &ldquo;Title Rank&rdquo; refers to the highest rank a Member has achieved in the Bella Vie Network compensation plan at any time.&nbsp; &ldquo;Paid As&rdquo; rank refers to the rank at which a Member is qualified to earn commissions and bonuses during the current pay period.<br />\\n	&nbsp;<br />\\n	Recruit&nbsp; &mdash; For purposes of Bella Vie Network&rsquo;s Conflict of Interest Policy (Section 4.10), the term &ldquo;Recruit&rdquo; means the actual or attempted sponsorship, solicitation, enrollment, encouragement, or effort to influence in any other way, either directly, indirectly, or through a third party, another Bella Vie Network Member or Customer to enroll or participate in another multilevel marketing, network marketing or direct sales opportunity.<br />\\n	&nbsp;<br />\\n	Resalable &mdash; Products and Sales aids shall be deemed &quot;resalable&quot; if each of the following elements is satisfied: 1) they are unopened and unused; 2) packaging and labeling has not been altered or damaged; 3) they are in a condition such that it is a commercially reasonable practice within the trade to sell the merchandise at full price; 4) it is returned to Bella Vie Network within one month from the date of purchase.&nbsp; Any merchandise that is clearly identified at the time of sale as nonreturnable, discontinued, or as a seasonal item, shall not be resalable.<br />\\n	&nbsp;<br />\\n	Retail Customer &mdash; An individual who purchases Bella Vie Network products from a Member but who is not a participant in the Bella Vie Network compensation plan.<br />\\n	&nbsp;<br />\\n	Retail Customer &ndash; An individual who or entity that purchases Bella Vie Network products or services from a Member, but who is not a Member, or an immediate household family member of a Member.&nbsp;<br />\\n	&nbsp;<br />\\n	Retail Sales &ndash; Sales to a Retail Customer.&nbsp;<br />\\n	&nbsp;<br />\\n	Social Media - Any type of online media that invites, expedites or permits conversation, comment, rating, and/or user generated content, as opposed to traditional media, which delivers content but does not allow readers/viewers/listeners to participate in the creation or development of content, or the comment or response to content.&nbsp; Examples of Social Media include, but are not limited to, blogs, chat rooms, Facebook, MySpace, Twitter, LinkedIn, Delicious, and YouTube.&nbsp;<br />\\n	&nbsp;<br />\\n	Sponsor &mdash; A Member under whom an enroller places a new Member or Customer, and is listed as the sponsor on the Member or Customer Application and Agreement.<br />\\n	&nbsp;<br />\\n	Upline &mdash; This term refers to the Member or Members above a particular Member in a sponsorship line up to the Company.&nbsp; Conversely stated, it is the line of sponsors that links any particular Member to the Company.</div>\\n<div>\\n	<p style=\\"color: #FE8F01;text-transform:uppercase;margin:15px 0 10px;\\">\\n		BVN&#39;s Rules and Policies:</p>\\n	<ul>\\n		<li>\\n			No membership sign-up fee is required but to be active, member is required to generate a minimum of $20 of personal sales volume monthly.</li>\\n		<li>\\n			Each member can directly sponsor 5 members horizontally, and the Company will pay commission 7 level deep.</li>\\n		<li>\\n			Percentage of commission is calculated between 0.5% to 6% accordingly per level. (see chart) *</li>\\n		<li>\\n			Member can also receive personal direct discount on purchases between 1% to 10% when credit can be put into their Wallet-account.*</li>\\n		<li>\\n			It requires members to generate a minimum of one transaction a month to be qualified for your down line commission for that month.</li>\\n		<li>\\n			Minimum personal sale/purchase has to be made by midnight (of GMT world time) of the end of the month; otherwise, the commission of your down line of that month will be forfeited.</li>\\n		<li>\\n			Monthly minimum earned commission can be self managed via debit card set-up system.</li>\\n		<li>\\n			If no personal sale/purchase is made for 3 consecutive months, membership is considered stricken out of the system. Company reserves the right to replace that vacant spot.</li>\\n		<li>\\n			To be qualified as a member, one must be at least 18 years of age. A member can be a person and/or a legal entity (SS# or EIN#).</li>\\n	</ul>\\n	<p class=\\"italic text-info\\">\\n		*Company reserves the right to modify the terms and percentages accordingly with notice.</p>\\n	<h3 class=\\"italic text-info\\">\\n		Compensation plan</h3>\\n	<ul>\\n		<li>\\n			<table border=\\"0\\" cellpadding=\\"0\\" cellspacing=\\"0\\" style=\\"width:548px;\\" width=\\"545\\">\\n				<colgroup>\\n					<col />\\n					<col />\\n					<col />\\n					<col />\\n					<col span=\\"2\\" />\\n				</colgroup>\\n				<tbody>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;width:63px;\\">\\n							&nbsp;</td>\\n						<td style=\\"width:107px;\\">\\n							&nbsp;</td>\\n						<td style=\\"width:111px;\\">\\n							Member</td>\\n						<td style=\\"width:143px;\\">\\n							% of Commission</td>\\n						<td style=\\"width:63px;\\">\\n							&nbsp;</td>\\n						<td style=\\"width:63px;\\">\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 1&nbsp;</td>\\n						<td>\\n							5</td>\\n						<td>\\n							From 0.5% to 1%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 2&nbsp;</td>\\n						<td>\\n							25</td>\\n						<td>\\n							From 0.5% to 3%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 3&nbsp;</td>\\n						<td>\\n							125</td>\\n						<td>\\n							From 0.5% to 6%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 4&nbsp;</td>\\n						<td>\\n							625</td>\\n						<td>\\n							From 0.5% to 2%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 5&nbsp;</td>\\n						<td>\\n							3125</td>\\n						<td>\\n							From 0.5% to 3%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 6&nbsp;</td>\\n						<td>\\n							15625</td>\\n						<td>\\n							From 0.5% to 4%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							Level 7&nbsp;</td>\\n						<td>\\n							78125</td>\\n						<td>\\n							From 0.5% to 6%</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"50\\">\\n						<td height=\\"50\\" style=\\"height:50px;\\">\\n							&nbsp;</td>\\n						<td style=\\"width:107px;\\">\\n							Total Members</td>\\n						<td>\\n							97655</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"20\\">\\n						<td height=\\"20\\" style=\\"height:20px;\\">\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"20\\">\\n						<td height=\\"20\\" style=\\"height:20px;\\">\\n							&nbsp;</td>\\n						<td style=\\"width:107px;\\">\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n						<td>\\n							&nbsp;</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td colspan=\\"5\\">\\n							Company pays commission: 5-wide and 7-levels deep</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td colspan=\\"5\\">\\n							Member&#39;s personal discount may vary between&nbsp; 1% to 10%.</td>\\n					</tr>\\n					<tr height=\\"25\\">\\n						<td height=\\"25\\" style=\\"height:25px;\\">\\n							&nbsp;</td>\\n						<td colspan=\\"5\\">\\n							Note: All commission and discounts based on selling price</td>\\n					</tr>\\n				</tbody>\\n			</table>\\n		</li>\\n	</ul>\\n</div>\\n<div class=\\"heading\\">\\n	<h3>\\n		Marketing Policy</h3>\\n</div>\\n<div>\\n	<ul>\\n		<li>\\n			BVN&#39;S Marketing Policies are the rules and regulations governing how an Independent Representative (&quot;IR&quot;) may advertise and promote his/her BVN Relationship marketing business.</li>\\n		<li>\\n			Before becoming an IR, an applicant must take the time to read this document carefully and fully, So that an IR may understand the rules regarding the advertising and promotion of BVN&#39;s product lines, services and opportunity. This document is incorporated by reference into the BVN Agreement, which means that when a new IR signs that document, he/she agrees to abide by each and every one of these rules.</li>\\n		<li>\\n			BVN reserves the right to change the Marketing Policy from time to time. In order to be sure that you are in compliance with the rules in place, all IRs should regularly check for announcements and changes at www.BellaVieNetWork.com. Those changes will be deemed to be agreed upon by each IR as an ongoing obligation undertaken in the initial contract.</li>\\n		<li>\\n			If an Independent Representative has questions about the BVN Marketing Policy or believes a BVN IR is in violation of the Policy, please contact the Compliance Department at the BVN home office at Support@Bellavienetwork.com.</li>\\n	</ul>\\n	<h3 style=\\"color: rgb(254, 143, 1); text-transform: uppercase; margin: 15px 0px 10px;\\">\\n		<span style=\\"color:#4b0082;\\">ADVERTISING</span></h3>\\n	<ul>\\n		<li>\\n			All IRs shall safeguard and promote the good reputation of BVN and its products and services.</li>\\n		<li>\\n			When promoting BVN&#39;s products, services and opportunity, IRs must use the sales tools and support materials produced by BVN. The Company has carefully designed its products, product labels, Compensation Plan and promotional materials to ensure that they are promoted in a fair and truthful manner, that they are substantiated and that the materials comply with the legal requirements of federal and state laws.</li>\\n		<li>\\n			Recruited members are expected to read and sign the understanding of the membership&#39;s terms and conditions and to abide by them.</li>\\n	</ul>\\n</div>\\n<br />');
INSERT INTO `web_content` VALUES(3, 'about', '', '<h3>\\n	Mission Statement</h3>\\n<p style=\\"margin: 0px 0px 16px; font-size: 14px;\\">\\n	<span style=\\"font-size:14px;\\"><span style=\\"letter-spacing: 0.0px\\">The Bella Vie Network, is designed to help our members earn a supplemental income through Relationship Marketing. You can do so by simply generating some personal sales/purchases of our many listed consumable products and referring the system to others. Our goal is to help anybody by producing some extra income for those rainy days that many may encounter, and providing the opportunity for substantial growth. Financial benefits of this program can help make a difference in people&rsquo;s lives by simply rethinking our financial decisions and redirecting them into a more valuable future investment for yourselves, families, friends, and community.&nbsp;</span></span></p>\\n<p style=\\"margin: 0px 0px 16px; font-size: 14px;\\">\\n	<span style=\\"font-size:14px;\\"><span style=\\"letter-spacing: 0.0px\\">We have made our system very easy and affordable to help make everyone&rsquo;s life a true &ldquo;Bella Vie&rdquo; (Beautiful Life). As a company, we strongly believe in giving back to our community and the less fortunate. BVN simply donates 1% of every dollar received to charities and gives our members the option of making your monthly purchase a Personal Donation to the charity organization of your choice.&nbsp;</span></span></p>\\n<p style=\\"margin: 0px 0px 16px; font-size: 14px;\\">\\n	<span style=\\"font-size:14px;\\"><span style=\\"letter-spacing: 0.0px\\">It is our mission at Bella Vie Network to help make Life Beautiful.</span></span></p>\\n<p style=\\"margin: 0px 0px 16px; font-size: 14px; min-height: 18px;\\">\\n	&nbsp;</p>\\n<p style=\\"margin: 0px 0px 16px; font-size: 14px;\\">\\n	<span style=\\"font-size:14px;\\"><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>&ldquo;Bella Vie&rdquo;</i></span><span style=\\"letter-spacing: 0.0px\\"> the </span><span style=\\"text-decoration: underline ; letter-spacing: 0.0px\\"><i>Beautiful Life</i></span><span style=\\"letter-spacing: 0.0px\\"> we wait to share with you all.</span></span></p>\\n<div>\\n	&nbsp;</div>');
INSERT INTO `web_content` VALUES(4, 'contact', '', '<h4>\\n	Bella Vie Network, LLC</h4>\\n<address>\\n	<div id=\\"divRa_Address\\">\\n		2360 Corporate Circle &middot; Suite 400</div>\\n	<span id=\\"spnRa_City\\">Henderson</span>,&nbsp;<span id=\\"spnRa_State\\">NV</span>&nbsp;<span id=\\"spnRa_PostalCode\\">89074-7722<br />\\n	<br />\\n	36 Technology Drive, Suite 250B<br />\\n	Irvine Ca 92618</span></address>\\n<h4>\\n	Customer Service</h4>\\n<dl>\\n	<dt>\\n		For the quickest response to questions or concerns regarding your account, billing, or your commercial, please use the email listed below.</dt>\\n	<br />\\n	<dt>\\n		E-mail: <a href=\\"mailto:support@bellavienetwork.com\\">support@bellavienetwork.com</a></dt>\\n</dl>');
INSERT INTO `web_content` VALUES(5, 'home', '', '<div style=\\"text-align: center;\\">\\n	<span style=\\"font-family: trebuchet ms,helvetica,sans-serif;\\"><span style=\\"font-size: 48px;\\">RELATIONSHIP NETWORK</span><br />\\n	<br />\\n	<span style=\\"color: rgb(255, 140, 0);\\"><span style=\\"font-size: 36px;\\">&#39;The shopping network for Members:<br />\\n	BUY TO USE &amp; TO EARN.&#39;</span></span></span></div>\\n<br />\\n<br />\\n<span style=\\"font-family: tahoma,geneva,sans-serif;\\"><span style=\\"font-size: 24px;\\"><span style=\\"font-family: trebuchet ms,helvetica,sans-serif;\\">NO sign up cost<br />\\n<br />\\nGet personal discount on your purchases<br />\\n<br />\\nJust buy / sell products&nbsp;and refer to others.<br />\\nEarn your commission Upon their personal sales</span></span></span><br />\\n<br />');
INSERT INTO `web_content` VALUES(6, 'policy', '', '<h3>\\n	<strong>PRIVACY POLICY</strong><br />\\n	<strong>ACCEPTANCE OF ENROLLMENT TERMS AND CONDITIONS OF SERVICE</strong><br />\\n	These Membership Terms and Conditions of Service (&ldquo;Agreement&rdquo;) constitute a binding legal agreement and are entered into by, as applicable, the Member signing these Terms or any document that references these Terms or that accepts these Terms electronically (&ldquo;Member&quot;) and Bella Vie Network (&ldquo;BVN&rdquo;). By registering for or by using Bellavienetwork.com&rsquo;s E-commerce service (&ldquo;Service&ldquo;). Member accepts this Agreement and any modi&shy;fications that may be made to the Agreement from time to time, whether Member is acting on its own behalf or on behalf of a third party, including their downline customers, Member further agrees that any of its agents, representatives, employees, or any person or entity acting on Member By checking the &ldquo;I Agree to the Policies and Procedures Terms and Conditions&rdquo; box, Member agrees to be bound by this Agreement.<br />\\n	&nbsp;<br />\\n	<strong>bella vie network&#39;s E-commerce website:</strong><br />\\n	If Member is a registered user, the Service will allow Member, in accordance with this Agreement, to have access to shopping network and to purchase products and services and to refer to others to do the same in order to be qualified for commission earning program.&nbsp;<br />\\n	See details in Policies and Procedures in Business Resources.<br />\\n	&nbsp;<br />\\n	<strong>ELIGIBILITY</strong><br />\\n	Customer must be 18 years of age or older to use Bella Vie Network&#39;s Service. By registering for or using Bella Vie Network&rsquo;s Service. Member represents and warrants that the profile information submitted is true and accurate and that Member is 18 years of age or older and fully able and competent to enter into, and abide by the Terms and Conditions of Service of this Agreement.<br />\\n	&nbsp;<br />\\n	<strong>ACCOUNT REGISTRATION</strong><br />\\n	Member must be registered for and maintain a BellaVienetwork.com account to use the Service. In order to submit the Enrollment Application, Member must enter a valid Identification that is in Customer&rsquo;s name, or in the Business Entity that Member is otherwise authorized to use. When registering for an account, Member must provide accurate and complete information and promptly update this information to keep it current. If Member provides any information that is inaccurate or incomplete, or Bella Vie Network believes that the information is inaccurate or incomplete, Bella Vie Network may suspend or terminate Member&rsquo;s account and use of Service.<br />\\n	<br />\\n	Member is solely responsible for all activities that occur through Member&#39;s account. To protect Member&rsquo;s account from unauthorized use, the account username or password should not be provided to anyone else. Member should notify Bella Vie Network immediately of any unauthorized use of Member account or any other breach of security.<br />\\n	&nbsp;<br />\\n	<strong>Third Party Cookies</strong><br />\\n	When visiting other sites that are linked to our website, the third party website may place or recognize a unique cookie on your browser. Because we do not control the privacy policies of these businesses, you should check the privacy policy of the site you are visiting if you have any concerns about that site&rsquo;s use of cookies or about the site&rsquo;s use of your information.<br />\\n	<strong>Questions</strong><br />\\n	If you have any questions about this privacy statement, the practices of this site, or your dealings with this website, you can contact:<br />\\n	<br />\\n	Bella Vie Network, LLC.<br />\\n	Mailing Address:<br />\\n	500 Rainbow Blvd., Suite A, Las Vegas, Nevada 89107<br />\\n	Support@bellavienetwork.com<br />\\n	<br />\\n	Changes to the Policy<br />\\n	<br />\\n	We may make changes to this policy from time to time. We will post changes to this policy here, so be sure to check back periodically.&nbsp;</h3>');
INSERT INTO `web_content` VALUES(7, 'term', '', '<h3>\\n	<strong>TERMS OF USE:</strong><br />\\n	&nbsp;<br />\\n	<strong>ACCEPTANCE OF ADVERTISING TERMS AND CONDITIONS OF SERVICE</strong><br />\\n	<strong>Please read this policy carefully so that you understand our online privacy practices. </strong><br />\\n	&nbsp;<br />\\n	<strong>TYPES OF INFORMATION</strong><br />\\n	The information collected by Bella Vie Network, LLC falls into two categories. The first category includes information, personal or otherwise, that you voluntarily supply to us online. The second category is information that is generated automatically as you navigate online through our website. This may include usage patterns and user preferences.<br />\\n	&nbsp;<br />\\n	<strong>BROWSER AND IP ADDRESS INFORMATION</strong><br />\\n	Our web servers automatically collect limited information about your computer configuration when you visit our site. This information may include items such as the browser software you use, the operating system you are running, the website that referred you, and your IP address. Your IP address is used to identify your computer so that we can send you the data you have requested (Your IP address does not tell us who you are). We also use IP address information for systems administration, troubleshooting and to measure traffic within our site.<br />\\n	&nbsp;<br />\\n	<strong>COOKIES</strong><br />\\n	We use &ldquo;cookies&rdquo; in a limited way to help deliver our web pages and to identify unique browsers that visit our website. We also use cookies to track usage throughout our site. The only personal information a cookie can contain is information you supply.<br />\\n	&nbsp;<br />\\n	<strong>CONTESTS AND PROMOTIONS</strong><br />\\n	From time to time, we may offer contests or other promotions on our website. If you enter one of these contests or promotions, you will need to provide BellaVieNetwork with information about yourself such as your name, address and email address so that we can contact you if you win. By entering a contest or promotion, you allow BellaVieNetwork to use your name and address for mailing-list purposes, and winners grant Bella Viet Network LLC the right to use their names and/or likenesses for promotional purposes without further compensation. If you do not want us to collect or use this kind of information then please do not enter the contest or promotion.<br />\\n	&nbsp;<br />\\n	<strong>LINKING</strong><br />\\n	This site contains links to other sites. This site is not responsible for the privacy practices or the content of such websites. When linking to another site, you may still see our site&rsquo;s logo or frame. This is to provide you with a better experience when visiting our website. In these cases you are no longer on Bella Vie Network.com and are now on a different website whose information collection practices may be different than ours. Please check the privacy policy of those sites to determine how they collect personal information.<br />\\n	&nbsp;<br />\\n	<strong>FEES AND PAYMENT</strong><br />\\n	Member understands and agrees that the prices for each order are acceptable with its applicable taxes and shipping costs which are voluntarily and willingly placed by Member. Member provides credit card info or authorizes money from his/her own MyWallet Account when places the Order. Once the Order is completed and Customer has agreed to the Terms.<br />\\n	&nbsp;<br />\\n	<strong>AUTHORIZATION TO CHARGE CREDIT CARD</strong><br />\\n	By self placing a Sales /Purchase Order to BellaVieNetwork, Member authorizes Bella Vie Network, LLC. to charge the credit card (which Customer represents and warrants that Customer is authorized to use) all applicable fees for the online purchase order, in United States dollars, including all applicable taxes, as described in this Agreement.<br />\\n	&nbsp;<br />\\n	<strong>ORDER CANCELLATION</strong><br />\\n	Member may cancel an Order within 3 business days from order date. Cancellation after 3 business days (5 days for Alaska), is not valid. &nbsp;<strong>No refunds will be due or payable to Customer should Customer cancel an Order(s) after 3 business days, especially after Oder has been shipped. </strong>If paying by Credit Card, Member warrants to Bella Vie Network that he/she/they is authorized by Member and by the entity issuing the credit card described above, to make changes to such credit card and to sign on behalf of the credit card holder. Furthermore, by execution hereof and by providing the credit card information listed above, the Member fully authorizes Bella Vie Network, LLC to charge such credit card for the orders placed.<br />\\n	&nbsp;<br />\\n	<strong>EXPIRED OR REFUSED CREDIT CARD</strong><br />\\n	If Bella Vie Network, LLC does not receive payment from Member&rsquo;s credit card provider or if Member&rsquo;s credit card is expired or is rejected, Member&rsquo;s order will not be valid. In the event Member&rsquo;s credit card provider reverses any charges related to Customer&rsquo;s payment for purchase order, Member&#39;s Order will be cancelled and will not be shipped.<br />\\n	&nbsp;<br />\\n	<strong>RESTRICTIONS</strong><br />\\n	Member must comply with all applicable laws when using Bella Vie Network&#39;s website. Except as may be expressly permitted by applicable law or authorized by Bella Vie Network in writing, Member will not, and will not permit anyone else to: (a) use any automated tool to use Service; (b) rent, lease, or sublicense Customer&rsquo;s access to Service to another person; (c) circumvent or disable any digital rights management, usage rules, or other security features of Service; (d) use Service in a manner that threatens the integrity, performance, or availability of Service; (e) remove, alter, or obscure any proprietary notices (including copyright notices) on any portion of Service.<br />\\n	&nbsp;<br />\\n	<strong>TERM AND TERMINATION</strong><br />\\n	The term of Membership shall commence on the effective date set forth in the Enrollment Application and continue to be valid as long as Member is generating personal order on a monthly basis in order to be qualified for their down line purchases&#39; commissions. Membership is cancelled upon 3 consecutive non active status of personal sale order. Bella Vie Network may terminate this Agreement and Customer&rsquo;s account at any time for any reason by providing Customer with notice in any reasonable manner, including via email and via notices posted on the applicable Service. Member&rsquo;s rights under this Agreement will terminate automatically if Customer breaches any material part of this Agreement.<br />\\n	<br />\\n	Upon termination of this Agreement and closure of Member&rsquo;s Bella Vie Network account, any pending commission will be honored for orders up to before termination date.<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>OWNERSHIP</strong><br />\\n	Bella Vie Network and its affiliates and licensors own all right, title, and interest, including all intellectual property rights, in and to the Services. Except for those rights expressly granted in this Agreement, no other rights are granted, either expressed or implied, to Member.<br />\\n	&nbsp;<br />\\n	<strong>DISCLAIMER OF WARRANTIES</strong><br />\\n	MEMBER&rsquo;S USE OF BELLA VIE NETWORK&rsquo;S SERVICE IS AT MEMBER&#39;S RISK. THE SERVICES ARE PROVIDED ON AN &ldquo;AS IS&rdquo; AND &ldquo;AS AVAILABLE&rdquo; BASIS. BELLA VIE NETWORK EXPRESSLY DISCLAIMS ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE, AND NON-INFRINGEMENT. BELLA VIE NETWORK DOES NOT GUARANTEE THE ACCURACY, COMPLETENESS, OR USEFULNESS OF SERVICE AND PRODUCTS AND MEMBER RELIES ON SERVICE AND CUSTOMER AT CUSTOMER&rsquo;S OWN RISK. ANY USER CONTENT TRANSMITTED THROUGH THE USE OF SERVICE IS DONE AT CUSTOMER&rsquo;S OWN DISCRETION AND RISK AND CUSTOMER WILL BE SOLELY RESPONSIBLE FOR ANY LOSS OF DATA THAT RESULTS FROM THE TRANSMISSION OF ANY MATERIAL THROUGH SERVICE. NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED BY MEMBER FROM BELLA VIE NETWORK OR THROUGH OR FROM SERVICE WILL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THIS AGREEMENT.<br />\\n	&nbsp;<br />\\n	<strong>LIMITATION OF LIABILITY</strong><br />\\n	NEITHER BELLA VIE NETWORK NOR ITS LICENSORS OR SUPPLIERS WILL BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR EXEMPLARY DAMAGES, INCLUDING BUT NOT LIMITED TO, DAMAGES FOR LOSS OF PROFITS, GOODWILL, USE, DATA OR OTHER INTANGIBLE LOSSES (EVEN IF BELLA VIE NETWORK HAS BEEN ADVISED OF THE POSSIBILITY OF THESE DAMAGES), RESULTING FROM MEMBER&rsquo;S USE OF SERVICE AND PRODUCTS. UNDER NO CIRCUMSTANCES WILL BELLA VIE NETWORK&#39;S TOTAL LIABILITY OF ALL KINDS ARISING OUT OF OR RELATED TO THIS AGREEMENT, REGARDLESS OF THE FORUM AND REGARDLESS OF WHETHER ANY ACTION OR CLAIM IS BASED ON CONTRACT, TORT, OR OTHERWISE, EXCEED THE GREATER OF THE TOTAL AMOUNT PAID BY MEMBER TO BELLA VIE NETWORK FOR THE ORDER GIVING RISE TO THE CLAIM..<br />\\n	<br />\\n	EACH PROVISION OF THIS AGREEMENT THAT PROVIDES FOR A LIMITATION OF LIABILITY, DISCLAIMER OF WARRANTIES, OR EXCLUSION OF DAMAGES IS TO ALLOCATE THE RISKS UNDER THIS AGREEMENT BETWEEN THE PARTIES. THIS ALLOCATION IS REFLECTED IN THE PRICING OFFERED BY BELLA VIE NETWORK OR SUPPLIERS TO MEMBER AND IS AN ESSENTIAL ELEMENT OF THE BASIS OF THE BARGAIN BETWEEN THE PARTIES. EACH OF THESE PROVISIONS IS SEVERABLE AND INDEPENDENT OF ALL OTHER PROVISIONS OF THIS AGREEMENT. THE LIMITATIONS IN THIS SECTION WILL APPLY NOTWITHSTANDING THE FAILURE OF ESSENTIAL PURPOSE OF ANY LIMITED REMEDY UNDER THIS AGREEMENT.<br />\\n	<br />\\n	<strong>Indemnity</strong> Customer defends, indemnifies and holds Bella Vie Network, and its subsidiaries, affiliates, officers, agents, employees, licensors, and suppliers harmless from any costs, damages, expenses, and liability caused by the Customer&rsquo;s ad or Customer&rsquo;s use of Service, Customer&rsquo;s violation of this Agreement, or Customer&rsquo;s violation of any rights of a third party through use of Products and Services.<br />\\n	<br />\\n	<strong>Updates to this Agreement</strong> BVM may occasionally update this Agreement. When updates are made, Customer may view the most current version at <a href=\\"http://www.bellavienetwork.com/\\">http://www.BellaVieNetwork.com</a>. It is Customer&rsquo;s responsibility to review the most recent version of the Agreement and remain informed about any changes to it. By continuing to use Customer&rsquo;s Bella Vie Network account or Service, Customer consents to any updates to this Agreement. This version of the Agreement supersedes all earlier versions, and comprises the entire agreement between Customer and Bella Vie Network regarding the Service.<br />\\n	<br />\\n	<strong>General Legal Notices</strong> Bella Vie Network&rsquo;s failure to act in a particular circumstance does not waive its ability to act with respect to that circumstance or similar circumstances. Bella Vie Network will have no liability to Customer for any failure or delay in the performance of its obligations under this Agreement on account of strikes, shortages, riots, acts of terrorism, insurrection, fires, flood, storm, explosions, earthquakes, internet outages, computer virus, acts of god, war, governmental action, or any other cause that is beyond Bella Vie Network&rsquo;s reasonable control.<br />\\n	<br />\\n	By using Service, Customer consents to receiving electronic communications from Bella Vie Network. These communications will include notices about Member&rsquo;s account and information concerning or related to the Service. Member agrees that any notice, agreements, disclosure or other communications that Bella Vie Network sends to Member electronically will satisfy any legal communication requirements, including that such communications be in writing.<br />\\n	<br />\\n	This Agreement is governed by the laws of the State of Nevada, excluding conflicts of law principles. Any legal actions against Bella Vie Network must be commenced within one year after the claim arose. Customer irrevocably consents to the exclusive jurisdiction of the federal, state and local courts located in the State of Nevada.<br />\\n	<br />\\n	Any controversy or claim arising out of or relating to Service or this Agreement will be settled by binding arbitration in accordance with the commercial arbitration rules of the American Arbitration Association. Any such controversy or claim shall be arbitrated on an individual basis, and shall not be consolidated in any arbitration with any claim or controversy of any other party. The arbitration will be conducted in Las Vegas, Nevada, and judgment on the arbitration award may be entered into any court having jurisdiction thereof. The award of the arbitrator shall be final and binding upon the parties without appeal or review except as permitted by Nevada law. Notwithstanding the foregoing, either party may seek any interim or preliminary injunctive relief from any court of competent jurisdiction, as necessary to protect the party&rsquo;s rights or property pending the completion of arbitration.<br />\\n	<strong>CONTACTING Bella vie network</strong><br />\\n	Any questions or concerns about this Agreement or Service should be sent with a thorough description by email to <a href=\\"mailto:support@PlayMyAd.com?Subject=Concerns%20about%20Agreement%20or%20Service\\">support@BellaVieNetwork.com</a>.&nbsp;</h3>');
INSERT INTO `web_content` VALUES(8, 'procedure', '', '<div style=\\"text-align: center;\\">\\n	<span style=\\"font-size:22px;\\"><span style=\\"font-size:36px;\\"><strong>Bella Vie Network, LLC</strong></span><br />\\n	&nbsp;<br />\\n	<span style=\\"font-size:26px;\\"><strong>STATEMENT OF POLICIES</strong><br />\\n	<strong><em>and</em></strong><br />\\n	<strong>PROCEDURES</strong><br />\\n	<span style=\\"font-size:20px;\\"><em>Effective __________</em></span></span><br />\\n	&nbsp;<br />\\n	<strong>TABLE OF CONTENTS</strong></span></div>\\n<div>\\n	&nbsp;</div>\\n<div style=\\"margin-left: 80px;\\">\\n	SECTION 1 &ndash; COMPANY MISSION STATEMENT.. 1</div>\\n<div style=\\"margin-left: 80px;\\">\\n	SECTION 2 - INTRODUCTION.. 1<br />\\n	<strong>2.1 - Policies and Compensation Plan Incorporated into Member Agreement</strong>. 1<br />\\n	<strong>2.2 - Changes to the Agreement</strong>. 1<br />\\n	<strong>2.3 - Policies and Provisions Severable</strong>. 2<br />\\n	<strong>2.4 - Waiver</strong>.. 2</div>\\n<div style=\\"margin-left: 80px;\\">\\n	SECTION 3 - BECOMING A MEMBER.. 2<br />\\n	<strong>3.1 - Requirements to Become a Member</strong>.. 2<br />\\n	<strong>3.2 - Product Purchases</strong>. 2<br />\\n	<strong>3.3 - Member Benefits</strong>. 2<br />\\n	<strong>3.4 - Term and Renewal of Your Bella Vie Network Business</strong>. 3<br />\\n	SECTION 4 - OPERATING A Bella Vie Network BUSINESS. 3<br />\\n	<strong>4.1 - Adherence to the Bella Vie Network Compensation Plan</strong>.. 3<br />\\n	<strong>4.2 - Advertising</strong>.. 3<br />\\n	<strong>4.2.1 - General</strong> 3<br />\\n	<strong>4.2.2 - Trademarks and Copyrights</strong>. 4<br />\\n	<strong>4.2.3 - Media and Media Inquiries</strong>. 4<br />\\n	<strong>4.2.4 - Unsolicited Email</strong> 5<br />\\n	<strong>4.2.5 - Unsolicited Faxes</strong>. 5<br />\\n	<strong>4.2.6 - Telephone Directory Listings</strong>. 5<br />\\n	<strong>4.2.7 - Television and Radio Advertising</strong>. 6<br />\\n	<strong>4.2.8 - Advertised Prices</strong>. 6<br />\\n	<strong>4.3 - Online Conduct</strong>. 6<br />\\n	<strong>4.3.1 - Online Classifieds</strong>. 6<br />\\n	<strong>4.3.2 - eBay / Online Auctions</strong>. 6<br />\\n	<strong>4.3.3 - Online Retailing</strong>. 6<br />\\n	<strong>4.3.4 - Spam Linking</strong>. 6<br />\\n	<strong>4.3.5 - Digital Media Submission (YouTube, iTunes, PhotoBucket etc.)</strong> 6<br />\\n	<strong>4.3.6 - Domain Names and Email Addresses</strong>. 7<br />\\n	<strong>4.3.7 - Social Media</strong>. 7<br />\\n	<strong>4.4 - Business Entities</strong>. 8<br />\\n	<strong>4.5 - Change of Sponsor</strong>.. 8<br />\\n	<strong>4.6 - Waiver of Claims</strong>. 8<br />\\n	<strong>4.7 - Unauthorized Claims and Actions</strong>. 9<br />\\n	<strong>4.7.1 - Indemnification</strong>. 9<br />\\n	<strong>4.7.2 - Product Claims</strong>. 9<br />\\n	<strong>4.7.3 - Compensation Plan Claims</strong>. 9<br />\\n	<strong>4.7.4 - Income Claims</strong>. 10<br />\\n	<strong>4.7.5 - Income Disclosure Statement</strong> 10<br />\\n	<strong>4.8 - Repackaging and Re-labeling Prohibited</strong>.. 11<br />\\n	<strong>4.9 - Commercial Outlets</strong>. 11<br />\\n	<strong>4.10 - Conflicts of Interest</strong>. 11<br />\\n	<strong>4.10.1 - Nonsolicitation</strong>. 11<br />\\n	<strong>4.10.2 - Member Participation in Other Network Marketing Programs</strong>. 12<br />\\n	<strong>4.10.3 - Confidential Information</strong>. 12<br />\\n	<strong>4.11 - Targeting Other Direct Sellers</strong>. 13<br />\\n	<strong>4.12 - Errors or Questions</strong>. 14<br />\\n	<strong>4.13 - Governmental Approval or Endorsement</strong>. 14<br />\\n	<strong>4.14 - Holding Applications or Orders</strong>. 14<br />\\n	<strong>4.15 - Income Taxes</strong>. 14<br />\\n	<strong>4.16 - Independent Contractor Status</strong>. 14<br />\\n	<strong>4.17 - International</strong> <strong>Marketing</strong>.. 14<br />\\n	<strong>4.18 - Adherence to Laws and Ordinances</strong>. 14<br />\\n	<strong>4.19 - Separation of a Bella Vie Network Business</strong>. 15<br />\\n	<strong>4.20 - Sponsoring Online</strong>. 15<br />\\n	<strong>4.21 - Telemarketing Techniques</strong>. 15<br />\\n	<strong>4.22 - Back Office Access</strong>. 16<br />\\n	SECTION 5 - RESPONSIBILITIES OF MEMBERS. 17<br />\\n	<strong>5.1 - Change of Address, Telephone, and E-Mail Addresses</strong>. 17<br />\\n	<strong>5.2 - Continuing Development Obligations</strong>. 17<br />\\n	<strong>5.2.1 - Ongoing Training</strong>. 17<br />\\n	<strong>5.2.2 - Increased Training Responsibilities</strong>. 17<br />\\n	<strong>5.2.3 - Ongoing Sales Responsibilities</strong>. 17<br />\\n	<strong>5.3 - Nondisparagement</strong>. 18<br />\\n	<strong>5.4 - Providing Documentation to Applicants</strong>. 18<br />\\n	SECTION 6 - SALES REQUIREMENTS. 18<br />\\n	<strong>6.1 - Product Sales</strong>. 18<br />\\n	<strong>6.2 - No Territory Restrictions</strong>. 18<br />\\n	<strong>6.3 - Sales Receipts</strong>. 18<br />\\n	SECTION 7 - BONUSES AND COMMISSIONS. 19<br />\\n	<strong>7.1 - Bonus and Commission Qualifications and Accrual</strong>. 19<br />\\n	<strong>7.2 - Adjustment to Bonuses and Commissions</strong>. 19<br />\\n	<strong>7.2.1 - Adjustments for Returned Products and Cancelled Services</strong>. 19<br />\\n	<strong>7.2.2 - Commissions</strong>. 19<br />\\n	<strong>7.2.3 - Tax Withholdings</strong>. 20<br />\\n	<strong>7.3 - Reports</strong>. 20<br />\\n	SECTION 8 - PRODUCT GUARANTEES, RETURNS AND INVENTORY REPURCHASE.. 21<br />\\n	<strong>8.1 - Rescission</strong>.. 21<br />\\n	<strong>8.2 - Returns by Retail Customers</strong>. 21<br />\\n	<strong>8.3 - Montana Residents</strong>. 21<br />\\n	<strong>8.4 - Procedures for All Returns</strong>. 21<br />\\n	SECTION 9 - DISPUTE RESOLUTION AND DISCIPLINARY PROCEEDINGS. 22<br />\\n	<strong>9.1 - Disciplinary Sanctions</strong>. 22<br />\\n	<strong>9.2 - Grievances and Complaints</strong>. 23<br />\\n	<strong>9.3 - Mediation</strong>.. 23<br />\\n	<strong>9.4 - Arbitration</strong>.. 23<br />\\n	<strong>9.5 - Governing Law, Jurisdiction and Venue</strong>. 24<br />\\n	<strong>9.5.1 - Louisiana Residents</strong>. 25<br />\\n	SECTION 10 - PAYMENTS. 25<br />\\n	<strong>10.1 - </strong><strong>Restrictions on Third Party Use of Credit Cards and Checking Account Access</strong>. 25<br />\\n	<strong>10.2 - Sales Taxes</strong>. 25<br />\\n	SECTION 11 - INACTIVITY AND CANCELLATION.. 25<br />\\n	<strong>11.1 - Effect of Cancellation</strong>.. 25<br />\\n	<strong>11.2 - Cancellation Due to Inactivity</strong>.. 26<br />\\n	<strong>11.2.1 - Failure to Meet PV Quota</strong>. 26<br />\\n	<strong>11.2.2 - Failure to Earn Commissions</strong>. 26<br />\\n	<strong>11.2.3 &ndash; Continuation of Autoship</strong>. 26<br />\\n	<strong>11.3 - Involuntary Cancellation</strong>.. 26<br />\\n	<strong>11.4 - Voluntary Cancellation</strong>.. 26<br />\\n	SECTION 12 - DEFINITIONS. 27</div>\\n<div style=\\"margin-left: 80px;\\">\\n	<br clear=\\"all\\" />\\n	&nbsp;</div>\\n<h1 style=\\"margin-left: 80px;\\">\\n	&nbsp;</h1>\\n<div style=\\"margin-left: 80px;\\">\\n	SECTION 1 - COMPANY MISSION STATEMENT</div>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>Bella Vie Network Mission Statement</strong><br />\\n	&nbsp;<br />\\n	<strong><em>Bella Vie Network is not just another MLM business, but it is driven by the eagerness of helping members making life better and more beautiful. Sometimes, just a little positive thinking to put into action can make a difference. As we all need to purchase things for our daily needs, members can still do just that but with a return of extra benefits by sharing their goodness with others. It is a sharing and caring network so together we can help each other save and earn some supplemental income without stocking or selling. It is a simple system which can make a difference when our major focus is to help each other making simple life beautiful and meaningful.</em></strong><br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 2 - </strong><strong>INTRODUCTION</strong><br />\\n	&nbsp;<br />\\n	<strong>2.1 - </strong><strong>Policies and Compensation Plan Incorporated into Member Agreement</strong><br />\\n	These Policies and Procedures and the Compensation Plan, in their present form and as amended by Bella Vie Network, LLC (hereafter &ldquo;Bella Vie Network&rdquo; or the &ldquo;Company&rdquo;), are incorporated into, and form an integral part of, the Bella Vie Network Member Agreement.&nbsp; It is the responsibility of each Member to read, understand, adhere to, and insure that he or she is aware of and operating under the most current version of these Policies and Procedures.&nbsp; Throughout these Policies, when the term &ldquo;Agreement&rdquo; is used, it collectively refers to the Bella Vie Network Member Application and Agreement, these Policies and Procedures, the Bella Vie Network Compensation Plan, and the Bella Vie Network Business Entity Application (if applicable).&nbsp; These documents are incorporated by reference into the Bella Vie Network Member Agreement (all in their current form and as amended by Bella Vie Network).&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>2.2 - </strong><strong>Changes to the Agreement</strong><br />\\n	Bella Vie Network reserves the right to amend the Agreement and its prices in its sole and absolute discretion.&nbsp; By executing the Member Agreement, a Member agrees to abide by all amendments or modifications that Bella Vie Network elects to make.&nbsp; Amendments shall be effective thirty (30) days after publication of notice that the Agreement has been modified.&nbsp; Amendments shall not apply retroactively to conduct that occurred prior to the effective date of the amendment.&nbsp; Notification of amendments shall be published by one or more of the following methods: (1) posting on the Company&rsquo;s official web site; (2) electronic mail (e-mail); (3) posting in Members&rsquo; back-offices; (4) inclusion in Company periodicals; (5) inclusion in product orders or bonus checks; or (6) special mailings.&nbsp; The continuation of a Member&rsquo;s Bella Vie Network business, the acceptance of any benefits under the Agreement, or a Member&rsquo;s acceptance of bonuses or commissions constitutes acceptance of all amendments.<br />\\n	&nbsp;<br />\\n	<strong>2.3 - </strong><strong>Policies and Provisions Severable</strong><br />\\n	If any provision of the Agreement, in its current form or as may be amended, is found to be invalid, or unenforceable for any reason, only the invalid portion(s) of the provision shall be severed and the remaining terms and provisions shall remain in full force and effect.&nbsp; The severed provision, or portion thereof, shall be reformed to reflect the purpose of the provision as closely as possible.<br />\\n	&nbsp;<br />\\n	<strong>2.4 - </strong><strong>Waiver</strong><br />\\n	The Company never gives up its right to insist on compliance with the Agreement and with the applicable laws governing the conduct of a business.&nbsp; No failure of Bella Vie Network to exercise any right or power under the Agreement or to insist upon strict compliance by a Member with any obligation or provision of the Agreement, and no custom or practice of the parties at variance with the terms of the Agreement, shall constitute a waiver of Bella Vie Network&rsquo;s right to demand exact compliance with the Agreement.&nbsp; The existence of any claim or cause of action of a Member against Bella Vie Network shall not constitute a defense to Bella Vie Network&rsquo;s enforcement of any term or provision of the Agreement.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 3 - </strong>&nbsp;&nbsp;<strong>BECOMING A MEMBER</strong><br />\\n	&nbsp;<br />\\n	<strong>3.1 - </strong>&nbsp;<strong>Requirements to Become a Member</strong><br />\\n	To become a Bella Vie Network Member, each applicant must:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Be at least 18 years of age;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reside in the United States or U.S. Territories or country that Bella Vie Network has officially announced is open for business;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Provide Bella Vie Network with his/her valid Social Security or Federal Tax ID number;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit a properly completed Member Application and Agreement to Bella Vie Network online;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit an IRS Form W-9.<br />\\n	&nbsp;<br />\\n	Bella Vie Network reserves the right to accept or reject any Member Application and Agreement for any reason or for no reason.<br />\\n	&nbsp;<br />\\n	<strong>3.2 - </strong>&nbsp;<strong>Product Purchases</strong><br />\\n	No person is required to purchase Bella Vie Network products, services or sales aids, or to pay any charge or fee to become a Member.<br />\\n	&nbsp;<br />\\n	<strong>3.3 - </strong><strong>Member Benefits</strong><br />\\n	Once a Member Application and Agreement has been accepted by Bella Vie Network, the benefits of the Compensation Plan and the Member Agreement are available to the new Member.&nbsp; These benefits include the right to:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sell/Buy Bella Vie Network products and services;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in the Bella Vie Network Compensation Plan (receive bonuses and commissions, if eligible);<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sponsor other individuals as Customers or Members into the Bella Vie Network business and thereby, build a marketing organization and progress through the Bella Vie Network Compensation Plan;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receive periodic Bella Vie Network literature and other Bella Vie Network communications;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in Bella Vie Network-sponsored support, service, training, motivational and recognition functions, upon payment of appropriate charges, if applicable; and<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Participate in promotional and incentive contests and programs sponsored by Bella Vie Network for its Members.<br />\\n	&nbsp;<br />\\n	<strong>3.4 - </strong><strong>Term and Renewal of Your Bella Vie Network Business</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The term of the Member Agreement is month-to-month, and is automatically renewed upon the payment of the monthly Member minimum sales order.&nbsp; Should a Member fail to pay his/her monthly sales order, the Member&rsquo;s business will be put on suspension, and will not be eligible for commissions or bonuses for that month.&nbsp; If the Member fails to generate the monthly minimum sales order for three consecutive months, the Member&rsquo;s Agreement shall be permanently cancelled.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 4 - </strong><strong>OPERATING A BELLA VIE NETWORK BUSINESS</strong><br />\\n	&nbsp;<br />\\n	<strong>4.1 - </strong><strong>Adherence to the Bella Vie Network Compensation Plan</strong><br />\\n	Members must adhere to the terms of the Bella Vie Network Compensation Plan as set forth in official Bella Vie Network literature.&nbsp; Members shall not offer the Bella Vie Network opportunity through, or in combination with, any other system, program, sales tools, or method of marketing other than that specifically set forth in official Bella Vie Network literature.&nbsp; Members shall not require or encourage other current or prospective Customers or Members to execute any agreement or contract other than official Bella Vie Network agreements and contracts in order to become a Bella Vie Network Member.&nbsp; Similarly, Members shall not require or encourage other current or prospective Customers or Members to make any purchase from, or payment to, any individual or other entity to participate in the Bella Vie Network Compensation Plan other than those purchases or payments identified as recommended or required in official Bella Vie Network literature.<br />\\n	&nbsp;<br />\\n	<strong>4.2 - </strong><strong>Advertising</strong><br />\\n	<strong>4.2.1 - </strong><strong>General</strong><br />\\n	All Members shall safeguard and promote the good reputation of Bella Vie Network and its products.&nbsp; The marketing and promotion of Bella Vie Network, the Bella Vie Network opportunity, the Compensation Plan, and Bella Vie Network products must avoid all discourteous, deceptive, misleading, unethical or immoral conduct or practices.<br />\\n	&nbsp;<br />\\n	To promote both the products and services, and the tremendous opportunity Bella Vie Network offers, Members should use the sales aids, business tools, and support materials produced by Bella Vie Network.&nbsp; The Company has carefully designed its products, product labels, Compensation Plan, and promotional materials to ensure that they are promoted in a fair and truthful manner, that they are substantiated, and the materials comply with the legal requirements of federal and state laws.<br />\\n	&nbsp;<br />\\n	Accordingly, Members must not produce or use the literature, advertisements, sales aids, business tools, promotional materials, or Internet web pages of themselves or other third parties.<br />\\n	&nbsp;<br />\\n	<strong>4.2.2 - </strong><strong>Trademarks and Copyrights</strong><br />\\n	The name of Bella Vie Network and other names as may be adopted by Bella Vie Network are proprietary trade names, trademarks and service marks of Bella Vie Network (collectively &ldquo;marks&rdquo;).&nbsp; As such, these marks are of great value to Bella Vie Network and are supplied to Members for their use only in an expressly authorized manner.&nbsp; Bella Vie Network will not allow the use of its trade names, trademarks, designs, or symbols, or any derivatives of such marks, by any person, including Bella Vie Network Members, in any unauthorized manner without its prior, written permission.&nbsp;<br />\\n	&nbsp;<br />\\n	The content of all Company sponsored events is copyrighted material.&nbsp; Members may not produce for sale or distribution any recorded Company events and speeches without written permission from Bella Vie Network, nor may Members reproduce for sale or for personal use any recording of Company-produced audio or video tape presentations.<br />\\n	&nbsp;<br />\\n	As an independent Member, you may use the Bella Vie Network name in the following manner<br />\\n	&nbsp;<br />\\n	Member&rsquo;s Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	<em>Example:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </em><br />\\n	Alice Smith&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not use the name Bella Vie Network in any form in your team name, a tagline, an external website name, your personal website address or extension, in an e-mail address, as a personal name, or as a nickname. Additionally, only use the phrase <em>Independent Bella Vie Network Member </em>in your phone greeting or on your answering machine to clearly separate your independent Bella Vie Network business from Bella Vie Network. For example, you may not secure the domain name www.buyBella Vie Network.com, nor may you create an email address such as <a href=\\"mailto:BellaVieNetworksales@hotmail.com\\">BellaVieNetworksales@hotmail.com</a>.<br />\\n	&nbsp;<br />\\n	<strong>4.2.3 - </strong><strong>Media and Media Inquiries</strong><br />\\n	Members must not attempt to respond to media inquiries regarding Bella Vie Network, its products or services, or their independent Bella Vie Network business.&nbsp; All inquiries by any type of media must be immediately referred to Bella Vie Network&rsquo;s Support Department.&nbsp; This policy is designed to assure that accurate and consistent information is provided to the public as well as a proper public image.<br />\\n	&nbsp;<br />\\n	<strong>4.2.4 - </strong><strong>&nbsp;Unsolicited Email</strong><br />\\n	&nbsp;Bella Vie Network does not permit Members to send unsolicited commercial emails unless such emails strictly comply with applicable laws and regulations including, without limitation, the federal CAN SPAM Act. Any email sent by a Member that promotes Bella Vie Network, the Bella Vie Network opportunity, or Bella Vie Network products and services must comply with the following:<br />\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			There must be a functioning return email address to the sender.</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			There must be a notice in the email that advises the recipient that he or she may reply to the email, via the functioning return email address, to request that future email solicitations or correspondence not be sent to him or her (a functioning &ldquo;opt-out&rdquo; notice).</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The email must include the Member&rsquo;s physical mailing address.</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The email must clearly and conspicuously disclose that the message is an advertisement or solicitation.</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The use of deceptive subject lines and/or false header information is prohibited.</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			All opt-out requests, whether received by email or regular mail, must be honored. If a Member receives an opt-out request from a recipient of an email, the Member must forward the opt-out request to the Company.</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;<br />\\n	Bella Vie Network may periodically send commercial emails on behalf of Members.&nbsp; By entering into the Member Agreement, Member agrees that the Company may send such emails and that the Member&rsquo;s physical and email addresses will be included in such emails as outlined above.&nbsp; Members shall honor opt-out requests generated as a result of such emails sent by the Company.<br />\\n	&nbsp;<br />\\n	<strong>4.2.5 - </strong><strong>Unsolicited Faxes</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Except as provided in this section, Members may not use or transmit unsolicited faxes in connection with their Bella Vie Network business.&nbsp; The term &quot;unsolicited faxes&quot; means the transmission via telephone facsimile or computer of any material or information advertising or promoting Bella Vie Network, its products, its compensation plan or any other aspect of the company which is transmitted to any person, except that these terms do not include a fax: (a) to any person with that person&#39;s prior express invitation or permission; or (b) to any person with whom the Member has an established business or personal relationship.&nbsp; The term &quot;established business or personal relationship&quot; means a prior or existing relationship formed by a voluntary two way communication between a Member and a person, on the basis of: (a) an inquiry, application, purchase or transaction by the person regarding products offered by such Member; or (b) a personal or familial relationship, which relationship has not been previously terminated by either party.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.2.6 - </strong><strong>Telephone Directory Listings</strong><br />\\n	Members may list themselves as an &ldquo;Independent Bella Vie Network Member&rdquo; in the white or yellow pages of the telephone directory, or with online directories, under their own name.&nbsp; No Member may place telephone or online directory display ads using Bella Vie Network&#39;s name or logo.&nbsp; Members may not answer the telephone by saying &ldquo;Bella Vie Network&rdquo;, &ldquo;Bella Vie Network Incorporated&rdquo;, or in any other manner that would lead the caller to believe that he or she has reached corporate offices of Bella Vie Network.&nbsp; If a Member wishes to post his/her name in a telephone or online directory, it must be listed in the following format:<br />\\n	&nbsp;<br />\\n	Member&#39;s Name<br />\\n	Independent Bella Vie Network Member<br />\\n	&nbsp;<br />\\n	<strong>4.2.7 - </strong><strong>Television and Radio Advertising</strong><br />\\n	Members may not advertise on television and radio except with Bella Vie Network&rsquo;s express written approval.<br />\\n	&nbsp;<br />\\n	<strong>4.2.8 - </strong><strong>Advertised Prices</strong><br />\\n	Members may not create their own marketing or advertising material offering any Bella Vie Network products at a price less than the current Autoship price plus shipping and applicable taxes.<br />\\n	&nbsp;<br />\\n	<strong>4.3 - </strong><strong>&nbsp;Online Conduct</strong><br />\\n	<strong>4.3.1 - </strong><strong>Online Classifieds</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may not use online classifieds (including Craigslist) to list, sell or retail specific Bella Vie Network products or product bundles. You <u>may</u> use online classifieds (including Craigslist) for prospecting, recruiting, sponsoring and informing the public about the Bella Vie Network business opportunity, provided Bella Vie Network-approved templates/images are used. These templates will identify you as an Independent Bella Vie Network Member. If a link or URL is provided, it must link to your Replicated Website or your Registered External Website.<br />\\n	&nbsp;<br />\\n	<strong>4.3.2 - </strong><strong>eBay / Online Auctions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network&rsquo;s products and services may not be listed on eBay or other online auctions, nor may Members enlist or knowingly allow a third party to sell Bella Vie Network products on eBay or other online auction.<br />\\n	&nbsp;<br />\\n	<strong>4.3.3 - </strong><strong>Online Retailing</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not list or sell Bella Vie Network products on any online retail store or ecommerce site, nor may you enlist or knowingly allow a third party to sell Bella Vie Network products on any online retail store or ecommerce site.<br />\\n	&nbsp;<br />\\n	<strong>4.3.4 - </strong><strong>Spam Linking</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Spam linking is defined as multiple consecutive submissions of the same or similar content into blogs, wikis, guest books, websites or other publicly accessible online discussion boards or forums and is not allowed. This includes blog spamming, blog comment spamming and/or spamdexing. Any comments you make on blogs, forums, guest books, etc., must be unique, informative and relevant.<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.3.5 - </strong><strong>Digital Media Submission (YouTube, iTunes, PhotoBucket etc.)</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may upload, submit or publish Bella Vie Network-related video, audio or photo content that they develop and create so long as it aligns with Bella Vie Network values, contributes to the Bella Vie Network community greater good and is in compliance with Bella Vie Network&rsquo;s Policies and Procedures. All submissions must clearly identify you as an Independent Bella Vie Network Member in the content itself and in the content description tag, must comply with all copyright/legal requirements, and must state that you are solely responsible for this content. Members may not upload, submit or publish any content (video, audio, presentations or any computer files) received from Bella Vie Network or captured at official Bella Vie Network events or in buildings owned or operated by Bella Vie Network without prior written permission.<br />\\n	&nbsp;<br />\\n	<strong>4.3.6 - </strong><strong>Domain Names and Email Addresses</strong><br />\\n	Except as set forth in the Member Website Application and Agreement, Members may not use or attempt to register any of Bella Vie Network&rsquo;s trade names, trademarks, service names, service marks, product names, the Company&rsquo;s name, or any derivative of the foregoing, for any Internet domain name, email address, or social media name or address.<br />\\n	&nbsp;<br />\\n	<strong>4.3.7 - </strong><strong>&nbsp;Social Media</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In addition to meeting all other requirements specified in these Policies and Procedures, should a Member utilize any form of social media, including but not limited to Facebook, Twitter, Linkedin, YouTube, or Pinterest, the Member agrees to each of the following:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No product sales or enrollments may occur on any social media site.&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; It is each Member&rsquo;s responsibility to follow the social media site&rsquo;s terms of use.&nbsp; If the social media site does not allow its site to be used for commercial activity, you must abide by the site&rsquo;s terms of use.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any social media site that is directly or indirectly operated or controlled by a Member that is used to discuss or promote Bella Vie Network&rsquo;s products or the Bella Vie Network opportunity may not link to any website, social media site, or site of any other nature, other than the Member&rsquo;s Bella Vie Network replicated website.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; During the term of this Agreement and for a period of 12 calendar months thereafter, a Member may not use any social media site on which they discuss or promote, or have discussed or promoted, the Bella Vie Network business or Bella Vie Network&rsquo;s products to directly or indirectly solicit Bella Vie Network Members for another direct selling or network marketing program (collectively, &ldquo;direct selling&rdquo;).&nbsp; In furtherance of this provision, a Member shall not take any action that may reasonably be foreseen to result in drawing an inquiry from other Members relating to the Member&rsquo;s other direct selling business activities.&nbsp; Violation of this provision shall constitute a violation of the non-solicitation provision in Section 4.11 below.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member may post or &ldquo;pin&rdquo; photographs of Bella Vie Network products on a social media site, but only photos that are provided by Bella Vie Network and downloaded from the Member&rsquo;s Back-Office may be used.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member creates a business profile page on any social media site that promotes or relates to Bella Vie Network, its products, or opportunity, the business profile page must relate exclusively to the Member&rsquo;s Bella Vie Network business and Bella Vie Network products.&nbsp; If the Member&rsquo;s Bella Vie Network business is cancelled for any reason or if the Member becomes inactive, the Member must deactivate the business profile page.<br />\\n	&nbsp;<br />\\n	<strong>4.4 - </strong><strong>Business Entities</strong><br />\\n	A corporation, limited liability company, partnership or trust (collectively referred to in this section as a &ldquo;Business Entity&rdquo;) may apply to be a Bella Vie Network Member by submitting a Member Application and Agreement along with a properly completed Business Entity Application and Agreement and a properly completed <st1:stockticker w:st=\\"on\\">IRS</st1:stockticker> form W-9. &nbsp;The equitable ownership of a Business Entity is limited to one individual (natural person).&nbsp; That is to say, the Business Entity that owns and operates a Bella Vie membership may only have one shareholder, member, manager, trustee, etc..&nbsp; The Business Entity, as well as its shareholder, member, manager, trustee, or other party with any ownership interest in, or management responsibilities for, the Business Entity (collectively &ldquo;Affiliated Parties&rdquo;) are individually, jointly and severally liable for any indebtedness to Bella Vie Network, compliance with the Bella Vie Network Policies and Procedures, the Bella Vie Network Member Agreement, and other obligations to Bella Vie Network.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To prevent the circumvention of Section 4.5, (regarding Sponsorship Changes), if any Affiliated Party wants to terminate his or her relationship with the Business Entity or Bella Vie Network, the Affiliated Party must terminate his or her affiliation with the Business Entity, notify Bella Vie Network in writing that he or she has terminated his/her affiliation with the Business Entity.&nbsp; In addition, the Affiliated Party foregoing their interest in the Business Entity may not participate in any other Bella Vie Network business for six consecutive calendar months.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The modifications permitted within the scope of this paragraph <em>do not</em> include a change of sponsorship.&nbsp; Each Member must immediately notify Bella Vie Network of all changes to type of business entity they utilize in operating their businesses and the addition or removal of business Affiliated Parties.<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	<strong>4.5 - </strong><strong>Change of Sponsor</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network strongly discourages changes in sponsorship.&nbsp; Accordingly, the transfer of a Bella Vie Network business from one sponsor to another is rarely permitted. &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.6 - </strong>&nbsp;<strong>Waiver of Claims</strong><br />\\n	In cases in which the appropriate sponsorship change procedures have not been followed, and a downline organization has been developed in the second business developed by a Member, Bella Vie Network reserves the sole and exclusive right to determine the final disposition of the downline organization.&nbsp; Resolving conflicts over the proper placement of a downline that has developed under an organization that has improperly switched sponsors is often extremely difficult.&nbsp; Therefore, <strong>MEMBERS WAIVE ANY </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> <st1:stockticker w:st=\\"on\\">ALL</st1:stockticker> CLAIMS AGAINST BELLA VIE NETWORK, ITS OFFICERS, DIRECTORS, OWNERS, EMPLOYEES, AND AGENTS THAT RELATE TO OR ARISE FROM BELLA VIE NETWORK&rsquo;S DECISION REGARDING THE DISPOSITION OF ANY DOWNLINE ORGANIZATION THAT DEVELOPS BELOW AN ORGANIZATION THAT HAS IMPROPERLY CHANGED LINES OF SPONSORSHIP.</strong>&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.7 - </strong><strong>Unauthorized Claims</strong><strong>and Actions</strong><br />\\n	<strong>4.7.1 - </strong><strong>Indemnification</strong><br />\\n	A Member is fully responsible for all of his or her verbal and written statements made regarding Bella Vie Network products, services, and the Compensation Plan that are not expressly contained in official Bella Vie Network materials.&nbsp; This includes statements and representations made through all sources of communication media, whether person-to-person, in meetings, online, through Social Media, in print, or any other means of communication.&nbsp; Members agree to indemnify Bella Vie Network and Bella Vie Network&rsquo;s directors, officers, employees, and agents, and hold them harmless from all liability including judgments, civil penalties, refunds, attorney fees, court costs, or lost business incurred by Bella Vie Network as a result of the Member&rsquo;s unauthorized representations or actions.&nbsp; This provision shall survive the termination of the Member Agreement.<br />\\n	&nbsp;<br />\\n	<strong>4.7.2 - </strong><strong>Product Claims</strong><br />\\n	No claims (which include personal testimonials) as to therapeutic, curative or beneficial properties of any products offered by Bella Vie Network may be made except those contained in official Bella Vie Network literature.&nbsp; In particular, no Member may make any claim that Bella Vie Network products are useful in the cure, treatment, diagnosis, mitigation or prevention of any diseases.&nbsp; Such statements can be perceived as medical or drug claims, and they may lack adequate substantiation. Not only are such claims in violation of the Member Agreement, they also violate the laws and regulations of the United States, Canada, and other jurisdictions.<br />\\n	&nbsp;<br />\\n	<strong>4.7.3 - </strong><strong>Compensation Plan Claims</strong><br />\\n	When presenting or discussing the Bella Vie Network Compensation Plan, you must make it clear to prospects that financial success with Bella Vie Network requires commitment, effort, and sales skill.&nbsp; Conversely, you must never represent that one can be successful without diligently applying themselves.&nbsp; Examples of misrepresentations in this area include:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; It&rsquo;s a turnkey system;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The system will do the work for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Just get in and your downline will build through spillover;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Just join and I&rsquo;ll build your downline for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The company does all the work for you;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You don&rsquo;t have to sell anything; or<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All you have to do is buy your products every month.<br />\\n	&nbsp;<br />\\n	The above are just examples of improper representations about the Compensation Plan.&nbsp; It is important that you do not make these or any other representations that could lead a prospect to believe that they can be successful as a Bella Vie Network Member without commitment, effort, and sales skill.<br />\\n	&nbsp;<br />\\n	<strong>4.7.4 - </strong><strong>Income Claims</strong><br />\\n	A Member, when presenting or discussing the Bella Vie Network opportunity or Compensation Plan to a prospective Member, may not make income projections, income claims, or disclose his or her Bella Vie Network income (including the showing of checks, copies of checks, bank statements, or tax records) unless, at the time the presentation is made, the Member provides a current copy of the Bella Vie Network Income Disclosure Statement (<st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>) to the person(s) to whom he or she is making the presentation.<br />\\n	&nbsp;<br />\\n	<strong>4.7.5 - </strong><strong>&nbsp;Income Disclosure Statement </strong><br />\\n	Bella Vie Network&rsquo;s corporate ethics compel us to do not merely what is legally required, but rather, to conduct the absolute best business practices. To this end, we have developed the Bella Vie Network Income Disclosure Statement (&ldquo;<st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>&rdquo;). The Bella Vie Network <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> is designed to convey truthful, timely, and comprehensive information regarding the income that Bella Vie Network Members earn. In order to accomplish this objective, a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be presented to all prospective Members.&nbsp; The failure to comply with this policy constitutes a significant and material breach of the Bella Vie Network Member Agreement and will be grounds for disciplinary sanctions, including termination, pursuant to Section 9.<br />\\n	&nbsp;<br />\\n	A copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be presented to a prospective Member (someone who is not a party to a current Bella Vie Network Member Agreement) anytime the Compensation Plan is presented or discussed, or any type of income claim or earnings representation is made.<br />\\n	&nbsp;<br />\\n	The terms &ldquo;income claim&rdquo; and/or &ldquo;earnings representation&rdquo; (collectively &ldquo;income claim&rdquo;) include: (1) statements of actual earnings, (2) statements of projected earnings, (3) statements of earnings ranges, (4) income testimonials, (5) lifestyle claims, and (6) hypothetical claims.<br />\\n	&nbsp;<br />\\n	A lifestyle income claim typically includes statements (or pictures) involving large homes, luxury cars, exotic vacations, or other items suggesting or implying wealth. They also consist of references to the achievement of one&#39;s dreams, having everything one always wanted, and are phrased in terms of &ldquo;opportunity&rdquo; or &ldquo;possibility&rdquo; or &ldquo;chance.&rdquo; Claims such as &ldquo;My Bella Vie Network income exceeded my salary after six months in the business,&rdquo; or &ldquo;Our Bella Vie Network business has allowed my wife to come home and be a full-time mom&rdquo; also fall within the purview of &ldquo;lifestyle&rdquo; claims.<br />\\n	&nbsp;<br />\\n	A hypothetical income claim exists when you attempt to explain the operation of the compensation plan through the use of a hypothetical example.&nbsp; Certain assumptions are made regarding some or all of the following: (1) number of personally-enrolled Customers and Members; (2) number of downline Customers and Members; (3) average sales/purchase volume/sales volume per Customer and Member; and (4) total organizational volume.&nbsp; Applying these assumptions through the compensation plan yields income figures which constitute hypothetical income claims.<br />\\n	&nbsp;<br />\\n	In any non-public meeting (e.g., a home meeting, one-on-one, regardless of venue) with a prospective Member or Members in which the Compensation Plan is discussed or any type of income claim is made, you must provide the prospect(s) with a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker>. In any meeting that is open to the public in which the Compensation Plan is discussed or any type of income claims is made, you must provide every prospective Member with a copy of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> and you must display at least one (3 foot x 5 foot poster board) in the front of the room in reasonably close proximity to the presenter(s). In any meeting in which any type of video display is utilized (e.g., monitor, television, projector, etc.) a slide of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> must be displayed continuously throughout the duration of any discussion of the Compensation Plan or the making of an income claim.<br />\\n	&nbsp;<br />\\n	Copies of the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> may be printed or downloaded without charge from the corporate website at <a href=\\"http://www.bellavienetwork.com/IDS\\">http://www.BellaVieNetwork.com/IDS</a>.<br />\\n	&nbsp;<br />\\n	Members who develop sales aids and tools in which the Compensation Plan or income claims are present must incorporate the <st1:stockticker w:st=\\"on\\">IDS</st1:stockticker> into each such sales aid or tool prior to submission to the Company for review.<br />\\n	&nbsp;<br />\\n	<strong>4.8 - </strong><strong>&nbsp;Repackaging and Re-labeling Prohibited</strong><br />\\n	Bella Vie Network products may only be sold in their original packaging.&nbsp; Members may not repackage, re-label, or alter the labels on Bella Vie Network products. Tampering with labels/packaging could be a violation of federal and state laws, and may result in civil or criminal liability. Members may affix a personalized sticker with your personal/contact information to each product or product container, as long as you do so without removing existing labels or covering any text, graphics, or other material on the product label.<br />\\n	&nbsp;<br />\\n	<strong>4.9 - </strong><strong>Commercial Ou</strong><strong>tl</strong><strong>ets</strong><br />\\n	Members may not sell Bella Vie Network products from a commercial outlet, nor may Members display or sell Bella Vie Network products or literature in any retail or service establishment.&nbsp; Online auction and/or sales facilitation websites, including but not limited to eBay and Craig&rsquo;s List constitute Commercial Outlets, and may not be used to sell Bella Vie Network products.&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	<strong>4.10 - </strong><strong>Conflicts of Interest</strong><br />\\n	<strong>4.10.1 - </strong><strong>Nonsolicitation</strong><br />\\n	Bella Vie Network Members are free to participate in other multilevel or network marketing business ventures or marketing opportunities (collectively &ldquo;network marketing&rdquo;).&nbsp; However, during the term of this Agreement, Members may not directly or indirectly Recruit other Bella Vie Network Members or Customers for any other network marketing business.&nbsp;<br />\\n	&nbsp;<br />\\n	Following the cancellation of a Member&rsquo;s Independent Member Agreement, and for a period of six calendar months thereafter, with the exception of a Member who is personally sponsored by the former Member, a former Member may not Recruit any Bella Vie Network Member or Customer for another network marketing business.&nbsp; Members and the Company recognize that because network marketing is conducted through networks of independent contractors dispersed across the entire United States and internationally, and business is commonly conducted via the internet and telephone, an effort to narrowly limit the geographic scope of this non-solicitation provision would render it wholly ineffective.&nbsp; Therefore, Members and Bella Vie Network agree that this non-solicitation provision shall apply nationwide and to all international markets in which Members are located. &nbsp;This provision shall survive the termination or expiration of the Member Agreement.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The term &ldquo;Recruit&rdquo; means the actual or attempted sponsorship, solicitation, enrollment, encouragement, or effort to influence in any other way, either directly, indirectly, or through a third party, another Bella Vie Network Member or Customer to enroll or participate in another multilevel marketing, network marketing or direct sales opportunity.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.10.2 - </strong><strong>Member Participation in Other Network Marketing Programs</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is engaged in other non-Bella Vie Network direct selling programs, it is the responsibility of the Member to ensure that his or her Bella Vie Network business is operated entirely separate and apart from any other program.&nbsp; To this end, the following must be adhered to:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members must not sell, or attempt to sell, any competing non-Bella Vie Network programs, products or services to Bella Vie Network Customers or Members.&nbsp; Any program, product or services in the same generic categories as Bella Vie Network products or services is deemed to be competing, regardless of differences in cost, quality or other distinguishing factors.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not display Bella Vie Network promotional material, sales aids, products or services with or in the same location as, any non-Bella Vie Network promotional material or sales aids, products or services.&nbsp;<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not offer the Bella Vie Network opportunity, products or services to prospective or existing Customers or Members in conjunction with any non-Bella Vie Network program, opportunity, product or service.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members may not offer any non-Bella Vie Network opportunity, products, services or opportunity at any Bella Vie Network-related meeting, seminar, convention, webinar, teleconference, or other function.<br />\\n	&nbsp;<br />\\n	<strong>4.10.3 - </strong><strong>Confidential Information</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ldquo;Confidential Information&rdquo; includes, but is not limited to, Downline Genealogy Reports, the identities of Bella Vie Network customers and Members, contact information of Bella Vie Network customers and Members, Members&rsquo; personal sales volumes, and Member rank and/or achievement levels.&nbsp; Confidential Information is, or may be available, to Members in their respective back-offices.&nbsp; Member access to such Confidential Information is password protected, and is confidential and constitutes proprietary information and business trade secrets belonging to Bella Vie Network.&nbsp; Such Confidential Information is provided to Members in strictest confidence and is made available to Members for the sole purpose of assisting Members in working with their respective downline organizations in the development of their Bella Vie Network business.&nbsp; Members may not use the reports for any purpose other than for developing their Bella Vie Network business. Where a Member participates in other multi-level marketing ventures, he/she is not eligible to have access to Downline Genealogy Reports.&nbsp; Members should use the Confidential Information to assist, motivate, and train their downline Members. The Member and Bella Vie Network agree that, but for this agreement of confidentiality and nondisclosure, Bella Vie Network would not provide Confidential Information to the Member.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To protect the Confidential Information, Members shall not, on his or her own behalf, or on behalf of any other person, partnership, association, corporation or other entity:<br />\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Directly or indirectly disclose any Confidential Information to any third party;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Directly or indirectly disclose the password or other access code to his or her back-office;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Use any Confidential Information to compete with Bella Vie Network or for any purpose other than promoting his or her Bella Vie Network business;&nbsp;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Recruit or solicit any Member or Customer of Bella Vie Network listed on any report or in the Member&rsquo;s back-office, or in any manner attempt to influence or induce any Member or Preferred Customer of Bella Vie Network, to alter their business relationship with Bella Vie Network; or</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Use or disclose to any person, partnership, association, corporation, or other entity any Confidential Information.</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;<br />\\n	The obligation not to disclose Confidential Information shall survive cancellation or termination of the Agreement, and shall remain effective and binding irrespective of whether a Member&rsquo;s Agreement has been terminated, or whether the Member is or is not otherwise affiliated with the Company.<br />\\n	&nbsp;<br />\\n	<strong>4.11 - </strong><strong>Targeting Other Direct Sellers</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network does not condone Members specifically or consciously targeting the sales force of another direct sales company to sell Bella Vie Network products or to become Members for Bella Vie Network, nor does Bella Vie Network condone Members solicitation or enticement of members of the sales force of another direct sales company to violate the terms of their contract with such other company.&nbsp; Should Members engage in such activity, they bear the risk of being sued by the other direct sales company.&nbsp; If any lawsuit, arbitration or mediation is brought against a Member alleging that he or she engaged in inappropriate recruiting activity of its sales force or customers, Bella Vie Network will not pay any of the Member&rsquo;s defense costs or legal fees, nor will Bella Vie Network indemnify the Member for any judgment, award, or settlement.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.12 - </strong><strong>Errors or Questions</strong><br />\\n	If a Member has questions about or believes any errors have been made regarding commissions, bonuses, genealogy lists, or charges, the Member must notify Bella Vie Network online through his or her Back Office within 60 days of the date of the purported error or incident in question.&nbsp; Bella Vie Network will not be responsible for any errors, omissions or problems not reported to the Company within 60 days.<br />\\n	&nbsp;<br />\\n	<strong>4.13 - </strong><strong>Governmental Approval or Endorsement</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Neither federal nor state regulatory agencies or officials approve or endorse any direct selling or network marketing companies or programs.&nbsp; Therefore, Members shall not represent or imply that Bella Vie Network or its Compensation Plan have been &quot;approved,&quot; &quot;endorsed&quot; or otherwise sanctioned by any government agency.<br />\\n	&nbsp;<br />\\n	<strong>4.14 - </strong><strong>Holding Applications or Orders</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members must not manipulate enrollments of new applicants and purchases of products.<br />\\n	&nbsp;<br />\\n	<strong>4.15 - </strong><strong>Income Taxes</strong><br />\\n	Each Member is responsible for paying local, state, and federal taxes on any income generated as an Independent Member.&nbsp; Unfortunately, we cannot provide you with any personal tax advice.&nbsp; Please consult your own tax accountant, tax attorney, or other tax professional.&nbsp; If a Member&rsquo;s Bella Vie Network business is tax exempt, the Federal tax identification number must be provided to Bella Vie Network.&nbsp; Every year, Bella Vie Network will provide an <st1:stockticker w:st=\\"on\\">IRS</st1:stockticker> Form 1099 MISC (Non-employee Compensation) earnings statement to each U.S. resident who: 1) Had earnings of over $600 in the previous calendar year; or 2) Made purchases during the previous calendar year in excess of $5,000.<br />\\n	&nbsp;<br />\\n	<strong>4.16 - </strong><strong>Independent Contractor Status</strong><br />\\n	Members are independent contractors.&nbsp; The agreement between Bella Vie Network and its Members does not create an employer/employee relationship, agency, partnership, or joint venture between the Company and the Member. Members shall not be treated as an employee for his or her services or for Federal or State tax purposes.&nbsp; All Members are responsible for paying local, state, and federal taxes due from all compensation earned as a Member of the Company.&nbsp; The Member has no authority (expressed or implied), to bind the Company to any obligation.&nbsp; Each Member shall establish his or her own goals, hours, and methods of sale, so long as he or she complies with the terms of the Member Agreement, these Policies and Procedures, and applicable laws.<br />\\n	&nbsp;<br />\\n	<strong>4.17 - </strong><strong>International</strong> <strong>Marketing</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members are authorized to sell Bella Vie Network products and services, and enroll Customers or Members only in the countries in which Bella Vie Network is authorized to conduct business, as announced in official Company literature. &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.18 - </strong><strong>Adherence to Laws and Ordinances</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall comply with all federal, state, and local laws and regulations in the conduct of their businesses.&nbsp; Many cities and counties have laws regulating certain home-based businesses.&nbsp; In most cases these ordinances are not applicable to Members because of the nature of their business.&nbsp; However, Members must obey those laws that do apply to them.&nbsp; If a city or county official tells a Member that an ordinance applies to him or her, the Member shall be polite and cooperative, and immediately send a copy of the ordinance to the Compliance Department of Bella Vie Network.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>4.19 - </strong><strong>Separation of a Bella Vie Network Business</strong><br />\\n	A divorcing husband and wife parties may continue to operate the Bella Vie Network business jointly on a &ldquo;business-as-usual&rdquo; basis, whereupon all compensation paid by Bella Vie Network will be paid according to the status quo as it existed prior to the divorce filing or dissolution proceedings.&nbsp; This is the default procedure if the parties do not agree on the format set forth above.<br />\\n	&nbsp;<br />\\n	Under no circumstances will the Downline Organization of divorcing spouses or a dissolving business entity be divided.&nbsp; Similarly, under no circumstances will Bella Vie Network split commission and bonus checks between divorcing spouses or members of dissolving entities.&nbsp; Bella Vie Network will recognize only one Downline Organization and will issue only one commission check per Bella Vie Network business per commission cycle.&nbsp; Commission checks shall always be issued to the same individual or entity.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a former spouse has completely relinquished all rights in the original Bella Vie Network business pursuant to a divorce, he or she is thereafter free to enroll under any sponsor of his or her choosing without waiting six calendar months.&nbsp; In the case of business entity dissolutions, the former partner, shareholder, member, or other entity affiliate who retains no interest in the business must wait six calendar months from the date of the final dissolution before re-enrolling as a Member.&nbsp; In either case, the former spouse or business affiliate shall have no rights to any Members in their former organization or to any former retail customer.&nbsp; They must develop the new business in the same manner as would any other new Member.<br />\\n	&nbsp;<br />\\n	<strong>4.20 - </strong><strong>Sponsoring Online</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; When sponsoring a new Member through the online enrollment process, the sponsor may assist the new applicant in filling out the enrollment materials.&nbsp; However, the applicant must personally review and agree to the online application and agreement, Bella Vie Network&rsquo;s Policies and Procedures, and the Bella Vie Network Compensation Plan.&nbsp; The sponsor may not fill out the online application and agreement on behalf of the applicant and agree to these materials on behalf of the applicant.<br />\\n	&nbsp;<br />\\n	<strong>4.21 - </strong><strong>Telemarketing Techniques</strong> &nbsp;<br />\\n	The Federal Trade Commission and the Federal Communications Commission each have laws that restrict telemarketing practices.&nbsp; Both federal agencies (as well as a number of states) have &ldquo;do not call&rdquo; regulations as part of their telemarketing laws.&nbsp; Although Bella Vie Network does not consider Members to be &ldquo;telemarketers&rdquo; in the traditional sense of the word, these government regulations broadly define the term &ldquo;telemarketer&rdquo; and &ldquo;telemarketing&rdquo; so that your inadvertent action of calling someone whose telephone number is listed on the federal &ldquo;do not call&rdquo; registry could cause you to violate the law.&nbsp; Moreover, these regulations must not be taken lightly, as they carry significant penalties.&nbsp;<br />\\n	&nbsp;<br />\\n	Therefore, Members must not engage in telemarketing in the operation of their Bella Vie Network businesses.&nbsp; The term &ldquo;telemarketing&rdquo; means the placing of one or more telephone calls to an individual or entity to induce the purchase of a Bella Vie Network product or service, or to recruit them for the Bella Vie Network opportunity.&nbsp; &ldquo;Cold calls&quot; made to prospective customers or Members that promote either Bella Vie Network&rsquo;s products or services or the Bella Vie Network opportunity constitute telemarketing and are prohibited.&nbsp; However, a telephone call(s) placed to a prospective customer or Member (a &quot;prospect&quot;) is permissible under the following situations:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			If the Member has an established business relationship with the prospect.&nbsp; An &ldquo;established business relationship&rdquo; is a relationship between a Member and a prospect&nbsp;based on the prospect&rsquo;s purchase, rental, or lease of goods or services from the Member, or a financial transaction between the prospect and the Member, within the eighteen (18) months immediately preceding the date of a telephone call to induce the prospect&#39;s purchase of a product or service.&nbsp;&nbsp;&nbsp; &nbsp;</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The prospect&rsquo;s personal inquiry or application regarding a product or service offered by the Member, within the three (3) months immediately preceding the date of such a call. &nbsp;</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			If the Member receives written and signed permission from the prospect authorizing the Member to call.&nbsp; The authorization must specify the telephone number(s) which the Member is authorized to call.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			You may call family members, personal friends, and acquaintances.&nbsp; An &ldquo;acquaintance&rdquo; is someone with whom you have at least a recent first-hand relationship within the preceding three months.&nbsp; Bear in mind, however, that if you engage in &ldquo;card collecting&rdquo; with everyone you meet and subsequently calling them, the FTC may consider this a form of telemarketing that is not subject to this exemption.&nbsp;&nbsp; Thus, if you engage in calling &ldquo;acquaintances,&rdquo; you must make such calls on an occasional basis only and not make this a routine practice.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Members shall not use automatic telephone dialing systems or software relative to the operation of their Bella Vie Network businesses.</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			Members shall not place or initiate any outbound telephone call to any person that delivers any pre-recorded message (a &quot;robocall&quot;) regarding or relating to the Bella Vie Network products, services or opportunity.</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;<br />\\n	<strong>4.22 - </strong><strong>Back Office Access</strong><br />\\n	Bella Vie Network makes online back offices available to its Members.&nbsp; Back offices provide Members access to confidential and proprietary information that may be used solely and exclusively to promote the development of a Member&rsquo;s Bella Vie Network business and to increase sales of Bella Vie Network products.&nbsp; However, access to a back office is a privilege, and not a right.&nbsp; Bella Vie Network reserves the right to deny Members&rsquo; access to the back office at its sole discretion.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 5 - </strong><strong>RESPONSIBILITIES OF MEMBERS</strong><br />\\n	&nbsp;<br />\\n	<strong>5.1 - </strong><strong>Change of Address, Telephone, and E-Mail Addresses</strong><br />\\n	To ensure timely delivery of products, support materials, commission, and tax documents, it is important that the Bella Vie Network&rsquo;s files are current.&nbsp; Street addresses are required for shipping since <st1:stockticker w:st=\\"on\\">UPS</st1:stockticker> cannot deliver to a post office box.&nbsp; Members planning to change their e-mail address or move must provide their new address and telephone numbers to Bella Vie Network via their Back Office.&nbsp; To guarantee proper delivery, two weeks advance notice must be provided to Bella Vie Network on all changes.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.2 - </strong><strong>Continuing Development Obligations</strong><br />\\n	<strong>5.2.1 - </strong><strong>Ongoing Training</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any Member who sponsors another Member into Bella Vie Network must perform a bona fide assistance and training function to ensure that his or her downline is properly operating his or her Bella Vie Network business.&nbsp; Members must have ongoing contact and communication with the Members in their Downline Organizations.&nbsp; Examples of such contact and communication may include, but are not limited to:&nbsp; newsletters, written correspondence, personal meetings, telephone contact, voice mail, electronic mail, and the accompaniment of downline Members to Bella Vie Network meetings, training sessions, and other functions.&nbsp; Upline Members are also responsible to motivate and train new Members in Bella Vie Network product knowledge, effective sales techniques, the Bella Vie Network Compensation Plan, and compliance with Company Policies and Procedures and applicable laws.&nbsp; Communication with and the training of downline Members must not, however, violate Sections 4.1 and/or 4.2 (regarding the development of Member-produced sales aids and promotional materials).<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members should monitor the Members in their Downline Organizations to guard against downline Members making improper product or business claims, violation of the Policies and Procedures, or engaging in any illegal or inappropriate conduct.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.2.2 - </strong><strong>Increased Training Responsibilities</strong><br />\\n	As Members progress through the various levels of leadership, they will become more experienced in sales techniques, product knowledge, and understanding of the Bella Vie Network program.&nbsp; They will be called upon to share this knowledge with lesser experienced Members within their organization.<br />\\n	&nbsp;<br />\\n	<strong>5.2.3 - </strong><strong>Ongoing Sales Responsibilities</strong><br />\\n	Regardless of their level of achievement, Members have an ongoing obligation to continue to personally promote sales through the generation of new customers and through servicing their existing customers.<br />\\n	&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.3 - </strong><strong>Nondisparagement</strong><br />\\n	Bella Vie Network wants to provide its independent Members with the best products, compensation plan, and service in the industry.&nbsp; Accordingly, we value your constructive criticisms and comments.&nbsp; All such comments should be submitted in writing to the Support Department.&nbsp; Remember, to best serve you, we must hear from you!&nbsp; While Bella Vie Network welcomes constructive input, negative comments and remarks made in the field by Members about the Company, its products, or compensation plan serve no purpose other than to sour the enthusiasm of other Bella Vie Network Members.&nbsp; For this reason, and to set the proper example for their downline, Members must not disparage, demean, or make negative remarks about Bella Vie Network, other Bella Vie Network Members, Bella Vie Network&rsquo;s products, the Marketing and Compensation plan, or Bella Vie Network&rsquo;s directors, officers, or employees.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>5.4 - </strong><strong>Providing Documentation to Applicants</strong><br />\\n	Members must provide the most current version of the Policies and Procedures and the Compensation Plan to individuals whom they are sponsoring to become Members before the applicant signs a Member Agreement, or ensure that they have online access to these materials.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 6 - </strong><strong>SALES REQUIREMENTS</strong><br />\\n	&nbsp;<br />\\n	<strong>6.1 - </strong><strong>Product Sales</strong><br />\\n	The Bella Vie Network Compensation Plan is based on the sale of Bella Vie Network products and services to end consumers.&nbsp; Members must fulfill personal and Downline Organization retail sales requirements (as well as meet other responsibilities set forth in the Agreement) to be eligible for bonuses, commissions and advancement to higher levels of achievement.&nbsp; The following sales requirements must be satisfied for Members to be eligible for commissions:<br />\\n	&nbsp;<br />\\n	Members must satisfy the Personal Volume requirements to fulfill the requirements associated with their rank as specified in the Bella Vie Network Compensation Plan.&nbsp; &ldquo;Personal Sales Volume&rdquo; includes purchases made by the Member and purchases made by the Member&rsquo;s personal customers. &nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Members must generate their monthly minimum orders in order to be qualified for commission of their downline).<br />\\n	&nbsp;<br />\\n	Members must develop or maintain at least five Customers who must multiply by 5 for each member. Please see the Compensation Chart.<br />\\n	&nbsp;<br />\\n	<strong>6.2 - </strong><strong>No Territory Restrictions</strong><br />\\n	There are no exclusive territories granted to anyone.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>6.3 - </strong><strong>Sales Receipts</strong><br />\\n	Retail customers must receive two copies of an official Bella Vie Network sales receipt at the time of the sale.&nbsp; These receipts set forth the Customer Satisfaction Guarantee as well as any consumer protection rights afforded by federal or state law.&nbsp; Upon completion of the order process, the sales receipt will be available for printing.&nbsp; In addition, Members must orally inform the buyer of his or her cancellation rights.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 7 - </strong><strong>BONUSES </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> COMMISSIONS</strong><br />\\n	&nbsp;<br />\\n	<strong>7.1 - </strong><strong>Bonus and Commission Qualifications and Accrual</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member must be active and in compliance with the Agreement to qualify for bonuses and commissions.&nbsp; So long as a Member complies with the terms of the Agreement, Bella Vie Network shall pay commissions to such Member in accordance with the Marketing and Compensation plan.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member can see all of his or her pending and actual commissions in the &quot;MyWallet&quot; section on the Back Office. &nbsp;A Member may apply for a Paylution Debit Card. Company will deduct from Member the initial fee of the issuance of their Debit Bank Card.&nbsp; In order to transfer funds the Member&rsquo;s &quot;MyWallet&quot; account to his or her Paylution Debit Card, Company will process transfers once a month after the 20th of each month, as long as the Member requests the transfer through his or her Back Office by the 20th of each month.&nbsp; There will be a processing fee from BankCorp for every transfer transaction from MyWallet to the Paylution Debit Card.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, all commissions owed a Member, regardless of the amount accrued, will be paid at the end of each fiscal year or upon the termination of a Member&rsquo;s business.<br />\\n	&nbsp;<br />\\n	<strong>7.2 - </strong><strong>Adjustment to Bonuses and Commissions</strong><br />\\n	&nbsp;<br />\\n	<strong>7.2.1 - </strong><strong>Adjustments for Returned Products </strong><strong>and Cancelled Services</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members receive bonuses, commissions, or overrides based on the actual sales of products and services to end consumers.&nbsp; When a service is cancelled or a product is returned to Bella Vie Network for a refund or is repurchased by the Company, any of the following may occur at the Company&rsquo;s discretion: (1) the bonuses, commissions, or overrides attributable to the returned or repurchased product(s) or cancelled service will be deducted from payments to the Member and upline Members who received bonuses, commissions, or overrides on the sales of the refunded product(s) or cancelled service, in the month in which the refund is given, and continuing every pay period thereafter until the commission is recovered; (2) the Member or upline Members who earned bonuses, commissions, or overrides based on the sale of the returned product(s) or cancelled service will have the corresponding points deducted from earnings in the next month and all subsequent months until it is completely recovered; or (3) the bonuses, commissions, or overrides attributable to the returned or repurchased product(s) or cancelled service may be deducted from any refunds or credits to the Member who received the bonuses, commissions, or overrides on the sales of the refunded product(s) or cancelled service.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>7.2.2 - </strong><strong>Commissions </strong><br />\\n	The Company pays commissions via HyperWallet system. Bella Vie Network will deduct the fee for the 1st Paylution Debit Card. &nbsp;A Member must to go to his or her Profile/Back Office to place the order for the Paylution debit card so commissions can be &nbsp;transferred on to that card upon Member&#39;s discretion. Member is responsible for any transaction fee thereafter. &nbsp;There may be a processing fee charged by Hyperwallet for transactions into and out of the account &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>7.2.3 - </strong><strong>Tax Withholdings</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member fails to submit a W-9 form, Bella Vie Network will deduct the necessary withholdings from the Member&rsquo;s commission checks as required by law.<br />\\n	&nbsp;<br />\\n	<strong>7.3 - </strong><strong>Reports</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All information provided by Bella Vie Network in downline activity reports, including but not limited to personal sales volume (or any part thereof), and downline sponsoring activity is believed to be accurate and reliable.&nbsp; Nevertheless, due to various factors including but not limited to the inherent possibility of human, digital, and mechanical error; the accuracy, completeness, and timeliness of orders; denial of credit card and electronic check payments; returned products; credit card and electronic check charge-backs; the information is not guaranteed by Bella Vie Network or any persons creating or transmitting the information.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <st1:stockticker w:st=\\"on\\">ALL</st1:stockticker> PERSONAL SALES VOLUME INFORMATION IS PROVIDED &quot;AS IS&quot; WITHOUT WARRANTIES, EXPRESS OR IMPLIED, OR REPRESENTATIONS OF ANY KIND WHATSOEVER.&nbsp; IN PARTICULAR BUT WITHOUT LIMITATION THERE SHALL BE NO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR USE, OR NON‑INFRINGEMENT.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TO THE FULLEST EXTENT PERMISSIBLE UNDER APPLICABLE LAW, BELLA VIE NETWORK AND/OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION WILL IN NO EVENT BE LIABLE TO ANY MEMBER OR ANYONE ELSE FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES THAT ARISE OUT OF THE USE OF OR ACCESS TO PERSONAL SALES VOLUME INFORMATION (INCLUDING BUT NOT LIMITED TO LOST PROFITS, BONUSES, OR COMMISSIONS, LOSS OF OPPORTUNITY, AND DAMAGES THAT MAY RESULT FROM INACCURACY, INCOMPLETENESS, INCONVENIENCE, DELAY, OR LOSS OF THE USE OF THE INFORMATION), EVEN IF BELLA VIE NETWORK OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION SHALL HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.&nbsp; TO THE FULLEST EXTENT PERMITTED BY LAW, BELLA VIE NETWORK OR OTHER PERSONS CREATING OR TRANSMITTING THE INFORMATION SHALL HAVE NO RESPONSIBILITY OR LIABILITY TO YOU OR ANYONE ELSE UNDER ANY TORT, CONTRACT, NEGLIGENCE, STRICT LIABILITY, PRODUCTS LIABILITY OR OTHER THEORY WITH RESPECT TO ANY SUBJECT MATTER OF THIS AGREEMENT OR TERMS AND CONDITIONS RELATED THERETO.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Access to and use of Bella Vie Network&rsquo; online and telephone reporting services and your reliance upon such information is at your own risk.&nbsp; All such information is provided to you &quot;as is&quot;.&nbsp; If you are dissatisfied with the accuracy or quality of the information, your sole and exclusive remedy is to discontinue use of and access to Bella Vie Network&rsquo; online and telephone reporting services and your reliance upon the information.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 8 - </strong><strong>&nbsp;PRODUCT GUARANTEES, RETURNS, <st1:stockticker w:st=\\"on\\">AND</st1:stockticker> INVENTORY REPURCHASE</strong><br />\\n	&nbsp;<br />\\n	<strong>8.1 - </strong><strong>Rescission</strong><br />\\n	Federal and state law requires that Members notify their retail customers that they have three business days (5 business days for Alaska residents.&nbsp; Saturday is a business day, Sundays and legal holidays are not business days) within which to cancel their purchase and receive a full refund upon return of the products in substantially as good condition as when they were delivered.&nbsp; Members <strong><em>MUST</em></strong> verbally inform their customers of this right and <strong><em>MUST</em></strong> point out this cancellation right stated on the receipt.&nbsp;<br />\\n	<strong>8.2 - </strong><strong>Returns by Retail Customers</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A retail customer who makes a purchase of $25.00 or more has three business days (72 hours, excluding Sundays and legal holidays) after the sale or execution of a contract to cancel the order and receive a full refund consistent with the cancellation notice on the order form or sales receipt (5 days for Alaska residents).&nbsp; When a Member makes a sale or takes an order from a retail customer who cancels or requests a refund within the 72 hour period, the Member must promptly refund the customer&#39;s money as long as the products are returned to the Member in substantially as good condition as when received (5 days for Alaska residents).&nbsp; Members must orally inform customers of their right to rescind a purchase or an order within 72 hours (5 days for Alaska residents), and ensure that the date of the order or purchase is entered on the order form.&nbsp; All retail customers must be provided with two copies of an official Bella Vie Network sales receipt at the time of the sale.&nbsp; The back of the receipt provides the customer with written notice of his or her rights to cancel the sales agreement.<br />\\n	&nbsp;<br />\\n	<strong>8.3 - </strong><strong>Montana Residents </strong><br />\\n	&nbsp;&nbsp;<br />\\n	A Montana resident may cancel his or her Member Agreement within 15 days from the date of enrollment.<br />\\n	&nbsp;<br />\\n	<strong>8.4 - </strong><strong>Procedures for All Returns</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The following procedures apply to all returns for exchange:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All merchandise must be returned by the Member or customer who purchased it directly from Bella Vie Network to the Product&#39;s Supplier.&nbsp; No products may be returned to Bella Vie Network.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All products to be returned must have a Return Authorization Number Product&rsquo;s Supplier which is obtained by contacting the Supplier.&nbsp; This Return Authorization Number must be written on each carton returned.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The return is accompanied by:<br />\\n	&nbsp;<br />\\n	o&nbsp;&nbsp; The original packing slip with the completed and signed Consumer Return information;<br />\\n	o&nbsp;&nbsp; the unused portion of the product in its original container.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Proper shipping carton(s) and packing materials are to be used in packaging the product(s) being returned for replacement, and the best and most economical means of shipping is suggested.&nbsp; All returns must be shipped to the Supplier shipping pre-paid.&nbsp; A Supplier will not accept shipping-collect packages.&nbsp; The risk of loss in shipping for returned product shall be on the Member.&nbsp; If returned product is not received by the Supplier, it is the responsibility of the Member to trace the shipment.<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is returning merchandise to a Supplier, the product(s) must be received by the Supplier within ten (10) days from the date of order.<br />\\n	&nbsp;<br />\\n	No refund or replacement of product will be made if the conditions of these rules are not met.<br />\\n	<br />\\n	<strong>SECTION 9 - </strong><strong>DISPUTE RESOLUTION </strong><st1:stockticker w:st=\\"on\\"><strong>AND</strong></st1:stockticker><strong> DISCIPLINARY PROCEEDINGS</strong><br />\\n	&nbsp;<br />\\n	<strong>9.1 - </strong><strong>Disciplinary Sanctions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Violation of the Agreement, these Policies and Procedures, violation of any common law duty, including but not limited to any applicable duty of loyalty, any illegal, fraudulent, deceptive or unethical business conduct, or any act or omission by a Member that, in the sole discretion of the Company may damage its reputation or goodwill (such damaging act or omission need not be related to the Member&rsquo;s Bella Vie Network business), may result, at Bella Vie Network&#39;s discretion, in one or more of the following corrective measures:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issuance of a written warning or admonition;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Requiring the Member to take immediate corrective measures;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Imposition of a fine, which may be withheld from bonus and commission checks;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Loss of rights to one or more bonus and commission checks;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network may withhold from a Member all or part of the Member&rsquo;s bonuses and commissions during the period that Bella Vie Network is investigating any conduct allegedly violative of the Agreement.&nbsp; If a Member&rsquo;s business is canceled for disciplinary reasons, the Member will not be entitled to recover any commissions withheld during the investigation period;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suspension of the individual&rsquo;s Member Agreement for one or more pay periods;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Permanent or temporary loss of, or reduction in, the current and/or lifetime rank of a Member (which may subsequently be re-earned by the Member);<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transfer or removal of some or all of a Member&rsquo;s downline Members from the offending Member&rsquo;s downline organization.<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Involuntary termination of the offender&rsquo;s Member Agreement;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suspension and/or termination of the offending Member&rsquo;s Bella Vie Network website or website access;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Any other measure expressly allowed within any provision of the Agreement or which Bella Vie Network deems practicable to implement and appropriate to equitably resolve injuries caused partially or exclusively by the Member&rsquo;s policy violation or contractual breach;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In situations deemed appropriate by Bella Vie Network, the Company may institute legal proceedings for monetary and/or equitable relief.<br />\\n	&nbsp;<br />\\n	<strong>9.2 - </strong><strong>Grievances and Complaints</strong><br />\\n	When a Member has a grievance or complaint with another Member regarding any practice or conduct in relationship to their respective Bella Vie Network businesses, the complaining Member should first report the problem to his or her Sponsor who should review the matter and try to resolve it with the other party&#39;s upline sponsor.&nbsp; If the matter involves interpretation or violation of Company policy, it must be reported in writing to the Member Services Department at the Company.&nbsp; The Member Services Department will review the facts and attempt to resolve it.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.3 - </strong><strong>&nbsp;Mediation</strong><br />\\n	Prior to instituting an arbitration, the parties shall meet in good faith and attempt to resolve any dispute arising from or relating to the Agreement through non-binding mediation.&nbsp; One individual who is mutually acceptable to the parties shall be appointed as mediator. &nbsp;The mediation shall occur within 60 days from the date on which the mediator is appointed. &nbsp;The mediator&rsquo;s fees and costs, as well as the costs of holding and conducting the mediation, shall be divided equally between the parties.&nbsp; Each party shall pay its portion of the anticipated shared fees and costs at least 10 days in advance of the mediation.&nbsp; Each party shall pay its own attorneys fees, costs, and individual expenses associated with conducting and attending the mediation.&nbsp; Mediation shall be held in the City of Las Vegas, Nevada, and shall last no more than two business days.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.4 - </strong><strong>Arbitration</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If mediation is unsuccessful, <strong>any controversy or claim arising out of or relating to the Agreement, or the breach thereof, shall be settled by arbitration.&nbsp; The Parties waive all rights to trial by jury or to any court</strong>.&nbsp; The arbitration shall be filed with, and administered by, the American Arbitration Association (&ldquo;AAA&rdquo;) or <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker> Endispute (&ldquo;<st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker>&rdquo;) under their respective rules and procedures.&nbsp; The <em>Commercial Arbitration Rules and Mediation Procedures</em> of the AAA are available on the AAA&rsquo;s website at <a href=\\"http://www.adr.org/\\">www.adr.org</a>.&nbsp; The<strong> <strong><em>Streamlined Arbitration Rules&nbsp;&amp; Procedures</em></strong> </strong>are available on the <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker> website at <a href=\\"http://www.jamsadr.com/\\">www.jamsadr.com</a>.&nbsp; Copies of AAA&rsquo;s <em>Commercial Arbitration Rules and Mediation Procedures</em> or JAM&rsquo;s <strong><em>Streamlined Arbitration Rules&nbsp;&amp; Procedures</em></strong> will also be emailed to Members upon request to Bella Vie Network&rsquo;s Support Department. &nbsp;&nbsp;<br />\\n	Notwithstanding the rules of the AAA or <st1:stockticker w:st=\\"on\\">JAMS</st1:stockticker>, the following shall apply to all Arbitration actions:</div>\\n<ul>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The Federal Rules of Evidence shall apply in all cases;&nbsp;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The Parties shall be entitled to all discovery rights permitted by the Federal Rules of Civil Procedure;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The Parties shall be entitled to bring motions under Rules 12 and/or 56 of the Federal Rules of Civil Procedure;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The arbitration shall occur within 180 days from the date on which the arbitrator is appointed, and shall last no more than five business days;</div>\\n	</li>\\n	<li>\\n		<div style=\\"margin-left: 80px;\\">\\n			The Parties shall be allotted equal time to present their respective cases, including cross-examinations.&nbsp;</div>\\n	</li>\\n</ul>\\n<div style=\\"margin-left: 80px;\\">\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; All arbitration proceedings shall be held in Las Vegas, Nevada.&nbsp; There shall be one arbitrator selected from the panel that the Alternate Dispute Resolution service provides. Each party to the arbitration shall be responsible for its own costs and expenses of arbitration, including legal and filing fees.&nbsp; The arbitration shall occur within 180 days from the date on which the arbitration is filed, and shall last no more than five business days.&nbsp; The parties shall be allotted equal time to present their respective cases.&nbsp; The decision of the arbitrator shall be final and binding on the parties and may if necessary, be reduced to a judgment in any court of competent jurisdiction.&nbsp; This agreement to arbitrate shall survive the cancellation or termination of the Agreement.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The parties and the arbitrator shall maintain the confidentiality of the entire arbitration process and shall not disclose to any person not directly involved in the arbitration process:<br />\\n	&nbsp;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The substance of, or basis for, the controversy, dispute, or claim;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The content of any testimony or other evidence presented at an arbitration hearing or obtained through discovery in arbitration;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The terms or amount of any arbitration award;<br />\\n	&middot;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The rulings of the arbitrator on the procedural and/or substantive issues involved in the case.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, nothing in these Policies and Procedures shall prevent either party from applying to and obtaining from any court having jurisdiction a writ of attachment, a temporary injunction, preliminary injunction, permanent injunction or other relief available to safeguard and protect its intellectual property rights, and/or to enforce its rights under the nonsolicitation provision of the Agreement.&nbsp;&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.5 - </strong><strong>&nbsp;Governing Law, Jurisdiction and Venue</strong><br />\\n	Jurisdiction and venue of any matter not subject to arbitration shall reside exclusively in Clark County, Nevada.&nbsp; The Federal Arbitration Act shall govern all matters relating to arbitration.&nbsp; The law of the State of Nevada shall govern all other matters relating to or arising from the Agreement.&nbsp; &nbsp;<br />\\n	&nbsp;<br />\\n	<strong>9.5.1 - </strong><strong>Louisiana</strong><strong> Residents</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notwithstanding the foregoing, and the arbitration provision in Section 9.4, residents of the State of Louisiana shall be entitled to bring an action against Bella Vie Network in their home forum and pursuant to Louisiana law.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>SECTION 10 - </strong><strong>PAYMENTS</strong><br />\\n	&nbsp;<br />\\n	<strong>10.1 - </strong><strong>Restrictions on Third Party Use of Credit Cards and Checking Account Access</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members shall not permit other Members or Customers to use his or her credit card, or permit debits to their checking accounts, to enroll or to make purchases from the Company<strong>.</strong><br />\\n	&nbsp;<br />\\n	<strong>10.2 - </strong><strong>Sales Taxes</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network is required to charge sales taxes on all purchases made by Members and Customers, and remit the taxes charged to the respective states.&nbsp; Accordingly, Bella Vie Network will collect and remit sales taxes on behalf of Members, based on the suggested retail price of the products, according to applicable tax rates in the state or province to which the shipment is destined.&nbsp; If a Member has submitted, and Bella Vie Network has accepted, a current Sales Tax Exemption Certificate and Sales Tax Registration License, sales taxes will not be added to the invoice and the responsibility of collecting and remitting sales taxes to the appropriate authorities shall be on the Member.&nbsp; Exemption from the payment of sales tax is applicable only to orders which are shipped to a state for which the proper tax exemption papers have been filed and accepted.&nbsp; Applicable sales taxes will be charged on orders that are drop-shipped to another state.&nbsp; Any sales tax exemption accepted by Bella Vie Network is not retroactive.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 11 - </strong><strong>INACTIVITY AND CANCELLATION</strong><br />\\n	&nbsp;<br />\\n	<strong>11.1 - </strong><strong>Effect of Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; So long as a Member remains active and complies with the terms of the Member Agreement and these Policies and Procedures, Bella Vie Network shall pay commissions to such Member in accordance with the Compensation Plan.&nbsp; A Member&rsquo;s bonuses and commissions constitute the entire consideration for the Member&#39;s efforts in generating sales and all activities related to generating sales (including building a downline organization).&nbsp; Following a Member&rsquo;s non-renewal of his or her Member Agreement, cancellation for inactivity, or voluntary or involuntary cancellation of his or her Member Agreement (all of these methods are collectively referred to as &ldquo;cancellation&rdquo;), the former Member shall have no right, title, claim or interest to the marketing organization which he or she operated, or any commission or bonus from the sales generated by the organization.&nbsp; <strong>A Member whose business is cancelled will lose all rights as a Member.&nbsp; This includes the right to sell Bella Vie Network products and services and the right to receive future commissions, bonuses, or other income resulting from the sales and other activities of the Member&rsquo;s former downline sales organization.&nbsp; In the event of cancellation, Members agree to waive all rights they may have, including but not limited to property rights, to their former downline organization and to any bonuses, commissions or other remuneration derived from the sales and other activities of his or her former downline organization.</strong><br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Following a Member&rsquo;s cancellation of his or her Member Agreement, the former Member shall not hold himself or herself out as a Bella Vie Network Member and shall not have the right to sell Bella Vie Network products or services.&nbsp; A Member whose business is canceled shall receive commissions and bonuses only for the last full pay period he or she was active prior to cancellation (less any amounts withheld during an investigation preceding an involuntary termination).<br />\\n	&nbsp;<br />\\n	<strong>11.2 - </strong><strong>Cancellation Due to Inactivity</strong><br />\\n	&nbsp;<br />\\n	<strong>11.2.1 - </strong><strong>Failure to Meet PV Quota</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member fails to personally generate at one order per month for 3 consecutive months, his or her Member Agreement shall be canceled for inactivity.&nbsp;&nbsp;<br />\\n	<br />\\n	<strong>11.2.2 - </strong><strong>Failure to Earn Commissions</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member has not earned a commission for three consecutive months (and thus become &ldquo;inactive&rdquo;), his or her Member Agreement shall be canceled for inactivity.&nbsp;<br />\\n	&nbsp;<br />\\n	<strong>11.2.3 - </strong><strong>Continuation of Autoship</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If a Member is cancelled for inactivity, his or her Member Agreement will be cancelled for inactivity.&nbsp; If he or she is on the Company&rsquo;s autoship program, the autoship agreement shall remain in force.<br />\\n	&nbsp;<br />\\n	<strong>11.3 - </strong><strong>Involuntary Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A Member&rsquo;s violation of any of the terms of the Agreement, including any amendments that may be made by Bella Vie Network in its sole discretion, may result in any of the sanctions listed in Section 9.1, including the involuntary cancellation of his or her Member Agreement.&nbsp; Cancellation shall be effective on the date on which written notice is mailed, emailed, faxed, or delivered to an express courier, to the Member&rsquo;s last known address, email address, or fax number, or to his/her attorney, or when the Member receives actual notice of cancellation, whichever occurs first.<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bella Vie Network reserves the right to terminate all Member Agreements upon thirty (30) days written notice in the event that it elects to: (1) cease business operations; (2) dissolve as a corporate entity; or (3) terminate distribution of its products via direct selling.<br />\\n	&nbsp;<br />\\n	<strong>11.4 - </strong><strong>Voluntary Cancellation</strong><br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A participant in this network marketing plan has a right to cancel at any time, regardless of reason.&nbsp; Cancellation must be submitted in writing to the Company at its principal business address. The written notice must include the Member&rsquo;s signature, printed name, address, and Member I.D. Number.&nbsp;<br />\\n	&nbsp;<br />\\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In addition to written cancellation, Members who have consented to Electronic Contracting will cancel their Member Agreement should they withdraw their consent to contract electronically.&nbsp; If a Member is also on the Autoship program, the Member&rsquo;s Autoship order shall continue unless the Member also specifically requests that his or her Autoship Agreement also be canceled.<br />\\n	&nbsp;<br />\\n	<strong>SECTION 12 - </strong><strong>DEFINITIONS</strong><br />\\n	&nbsp;<br />\\n	Active Customer &mdash; A Customer who purchases Bella Vie Network products and whose account has been paid for the ensuing year.<br />\\n	&nbsp;<br />\\n	Active Member &mdash; A Member who satisfies the minimum Personal Sales Volume requirements, as set forth in the Bella Vie Network Compensation Plan, to ensure that he or she is eligible to receive bonuses and commissions.<br />\\n	&nbsp;<br />\\n	Affiliated Party - A shareholder, member, partner, manager, trustee, or other parties with any ownership interest in, or management responsibilities for, a Business Entity.<br />\\n	&nbsp;<br />\\n	Agreement - The contract between the Company and each Member includes the Member Application and Agreement, the Bella Vie Network Policies and Procedures, the Bella Vie Network Compensation Plan, and the Business Entity Form (where appropriate), all in their current form and as amended by Bella Vie Network in its sole discretion.&nbsp; These documents are collectively referred to as the &ldquo;Agreement.&rdquo;<br />\\n	&nbsp;<br />\\n	Cancel &mdash; The termination of a Member&rsquo;s business.&nbsp; Cancellation may be either voluntary, involuntary, through non-renewal or inactivity.<br />\\n	&nbsp;<br />\\n	Downline &mdash; See &ldquo;Marketing Organization&rdquo; below.&nbsp;<br />\\n	&nbsp;<br />\\n	Downline Leg &mdash; Each one of the individuals enrolled immediately underneath you and their respective marketing organizations represents one &ldquo;leg&rdquo; in your marketing organization.<br />\\n	&nbsp;<br />\\n	Household - Spouses, heads-of-household, and dependent family members residing in the same residence.<br />\\n	&nbsp;<br />\\n	Immediate Household &mdash; Spouses, heads-of-household, and dependent family members residing in the same residence.<br />\\n	&nbsp;<br />\\n	Level &mdash; The layers of downline Customers and Members in a particular Member&rsquo;s Marketing Organization.&nbsp; This term refers to the relationship of a Member relative to a particular upline Member, determined by the number of Members between them who are related by sponsorship.&nbsp; For example, if A sponsors B, who sponsors C, who sponsors D, who sponsors E, then E is on A&rsquo;s fourth level.<br />\\n	&nbsp;<br />\\n	Marketing Organization &mdash; The Customers and Members sponsored up to seven levels and five generations below a particular Member, depending on the Member&rsquo;s rank qualification.&nbsp; The word &ldquo;downline&rdquo; is used interchangeably with Marketing Organization.<br />\\n	&nbsp;<br />\\n	Official Bella Vie Network Material &mdash; Literature, audio or video tapes, websites, and other materials developed, printed, published and/or distributed by Bella Vie Network to Members.<br />\\n	&nbsp;<br />\\n	Personal Production &mdash; Moving Bella Vie Network products or services to an end consumer for actual use.<br />\\n	&nbsp;<br />\\n	Personal Volume&mdash; The commissionable value of services and products purchased by: (1) a Member; and (2) the Member&rsquo;s downline who are on the autoship program or who purchase from the Member&rsquo;s Bella Vie Network.<br />\\n	&nbsp;<br />\\n	Rank &mdash; The &ldquo;title&rdquo; that a Member holds pursuant to the Bella Vie Network Compensation Plan.&nbsp; &ldquo;Title Rank&rdquo; refers to the highest rank a Member has achieved in the Bella Vie Network compensation plan at any time.&nbsp; &ldquo;Paid As&rdquo; rank refers to the rank at which a Member is qualified to earn commissions and bonuses during the current pay period.<br />\\n	&nbsp;<br />\\n	Recruit&nbsp; &mdash; For purposes of Bella Vie Network&rsquo;s Conflict of Interest Policy (Section 4.10), the term &ldquo;Recruit&rdquo; means the actual or attempted sponsorship, solicitation, enrollment, encouragement, or effort to influence in any other way, either directly, indirectly, or through a third party, another Bella Vie Network Member or Customer to enroll or participate in another multilevel marketing, network marketing or direct sales opportunity.<br />\\n	&nbsp;<br />\\n	Resalable &mdash; Products and Sales aids shall be deemed &quot;resalable&quot; if each of the following elements is satisfied: 1) they are unopened and unused; 2) packaging and labeling has not been altered or damaged; 3) they are in a condition such that it is a commercially reasonable practice within the trade to sell the merchandise at full price; 4) it is returned to Bella Vie Network within one month from the date of purchase.&nbsp; Any merchandise that is clearly identified at the time of sale as nonreturnable, discontinued, or as a seasonal item, shall not be resalable.<br />\\n	&nbsp;<br />\\n	Retail Customer &mdash; An individual who purchases Bella Vie Network products from a Member but who is not a participant in the Bella Vie Network compensation plan.<br />\\n	&nbsp;<br />\\n	Retail Customer &ndash; An individual who or entity that purchases Bella Vie Network products or services from a Member, but who is not a Member, or an immediate household family member of a Member.&nbsp;<br />\\n	&nbsp;<br />\\n	Retail Sales &ndash; Sales to a Retail Customer.&nbsp;<br />\\n	&nbsp;<br />\\n	Social Media - Any type of online media that invites, expedites or permits conversation, comment, rating, and/or user generated content, as opposed to traditional media, which delivers content but does not allow readers/viewers/listeners to participate in the creation or development of content, or the comment or response to content.&nbsp; Examples of Social Media include, but are not limited to, blogs, chat rooms, Facebook, MySpace, Twitter, LinkedIn, Delicious, and YouTube.&nbsp;<br />\\n	&nbsp;<br />\\n	Sponsor &mdash; A Member under whom an enroller places a new Member or Customer, and is listed as the sponsor on the Member or Customer Application and Agreement.<br />\\n	&nbsp;<br />\\n	Upline &mdash; This term refers to the Member or Members above a particular Member in a sponsorship line up to the Company.&nbsp; Conversely stated, it is the line of sponsors that links any particular Member to the Company.</div>');
