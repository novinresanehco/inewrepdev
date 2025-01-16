
<?php
/***************************************************************************
 *                                  CMS CMS
 *                          -------------------
 *   copyright            : (C) 2009 The samaneh  $Team = "www.samaneh.com";
 *   copyright            : (C) 2009 The samaneh  $Team = "www.mihanphp.com";
 *   copyright            : (C) 2009 The samaneh  $Team = "www.mihanphp.ir";
 *   email                : info@samaneh.com
 *   email                : samaneh@gmail.com
 *   programmer           : Reza Shahrokhian
 *   File Name            : samanehBackup_1448985294
 ***************************************************************************/
//         Security
if ( !defined('news_security'))
{
die("You are not allowed to access this page directly");
}

mysql_query("CREATE TABLE `ads` (
  `adsID` int(10) NOT NULL AUTO_INCREMENT,
  `positionID` int(10) NOT NULL,
  `userID` int(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `hits` int(10) NOT NULL,
  `views` int(10) NOT NULL,
  `maxHits` int(10) NOT NULL,
  `maxViews` int(10) NOT NULL,
  `ipRange` text NOT NULL,
  `order` int(5) NOT NULL,
  PRIMARY KEY (`adsID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `ads` VALUES('4' , '4' , '0' , 'http://127.0.0.1/irancms/files/bg-cyan.jpg' , 'www.pardisgame.net' , 'عنوان' , '0' , '0' , '0' , '0' , '' , '123' );");  
mysql_query("CREATE TABLE `ads_hits` (
  `adsHitID` bigint(20) NOT NULL AUTO_INCREMENT,
  `adsID` int(10) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `agent` varchar(255) NOT NULL,
  PRIMARY KEY (`adsHitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `ads_positions` (
  `positionID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `template` text NOT NULL,
  PRIMARY KEY (`positionID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `ads_positions` VALUES('4' , '1' , '&lt;ads&gt;\r\n         &lt;div style=\\&quot;text-align: center\\&quot;&gt;&lt;a target=\\&quot;_blank\\&quot; href=\\&quot;[url]\\&quot; title=\\&quot;[title]\\&quot;&gt;&lt;img src=\\&quot;[image]\\&quot; /&gt;&lt;/a&gt;&lt;/div&gt;\r\n&lt;/ads&gt;' );");  
mysql_query("CREATE TABLE `ads_views` (
  `adsViewID` bigint(20) NOT NULL AUTO_INCREMENT,
  `adsID` int(10) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  PRIMARY KEY (`adsViewID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `autolinker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(250) NOT NULL,
  `cdate` int(20) NOT NULL,
  `update` int(20) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `url` varchar(250) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `desc` varchar(300) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `ref` int(10) NOT NULL,
  `stats` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `autolinker` VALUES('3' , 'admin' , '1371402611' , '0' , 'تست' , 'test.com' , 'این یک تست است.' , '0' , '4' );");  
mysql_query("CREATE TABLE `autolinkerset` (
  `title` varchar(256) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `url` varchar(256) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `time2` int(20) NOT NULL,
  `time3` int(20) NOT NULL,
  `bantime` int(20) NOT NULL,
  `list` int(20) NOT NULL,
  `badw` varchar(1000) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `fstats` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `autolinkerset` VALUES('my site' , 'http://www.mysite.com' , '10' , '30' , '15' , '0' , '' , '1' );");  
mysql_query("CREATE TABLE `bann` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `mess` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");  
mysql_query("CREATE TABLE `basket` (
  `basketID` int(10) NOT NULL AUTO_INCREMENT,
  `trackKey` varchar(50) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `ip` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `time` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `paymentStatus` tinyint(1) NOT NULL DEFAULT '0',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `tell` varchar(15) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `adminStatus` text NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`basketID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `basket_items` (
  `itemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `basketID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `count` int(10) NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `basket_transactions` (
  `transactionID` int(10) NOT NULL AUTO_INCREMENT,
  `transactionCode` varchar(100) NOT NULL,
  `trackKey` varchar(100) NOT NULL,
  `basketID` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `gateway` varchar(100) NOT NULL,
  `sendData` text NOT NULL,
  `reciveData` text NOT NULL,
  PRIMARY KEY (`transactionID`),
  UNIQUE KEY `trackKey` (`trackKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `block` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order` int(10) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `pos` int(1) NOT NULL,
  `users` int(1) NOT NULL,
  `plugins` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `block` VALUES('92' , '1' , 'بلوک تست' , '<p><span style=\"font-family: BTitrBold, tahoma;\">این یک تست است</span></p>' , '1' , '2' , 'none' );");  
mysql_query("CREATE TABLE `cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `enname` varchar(256) NOT NULL,
  `sub` int(10) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `cat` VALUES('78' , 'ایران سی ام اس' , 'ایران سی ام اس' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('79' , 'برنامه نویسی' , 'برنامه نویسی' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('80' , 'سئو' , 'سئو' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('81' , 'طراحی' , 'طراحی' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('82' , 'هاست و دامنه' , 'هاست و دامنه' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('83' , 'تکنیک و ترفند' , 'تکنیک و ترفند' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('84' , 'تکنولوژی' , 'تکنولوژی' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('85' , 'موارد مختلف' , 'موارد مختلف' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('86' , 'پورتال' , 'پورتال' , '78' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('87' , 'سی ام اس' , 'سی ام اس' , '78' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('88' , 'قالب' , 'قالب' , '78' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('89' , 'ماژول' , 'ماژول' , '78' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('90' , 'موارد مختلف' , 'موارد مختلف' , '78' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('91' , 'خبری' , 'خبری' , '87' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('92' , 'فروشگاه ساز' , 'فروشگاه ساز' , '87' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('93' , 'فروشگاه کارت شارژ' , 'فروشگاه کارت شارژ' , '87' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('94' , 'املاک' , 'املاک' , '87' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('95' , 'وبلاگ' , 'weblog' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('96' , 'اطلاعیه های شرکت' , 'event' , '95' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('97' , 'اخبار تکنولوژی' , 'newstech' , '95' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('98' , 'دانلود' , 'downloads' , '95' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('99' , 'کفسابی' , 'کفسابی' , '0' , '' );"); 
mysql_query("INSERT INTO `cat` VALUES('100' , 'کفسابی منزل' , 'کفسابی منزل' , '99' , '' );");  
mysql_query("CREATE TABLE `category_fields` (
  `fieldID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `name` varchar(35) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`fieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `category_fields` VALUES('1' , '11' , '216159082528c2141c4696165c92437f' , 'نوع فایل' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('2' , '12' , 'c7adebb8993a6a0fe0ebf6ce1342c907' , 'dffffffffffffffffff' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('3' , '9' , '93dfcaf3d923ec47edb8580667473987' , 'asdasdasdasdasd' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('4' , '9' , '0aa1ea9a5a04b78d4581dd6d17742627' , 'asdas' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('5' , '9' , '8277e0910d750195b448797616e091ad' , 'd' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('6' , '9' , 'f970e2767d0cfe75876ea857f92e319b' , 'as' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('7' , '9' , '8277e0910d750195b448797616e091ad' , 'd' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('8' , '9' , 'f970e2767d0cfe75876ea857f92e319b' , 'as' );"); 
mysql_query("INSERT INTO `category_fields` VALUES('9' , '9' , '196b0f14eba66e10fba74dbf9e99c22f' , 'dasd' );");  
mysql_query("CREATE TABLE `catpost` (
  `catid` int(11) NOT NULL,
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '19' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '20' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '21' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '22' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '23' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '24' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '25' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '26' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '27' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '28' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '30' );"); 
mysql_query("INSERT INTO `catpost` VALUES('49' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('50' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('51' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('52' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('53' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('54' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('55' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('56' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('57' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('58' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('59' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('60' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('61' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('62' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('63' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('64' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('65' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('66' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('67' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('68' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('69' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('70' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('71' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('72' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('73' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('75' , '31' );"); 
mysql_query("INSERT INTO `catpost` VALUES('89' , '38' );"); 
mysql_query("INSERT INTO `catpost` VALUES('85' , '39' );");  
mysql_query("CREATE TABLE `chat` (
  `conversationID` bigint(20) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `message` tinytext NOT NULL,
  `support` tinyint(1) NOT NULL DEFAULT '0',
  `parent` bigint(20) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`conversationID`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `chat` VALUES('21' , '1377368697' , '127.0.0.1/irancms' , 'سلام' , '0' , '0' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('22' , '0' , '' , 'سلام' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('23' , '0' , '' , 'چه کمکی' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('24' , '1377368882' , '127.0.0.1/irancms' , 'من' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('25' , '0' , '' , 'خب' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('26' , '0' , '' , 'بگو' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('27' , '1377368987' , '127.0.0.1/irancms' , '9630' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('28' , '0' , '' , ':دی' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('29' , '0' , '' , 'ساعت' , '1' , '21' , '1' );"); 
mysql_query("INSERT INTO `chat` VALUES('30' , '1377369385' , '127.0.0.1/irancms' , 'aaaa' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('31' , '1377371228' , '127.0.0.1/irancms' , 'sfsdf' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('32' , '1377371274' , '127.0.0.1/irancms' , 't' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('33' , '1377371340' , '127.0.0.1/irancms' , 'abcd' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('34' , '1377371350' , '127.0.0.1/irancms' , 'aaa' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('35' , '1377371362' , '127.0.0.1/irancms' , 'salam' , '0' , '21' , '0' );"); 
mysql_query("INSERT INTO `chat` VALUES('36' , '1380651261' , '127.0.0.1/irancms' , '3' , '0' , '0' , '0' );");  
mysql_query("CREATE TABLE `comments` (
  `c_id` int(8) NOT NULL AUTO_INCREMENT,
  `p_id` int(8) NOT NULL,
  `c_author` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `date` int(14) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ans` text CHARACTER SET utf8 NOT NULL,
  `memberid` int(10) NOT NULL DEFAULT '-1' COMMENT 'a user who send message',
  `active` int(1) NOT NULL,
  `ansid` int(10) NOT NULL COMMENT 'a manager who answer',
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `comments` VALUES('1' , '39' , 'آب و هوا' , 'سلام\nلطقا' , '1439483177' , '127.0.0.1' , 'http://' , 'maskmr85@gmail.com' , 'خیلی خوب' , '-1' , '1' , '1' );");  
mysql_query("CREATE TABLE `config` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `config` VALUES('adisable' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('admintheme' , 'pannonia' );"); 
mysql_query("INSERT INTO `config` VALUES('allow_types' , 'jpg,gif,zip,rar,txt,' );"); 
mysql_query("INSERT INTO `config` VALUES('catlinks' , 'cat/%id%-%name%.php' );"); 
mysql_query("INSERT INTO `config` VALUES('comment' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('contacttxt' , '<p style=\"text-align: center;\"><span style=\"font-family: BTitrBold, tahoma;\"><span style=\"font-size: 19px;\"><strong>این یک تست است.</strong></span></span></p>' );"); 
mysql_query("INSERT INTO `config` VALUES('desc' , 'توضيحات :' );"); 
mysql_query("INSERT INTO `config` VALUES('dir' , 'uploads' );"); 
mysql_query("INSERT INTO `config` VALUES('disable' , '0' );"); 
mysql_query("INSERT INTO `config` VALUES('dtype' , 'l j F Y' );"); 
mysql_query("INSERT INTO `config` VALUES('dzone' , '3.5' );"); 
mysql_query("INSERT INTO `config` VALUES('email' , '[resellername]@gmail.com' );"); 
mysql_query("INSERT INTO `config` VALUES('events' , 'eyJ0b2RheSI6IjEiLCJhbGwiOiIxIiwidW5hcHByb3ZlZENvbW1lbnRzIjoiMSIsImRhdGUiOiJtb250aGx5IiwibnVtYmVycyI6IjEwODUxNTAyNlxyXG41MjA1NDE1MlxyXG41OTQ5NiJ9' );"); 
mysql_query("INSERT INTO `config` VALUES('file_files' , '3' );"); 
mysql_query("INSERT INTO `config` VALUES('gallery_group_style' , 'gallery5' );"); 
mysql_query("INSERT INTO `config` VALUES('gallery_thn_height' , '100' );"); 
mysql_query("INSERT INTO `config` VALUES('gallery_thn_width' , '100' );"); 
mysql_query("INSERT INTO `config` VALUES('id' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('keys' , 'كلمات كليدي  :' );"); 
mysql_query("INSERT INTO `config` VALUES('lic' , '' );"); 
mysql_query("INSERT INTO `config` VALUES('mainpagepost' , '39' );"); 
mysql_query("INSERT INTO `config` VALUES('mainpagetheme' , 'first.htm' );"); 
mysql_query("INSERT INTO `config` VALUES('max_combined_size' , '90720' );"); 
mysql_query("INSERT INTO `config` VALUES('max_file_size' , '90720' );"); 
mysql_query("INSERT INTO `config` VALUES('member' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('member_area' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('min_pass_length' , '5' );"); 
mysql_query("INSERT INTO `config` VALUES('min_user_length' , '7' );"); 
mysql_query("INSERT INTO `config` VALUES('new_member' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('nlast' , '6' );"); 
mysql_query("INSERT INTO `config` VALUES('num' , '10' );"); 
mysql_query("INSERT INTO `config` VALUES('pagelinks' , 'page/%pageid%-%pagetitle%.php' );"); 
mysql_query("INSERT INTO `config` VALUES('pagetheme' , 'comment.htm' );"); 
mysql_query("INSERT INTO `config` VALUES('pdfcontent' , '[resellername]&lt;hr /&gt;&lt;b&gt;[title]&lt;/b&gt;&lt;hr /&gt;[content]&lt;hr /&gt;&lt;center&gt;All rights reserved.&lt;/center&gt;' );"); 
mysql_query("INSERT INTO `config` VALUES('position_1' , 'menumaker' );"); 
mysql_query("INSERT INTO `config` VALUES('postlinks' , 'post/%postid%-%posttitle%.php' );"); 
mysql_query("INSERT INTO `config` VALUES('random_name' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('recentpostbycat' , '32,33' );"); 
mysql_query("INSERT INTO `config` VALUES('register_rulles' , '' );"); 
mysql_query("INSERT INTO `config` VALUES('send_pm' , '2' );"); 
mysql_query("INSERT INTO `config` VALUES('send_post' , '2' );"); 
mysql_query("INSERT INTO `config` VALUES('seo' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('site' , 'http://127.0.0.1/irancms/' );"); 
mysql_query("INSERT INTO `config` VALUES('sitetitle' , '[resellername]' );"); 
mysql_query("INSERT INTO `config` VALUES('slider_group_style' , 'sliderdefault' );"); 
mysql_query("INSERT INTO `config` VALUES('slider_thn_height' , '100' );"); 
mysql_query("INSERT INTO `config` VALUES('slider_thn_width' , '100' );"); 
mysql_query("INSERT INTO `config` VALUES('subcatlinks' , 'cat/%id%-%name%.php' );"); 
mysql_query("INSERT INTO `config` VALUES('taglinks' , 'tag/%name%' );"); 
mysql_query("INSERT INTO `config` VALUES('theme' , 'fh3' );"); 
mysql_query("INSERT INTO `config` VALUES('theme_fh3_first_sidebars' , '{\"1_1\":\"0\",\"1_2\":\"0\"}' );"); 
mysql_query("INSERT INTO `config` VALUES('tries' , '3' );"); 
mysql_query("INSERT INTO `config` VALUES('userlink' , 'profile/%username%' );"); 
mysql_query("INSERT INTO `config` VALUES('user_list' , '1' );"); 
mysql_query("INSERT INTO `config` VALUES('weather_zipCode' , 'Mashhad' );");  
mysql_query("CREATE TABLE `contact` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `u_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `tell` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `counter` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `total` int(30) NOT NULL,
  `todaycounts` int(30) NOT NULL,
  `yescounts` int(30) NOT NULL,
  `month` int(30) NOT NULL,
  `year` int(30) NOT NULL,
  `lastyear` int(11) NOT NULL,
  `cdate` date NOT NULL,
  `lastmonth` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `counter` VALUES('1' , '16401' , '3' , '1' , '2' , '93' , '13218' , '2015-12-01' , '17' );");  
mysql_query("CREATE TABLE `data` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `pos` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `entitle` varchar(255) CHARACTER SET utf8 NOT NULL,
  `timage` varchar(255) CHARACTER SET utf8 NOT NULL,
  `cat_id` int(4) NOT NULL,
  `author` int(10) NOT NULL,
  `context` varchar(255) DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `show` int(1) NOT NULL DEFAULT '1',
  `scomments` int(1) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `star` int(1) NOT NULL DEFAULT '1',
  `expire` varchar(10) NOT NULL,
  `date` varchar(10) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) NOT NULL,
  `pass1` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pass2` varchar(255) CHARACTER SET utf8 NOT NULL,
  `reg` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `full` text CHARACTER SET utf8,
  `tvote` int(10) NOT NULL DEFAULT '0',
  `nov` int(5) NOT NULL DEFAULT '0',
  `textbox` text CHARACTER SET utf8,
  `image` text CHARACTER SET utf8,
  `textarea` text CHARACTER SET utf8,
  `sendpost` int(1) NOT NULL DEFAULT '0' COMMENT 'is this post is sent by uses ?',
  `acceptmsg` int(1) NOT NULL DEFAULT '0' COMMENT 'is private message sent to user on post accept?',
  `keywords` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `directoryid` bigint(10) NOT NULL DEFAULT '0',
  `Asdasd` text CHARACTER SET utf8,
  `Asasdaddasd` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`,`text`,`full`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=ucs2"); 
mysql_query("INSERT INTO `data` VALUES('15' , '0' , 'بسته پولی بانک مرکزی بازنگری می‌شود' , 'eco' , 'http://127.0.0.1/irancms/files/news/LG_1401693282_39a571bd06279610bc6329c141666523.jpg' , '39' , '1' , 'ادامه مطلب' , '5' , '1' , '1' , '0' , '0' , '0' , '1401887986' , '93' , '3' , '' , '' , '1' , '<p>معاون اقتصادی بانک مرکزی با اعلام اینکه بسته سیاستی-نظارتی نظام بانکی برای بازنگری مجدد باید به شورای پول و اعتبار ارائه شود، گفت: در تعیین نرخ&zwnj;های جدید سود سپرده بانکی، حقوق سپرده گذاران لحاظ شده است. ضمن اینکه مطرح می شد با تصمیمات جدید درباره سود بانکی، بازار ارز ملتهب شود؛ اما چنین نشد.</p>\n<p>&nbsp;پیمان قربانی در جمع خبرنگاران در پاسخ به سئوال خبرنگار مهر در مورد بازنگری در بسته سیاستی- نظارتی بانک مرکزی گفت: بسته بانک مرکزی بازنگری می شود و البته باید در شورای پول و اعتبار به تصویب برسد تا در نهایت جزئیات آن اعلام شود، همچنین آنچه فعلا در بانک مرکزی مورد بازنگری قرار می گیرد، مولفه های مربوط به سیاست پولی است.</p>\n<div id=\"mcePasteBin\" style=\"position: absolute; top: 0px; left: 0; background: red; width: 1px; height: 1px; overflow: hidden\" contenteditable=\"false\">\n<div contenteditable=\"true\">پیمان قربانی در جمع خبرنگاران در پاسخ به سئوال خبرنگار مهر در مورد بازنگری در بسته سیاستی- نظارتی بانک مرکزی گفت: بسته بانک مرکزی بازنگری می شود و البته باید در شورای پول و اعتبار به تصویب برسد تا در نهایت جزئیات آن اعلام شود، همچنین آنچه فعلا در بانک مرکزی مورد بازنگری قرار می گیرد، مولفه های مربوط به سیاست پولی است.</div>\n</div>' , '<p>معاون اقتصادی بانک مرکزی در پاسخ به این سئوال که نرخ های جدید سود سپرده بانکی به ضرر سپرده گذاران است و نرخ ها به صورت دستوری تعیین شده است، عنوان کرد: در تعیین نرخ سود رویکردها مثبت بوده و حقوق سپرده گذاران در آن لحاظ شده است.<br /><br />میزان خروج سپرده' , '0' , '0' , '' , '' , '' , '0' , '0' , 'بانک,بانک مرکزی,' , 'بسته پولی بانک مرکزی بازنگری می‌شود' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('16' , '0' , 'تحویل پیتزا با هواپیمای بدون سرنشین در هند!' , 'eco' , 'http://127.0.0.1/irancms/files/news/LG_1400920400_22255fdb85534c3a80d0ecf461a92529.jpg' , '38' , '1' , 'ادامه مطلب' , '12' , '1' , '1' , '0' , '0' , '0' , '1401888368' , '93' , '3' , '' , '' , '1' , '<p>گزارش ها از شهر مومبای (بمبئی) در هند حاکی از تحقیقات پلیس آن شهر در مورد استفاده یک رستوران از هواپیمای بدون سرنشین برای تحویل پیتزا به مشتریان است.</p>\n<p>&nbsp;طبق گزارش \"اکونومیک تایمز\" ، روزنامه انگلیسی زبان چاپ هند، پیتزافروشی فرانسسکو می گوید که با موفقیت توانسته از یک هواپیمای کوچک چهار ملخه بدون سرنشین، برای تحویل پیتزا به یک مشتری استفاده کند که در آسمانخراشی ساکن بوده است. این محل در فاصله حدود یک و نیم کیلومتری رستوران قرار داشته و هواپیما با استفاده از کنترل از راه دور هدایت می شده است.</p>\n<div id=\"mcePasteBin\" style=\"position: absolute; top: 0px; left: 0; background: red; width: 1px; height: 1px; overflow: hidden\" contenteditable=\"false\">\n<div contenteditable=\"true\">طبق گزارش \"اکونومیک تایمز\" ، روزنامه انگلیسی زبان چاپ هند، پیتزافروشی فرانسسکو می گوید که با موفقیت توانسته از یک هواپیمای کوچک چهار ملخه بدون سرنشین، برای تحویل پیتزا به یک مشتری استفاده کند که در آسمانخراشی ساکن بوده است. این محل در فاصله حدود یک و نیم کیلومتری رستوران قرار داشته و هواپیما با استفاده از کنترل از راه دور هدایت می شده است.</div>\n</div>' , '<p>شهر مومبای به خاطر داشتن ترافیک سنگین مشهور است، به همین دلیل، این رستوران می گوید که استفاده از هواپیمای بدون سرنشین روشی است بدون آلودگی محیط زیست که موجب صرفه جویی در وقت هم می شود.<br /><br />درویدیویی که این رستوران منتشر کرده، استفاده از هواپیما برای تحویل پیتزا نشان داده می شود.<br /><br />پلیس مومبای می گوید در حال تحقیق از مقامات هوانوردی کشوری در مورد کسب مجوز استفاده از هواپیما توسط این رستوارن است.<br /><br />یک از مسئولان مرکز کنترل ترافیک هوایی گفته است که \"طبق روال معمول، برای پرواز هر جسم پرنده ای، باید مجوز اخذ شود\".<br /><br />یکی از فرماندهان پلیس محلی هم به خبرگزاری پی تی آی هند گفته است: \"ما نسبت به اشیاء پرنده در آسمان که با کنترل از راه دور هدایت می شوند، بسیار حساس هستیم.\"<br /><br />شبکه آی بی ان از قول برخی منابع گزارش کرده که نیروهای امنیتی هند به احتمال وقوع حملات تروریستی با استفاده از پاراگلایدر یا هواپیمای بدون سرنشین حساسیت زیادی دارند. یک منبع هم به روزنامه اکونومیک تایمز گفته است که ارتفاع پرواز هواپیمای بدون سرنشین مورد بحث بیش از ۱۳۰ متر نیست و اختلالی در ترافیک هوایی ایجاد نمی کند. ضمنا این جسم پرنده هیچوقت از محدوده کنترل فرد هدایت کننده آن خارج نمی شود.<br /><br />سال گذشته، شرکت آمازون گفت مشغول آزمایش امکان تحویل بسته های پستی توسط هواپیماهای بدون سرنشین است ولی ارائه این خدمات پنج سال طول خواهد کشید.</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'پیتزا' , 'تحویل پیتزا با هواپیمای بدون سرنشین در هند!' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('14' , '0' , 'حاشیه‌های سفر تیم ملی فوتبال به برزیل' , 'brazil' , 'http://127.0.0.1/irancms/files/news/LG_1401784482_7f0b2d0dccc64ec1c1096c7aaa508113.jpg' , '42' , '1' , 'ادامه مطلب' , '2' , '1' , '1' , '0' , '0' , '0' , '1401887646' , '93' , '3' , '' , '' , '1' , '<p>تیم ملی فوتبال ایران در شرایطی بامداد روز سه شنبه تهران را به مقصد سائوپائول ترک کرد که سرمربی این تیم پس از مدتها کش و قوس با تولیدی البسه تیم ملی بالاخره پذیرفت تا لباس این تولیدی را برتن کند. این اتفاق پس از آن رخ داد که کی روش هزینه مربوطه را دریافت کرد!</p>\n<p>اعضای تیم ملی فوتبال ایران که دوشنبه شب در مراسم بدرقه برگزار شده در سالن 12 هزار نفری مجموعه ورزشی آزادی حضور پیدا کرده بودند، ساعت 5 صبح روز سه شنبه از طریق فرودگاه بین&zwnj;المللی امام خمینی(ره) تهران را به مقصد برزیل ترک کردند. این سفر در حالی انجام شد که برخی مسئولان فدراسیون و وزارت ورزش و جوانان هم در فرودگاه حاضر بودند.</p>\n<div id=\"mcePasteBin\" style=\"position: absolute; top: 0px; left: 0; background: red; width: 1px; height: 1px; overflow: hidden\" contenteditable=\"false\">\n<div contenteditable=\"true\">اعضای تیم ملی فوتبال ایران که دوشنبه شب در مراسم بدرقه برگزار شده در سالن 12 هزار نفری مجموعه ورزشی آزادی حضور پیدا کرده بودند، ساعت 5 صبح روز سه شنبه از طریق فرودگاه بین&zwnj;المللی امام خمینی(ره) تهران را به مقصد برزیل ترک کردند. این سفر در حالی انجام شد که برخی مسئولان فدراسیون و وزارت ورزش و جوانان هم در فرودگاه حاضر بودند.</div>\n</div>' , '<p>* با وجود اینکه مراسم بدرقه از تیم ملی در سالن 12 هزار نفری آزادی برگزار شده بود و خبرنگاران و عکاسان زیادی این برنامه را پوشش دادند، اما بازهم این نمایندگان رسانه های گروهی بودند که با حضور در فرودگاه به عنوان حامیان اصلی تیم ملی، ملی پوشان را بدرقه کردند.<br /><br />* محمود گودرزی وزیر ورزش و جوانان که در مراسم بدرقه هم حضور داشت، به فرودگاه امام خمینی رفت و اعضای تیم ملی را بدرقه کرد.<br /><br />* علی کفاشیان رئیس فدراسیون فوتبال همراه اعضای تیم ملی بود و بازیکنان را بدرقه کرد.<br /><br />* مهدی محمدنبی سرپرست تیم ملی فوتبال ایران به همراه ملی پوشان راهی برزیل نشد. قرار است او طی روزهای آینده به تیم ملی اضافه شود.<br /><br />* علی کفاشیان رئیس فدراسیون فوتبال نیز اعلام کرد روز یکشنبه راهی برزیل خواهد شد.<br /><br />* کارلوس کی روش که طی ماه های گذشته با تولیدی آل اشپورت مشکل داشت و حاضر نمی شد لباس این تولیدی را برتن کند، پس از جلسه ای که با مدیر این شرکت داشت و دریافت مبلغی، حاضر شد در فرودگاه لباس تولیدی آل اشپورت را برتن کند.<br /><br />* بازیکنان تیم ملی فوتبال ایران پس از حضور در فرودگاه در سالن ویژه حضور پیدا کردند و پس از انجام امور مربوط به پرواز راهی برزیل شدند.<br /><br />تیم ملی فوتبال ایران قرار است در اردوی برزیل یک دیدار تدارکاتی با تیم ملی ترینیداد و توباگو برگزار کند. تیم ملی ایران در بیستمین دوره رقابت های جام جهانی با تیم های نیجریه، آرژانتین و بوسنی و هرزگوین هم گروه است و روز 26 خرداد در اولین بازی به مصاف نیجریه خواهد رفت.</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'ورزش,فوتبال,جام جهانی,برزیل2014' , 'حاشیه‌های سفر تیم ملی فوتبال به برزیل' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('17' , '0' , 'سی و سومین جشنواره تئاتر فجر' , 'teatr' , 'http://127.0.0.1/irancms/files/news/LG_1401712055_c4a20c6f06c87a0463c6df65e8933e3c.jpg' , '40' , '1' , 'ادامه مطلب' , '56' , '1' , '1' , '0' , '0' , '0' , '1401888698' , '93' , '3' , '' , '' , '1' , '<p>بعد از پایان سی و دومین جشنواره بین&zwnj;المللی تئاتر فجر در بهمن ماه سال 92 خانواده تئاتر انتظار داشتند که دبیر دوره سی و سوم این جشنواره به سرعت انتخاب و معرفی شود اما بعد از گذشت 4 ماه از پایان جشنواره سی و دوم و در حالی که وارد نیمه دوم خردادماه سال 93 می&zwnj;شویم، هنوز دبیر سی و سومین جشنواره بین&zwnj;المللی تئاتر فجر معرفی و انتخاب نشده است.<br /><br />البته پیش از این اسامی هنرمندانی چون نغمه ثمینی، حسن فتحی، محمد چرمشیر، حمید امجد و حمیدرضا افشار در برخی رسانه&zwnj;ها به عنوان گزینه&zwnj;های مدنظر برای دبیری دوره سی و سوم جشنواره بین&zwnj;المللی تئاتر فجر مطرح شدند که هیچکدام از این گمانه زنی&zwnj;ها تاکنون به واقعیت تبدیل نشده است.<br /><br />با توجه به اینکه فراخوان دوره سی و سوم جشنواره تئاتر فجر باید پیش تا امروز منتشر می&zwnj;شد، رسیدن به فصل تابستان وضعیت برگزاری جشنواره سی و سوم تئاتر فجر را با شرایط مبهم&zwnj;تری مواجه خواهد کرد.</p>\n<p>&nbsp;</p>\n<div id=\"mcePasteBin\" style=\"position: absolute; top: 0px; left: 0; background: red; width: 1px; height: 1px; overflow: hidden\" contenteditable=\"false\">\n<div contenteditable=\"true\">بعد از پایان سی و دومین جشنواره بین&zwnj;المللی تئاتر فجر در بهمن ماه سال 92 خانواده تئاتر انتظار داشتند که دبیر دوره سی و سوم این جشنواره به سرعت انتخاب و معرفی شود اما بعد از گذشت 4 ماه از پایان جشنواره سی و دوم و در حالی که وارد نیمه دوم خردادماه سال 93 می&zwnj;شویم، هنوز دبیر سی و سومین جشنواره بین&zwnj;المللی تئاتر فجر معرفی و انتخاب نشده است.<br /><br />البته پیش از این اسامی هنرمندانی چون نغمه ثمینی، حسن فتحی، محمد چرمشیر، حمید امجد و حمیدرضا افشار در برخی رسانه&zwnj;ها به عنوان گزینه&zwnj;های مدنظر برای دبیری دوره سی و سوم جشنواره بین&zwnj;المللی تئاتر فجر مطرح شدند که هیچکدام از این گمانه زنی&zwnj;ها تاکنون به واقعیت تبدیل نشده است.<br /><br />با توجه به اینکه فراخوان دوره سی و سوم جشنواره تئاتر فجر باید پیش تا امروز منتشر می&zwnj;شد، رسیدن به فصل تابستان وضعیت برگزاری جشنواره سی و سوم تئاتر فجر را با شرایط مبهم&zwnj;تری مواجه خواهد کرد.</div>\n</div>' , '<p>البته شنیده' , '0' , '0' , '' , '' , '' , '0' , '0' , 'تئاتر فجر' , 'سی و سومین جشنواره تئاتر فجر با دبیر خود آشنا می‌شود' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('18' , '18' , 'تست آخرین اخبار' , 'تست آخرین اخبار' , 'http://127.0.0.1/irancms/files/sample/00992484.jpg' , '75' , '1' , '' , '1' , '1' , '1' , '0' , '0' , '0' , '1421001232' , '93' , '10' , '' , '' , '1' , '<p>تست آخرین اخبارتست آخرین اخبارتست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبار</p>' , '<p>تست آخرین اخبارتست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبارتست آخرین اخبار</p>\n<p>تست آخرین اخبار</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی' , 'توضیح' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('19' , '19' , 'چطور یک کودک باهوش پرورش دهیم؟' , 'چطور یک کودک باهوش پرورش دهیم؟' , 'http://127.0.0.1/irancms/files/adsa_490x312.jpg' , '49' , '1' , '' , '1' , '1' , '1' , '0' , '0' , '0' , '1421145952' , '93' , '10' , '' , '' , '1' , '<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('20' , '20' , 'الگوهای معمول خواب نوزاد كدامند؟' , 'الگوهای معمول خواب نوزاد كدامند؟' , 'http://127.0.0.1/irancms/files/bg-red.jpg' , '49' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146026' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('21' , '21' , 'رمزگشایی رفتارهای نامتعارف کودک نوپا' , 'رمزگشایی رفتارهای نامتعارف کودک نوپا' , 'http://127.0.0.1/irancms/files/10247498_284158231751130_2068507499884423601_n.jpg' , '49' , '1' , '' , '16' , '1' , '1' , '0' , '0' , '0' , '1421146064' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('22' , '22' , 'كودك شما برای رشد و بالندگی به چه چیزهایی نیاز دارد؟' , 'كودك شما برای رشد و بالندگی به چه چیزهایی نیاز دارد؟' , 'http://127.0.0.1/irancms/files/sample/00943843.png' , '49' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146113' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('23' , '23' , 'نکاتی جهت قصه گویی موثر برای کودکان' , 'نکاتی جهت قصه گویی موثر برای کودکان' , 'http://127.0.0.1/irancms/files/news/LG_1401712055_c4a20c6f06c87a0463c6df65e8933e3c.jpg' , '49' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146149' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('24' , '24' , 'مشكلاتی بر سر زمان خواب چگونه از ابتدا با آنها مقابله كنیم؟' , 'مشكلاتی بر سر زمان خواب چگونه از ابتدا با آنها مقابله كنیم؟' , 'http://127.0.0.1/irancms/files/news/LG_1401784482_7f0b2d0dccc64ec1c1096c7aaa508113.jpg' , '49' , '1' , '' , '1' , '1' , '1' , '0' , '0' , '0' , '1421146196' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('25' , '25' , 'نگهداری از كودك توسط بستگان ،تا چه اندازه توصیه می شود؟' , 'نگهداری از كودك توسط بستگان ،تا چه اندازه توصیه می شود؟' , 'http://127.0.0.1/irancms/files/news/LG_1400920400_22255fdb85534c3a80d0ecf461a92529.jpg' , '49' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146222' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('26' , '26' , 'به كودك خود كمك كنید تا حرف بزند' , 'به كودك خود كمك كنید تا حرف بزند' , 'http://127.0.0.1/irancms/files/news/LG_1400920400_22255fdb85534c3a80d0ecf461a92529.jpg' , '49' , '1' , '' , '8' , '1' , '1' , '0' , '0' , '0' , '1421146241' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('27' , '27' , 'یک نوشیدنی برای کودکان بعد از 10 ماه' , 'یک نوشیدنی برای کودکان بعد از 10 ماه' , 'http://127.0.0.1/irancms/files/news/LG_1401693282_39a571bd06279610bc6329c141666523.jpg' , '49' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146285' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('28' , '28' , 'مواد غذایی حساسیت زا برای کودکان زیر یکسال' , 'مواد غذایی حساسیت زا برای کودکان زیر یکسال' , 'http://127.0.0.1/irancms/files/sample/425081_343.jpg' , '49' , '1' , '' , '2' , '1' , '1' , '0' , '0' , '0' , '1421146319' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('29' , '0' , 'طرز تهیه چند غذای کمکی برای شیرخواران' , 'طرز تهیه چند غذای کمکی برای شیرخواران' , 'http://127.0.0.1/irancms/files/sample/00992484.jpg' , '84' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1421146343' , '94' , '9' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('30' , '30' , 'چگونگی شیردادن به دوقلوها' , 'چگونگی شیردادن به دوقلوها' , 'http://127.0.0.1/irancms/files/sample/407250_278.jpg' , '49' , '1' , '' , '2' , '1' , '1' , '0' , '0' , '0' , '1421146361' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('31' , '31' , 'چگونه چاقی و اضافه وزن كودك را تشخیص دهیم' , 'چگونه چاقی و اضافه وزن كودك را تشخیص دهیم' , 'http://127.0.0.1/irancms/files/bg-red_450x271.jpg' , '49' , '1' , '' , '2' , '1' , '1' , '0' , '0' , '0' , '1421146382' , '93' , '10' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('32' , '0' , 'چگونه چاقی و اضافه وزن كودك را تشخیص دهیم' , 'چگونه چاقی و اضافه وزن كودك را تشخیص دهیم' , 'http://127.0.0.1/irancms/files/bg-red_450x271.jpg' , '84' , '1' , '' , '2' , '1' , '1' , '0' , '0' , '0' , '1421146387' , '94' , '9' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('41' , '0' , 'این یک مطلب تست می باشد' , 'این یک مطلب تست می باشد' , 'http://127.0.0.1/irancms/files/010.jpg' , '96' , '1' , '' , '0' , '1' , '1' , '0' , '0' , '0' , '1448983383' , '94' , '9' , '' , '' , '1' , '<p>دونالد ترامپ، نامزد احتمالی جمهوری خواهان در انتخابات ریاست جمهوری 2016 آمریکا که از همان آغاز ورود به رقابت ها به سبب اظهارنظرها و مواضع جنجالی اش شهرت یافته، این روزها به سبب همین مواضع در موقعیت دشواری قرار گرفته است. کاهش حمایت گروه های مختلف از وی در پی برخی اظهارات اخیر، چشم اذایسبسیذبدی&nbsp; انداز ادامه اوج گیری وی در میان دیگر نامزدهای هم حزبی اش را تیره کرده است.</p>' , '<p>دونالد ترامپ، نامزد احتمالی جمهوری خواهان در انتخابات ریاست جمهوری 2016 آمریکا که از همان آغاز ورود به رقابت ها به سبب اظهارنظرها و مواضع جنجالی اش شهرت یافته، این روزها به سبب همین مواضع در موقعیت دشواری قرار گرفته است. کاهش حمایت گروه های مختلف از وی در پی برخی اظهارات اخیر، چشم انداز ادامه اوج گیری وی در میان دیگر نامزدهای هم حزبی اش را تیره کرده است.</p>\n<div style=\"text-align: justify;\">' , '0' , '0' , '' , '' , '' , '0' , '0' , 'رنگ آمیزی صنعتی، رنگ آمیزی صنعتی، رنگ آمیزی صنعتی، رنگ آمیزی صنعتی، داربست، داربست، داربست، داربست، داربست، داربست، داربست،' , '' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('33' , '0' , 'نکته برای شروع تغذیه تکمیلی کودک' , 'نکته برای شروع تغذیه تکمیلی کودک' , 'http://127.0.0.1/irancms/files/sample/00990652.jpg' , '84' , '1' , '' , '7' , '1' , '1' , '0' , '0' , '0' , '1421146413' , '94' , '9' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('34' , '0' , 'هفت توصیه برای صبحانه' , 'هفت توصیه برای صبحانه خوردن صبحانه خوردن' , 'http://127.0.0.1/irancms/files/sample/00990652.jpg' , '78' , '1' , '' , '84' , '1' , '1' , '0' , '0' , '0' , '1421146429' , '94' , '5' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟</p>\n<p>چطور یک کودک باهوش پرورش دهیم؟</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('35' , '0' , 'کلوچه گوشت چرخ کرده برای کودکان' , 'کلوچه گوشت چرخ کرده برای کودکان' , 'http://127.0.0.1/irancms/files/sample/424841_510.jpg' , '84' , '1' , '' , '21' , '1' , '1' , '0' , '0' , '0' , '1421146450' , '94' , '9' , '' , '' , '1' , '<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>\n<p>الگوهای معمول خواب نوزاد كدامند؟</p>' , '<p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'کلمات کلیدی:چطور یک کودک باهوش پرورش دهیم؟' , 'توضیح:چطور یک کودک باهوش پرورش دهیم؟چطور یک کودک باهوش پرورش دهیم؟' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('38' , '38' , 'ماژول آب و هوا' , 'ماژول آب و هوا' , 'http://127.0.0.1/irancms/files/news/LG_1400920400_22255fdb85534c3a80d0ecf461a92529.jpg' , '78' , '1' , '' , '17' , '1' , '1' , '0' , '0' , '0' , '1436709571' , '94' , '4' , '' , '' , '1' , '<p>ماژول آب و هواماژول آب و هواماژول آب و هواماژول آب و هوا</p>\n<p>ماژول آب و هواماژول آب و هوا</p>\n<p>ماژول آب و هواماژول آب و هوا</p>\n<p>ماژول آب و هوا</p>\n<p>ماژول آب و هوا</p>' , '<p>ماژول آب و هواماژول آب و هواماژول آب و هواماژول آب و هواماژول آب و هوا</p>\n<p>ماژول آب و هوا</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , '' , 'teasdasdst' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('37' , '0' , 'testtttsdsadasdtttttttttasda' , 'asdasdasd' , 'http://127.0.0.1/irancms/files/adsa_490x312.jpg' , '84' , '1' , '' , '7' , '1' , '1' , '0' , '0' , '0' , '1435767963' , '94' , '9' , '' , '' , '1' , '<p>asdasdasd</p>' , '<p>asdasdasd</p>' , '0' , '0' , '' , '' , '' , '0' , '0' , 'asdasd' , 'asdasd' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('39' , '0' , 'به وبسایت پدید آوران پاکیزگی غزل خوش آمدید' , 'به وبسایت پدید آوران پاکیزگی غزل خوش آمدید' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s4.jpg' , '84' , '1' , '' , '128' , '1' , '1' , '1' , '0' , '0' , '1436710407' , '94' , '9' , '' , '' , '1' , '<p><img style=\"display: inherit; position: inherit; max-width: 660px; margin: 10px; float: right; width: 48%;\" src=\"theme/core/fh3/images/s4.jpg\" alt=\"خوش آمدید به سایت شرکت فرا ساختمان هزاره سوم\" /></p>\n<p>امروزه در ساختمانهای مسکونی،تجاری،اداری،برج های مرتفع و مدرن شهری،ادارات،شرکتها پارکها و فضای سبز،مکانهای سرپوشیده ورزشی و... جهت از بین بردن جرم و لکه کف سنگ و همچنین صاف و صیقل نمودن و ترمیم انواع سنگ،لاشه،موزائیگ نیاز به کفسابی وسنگ سابی وکفشویی می باشد این شرکت با داشتن دستگاههای سه فاز و تکفاز و نیروی مجرب آمادگی خود را برای انجام کفسابی در کلیه نقاط ایران اعلام می دارد. همچنین این شرکت جهت دسترسی آسان در ارتفاع و موقعیت هاي خاص که غیر قابل دسترس می باشند،جهت اجرای عملیات مختلف،امکان اجرای خواسته ها و نیازهای کارفرمایان در ارتفاع و نماهای بیرونی پروژه ها که از دستگاه های(کلایمبر) وسازه های فلزی مانند داربست استفاده می نمایند،جدا ازهزینه های سنگین، اشغال فضای عابر پیاده و تحمیل زمان زیاد،ایجاد خسارتهای مالی و جانی که بدلیل این شیوه کاری در پروژه ها،غیر قابل انکار است. همواره درجهان دسترسی با طناب شیوه ای منتخب برای رسیدن به نقاط مرتفع و غیر قابل دسترس می باشد.عملیاتي که با تکنیک راپل و به وسیله ابزار های صخره نوردي صنعتی قابل انجام می باشد بدین شرح است(شستشو نمای ساختمان و برجهای مرتفع با آب گرم و یا سرد)(انجام واتر سند بلاست)(انجام رنگ آمیزی نمای ساختمان ها و سازه های صنعتی) (نصب شیشه در ارتفاع)(اجرای جوشکاری و آچارکشی)(انجام آببندی و عایق های حرارتی)(رول پلاک سنگ و نصب اجسام در ارتفاع)(نصب تجهیزات و هر شیي سنگین و نیمه سنگین در ارتفاع و بلعکس)(انجام تخریب&nbsp; دودکش های صنعتی پلها و صخره ها جهت کشایش فضای پروژه ها و جاده سازی )(ایمن سازیجاده های صخره ای در برابر خطر ریزش سنگ)(انجام سیمانکاری و نصب بیلبرد و بنر های تبلیغاتی) (اجرای نورپردازی) و اجرای هرگونه کار در ارتفاع بنا به خواست کارفرما بدون محدودیت در ارتفاع و خاص بودن موقعیت همراه با تجهیزات پیشرفته و پرسنل با تجربه انجام می پذیرد.<br /><br />این شرکت افتخار آن را دارد با استفاده ازتجهیزات وابزار آلات پیشرفته وپرسنل مجرب به صورت تخصصی در سطح گسترده واصولی به اجرا در آورده است<br /><br />ما آمادگی خود را جهت همکاری و مشارکت در پروژه های داخلی در سراسر کشور و درتمام نقاط ایران اعلام می داریم</p>' , '' , '0' , '0' , '' , '' , '' , '0' , '0' , '' , 'teasdasdst' , '0' , '' , '' );"); 
mysql_query("INSERT INTO `data` VALUES('40' , '40' , 'کفسابی در کلیه نقاط ایران اعلام می دارد' , 'کفسابی در کلیه نقاط ایران اعلام می دارد' , 'http://127.0.0.1/irancms/files/news/LG_1401784482_7f0b2d0dccc64ec1c1096c7aaa508113.jpg' , '84' , '1' , '' , '7' , '1' , '1' , '0' , '0' , '0' , '1448632095' , '94' , '9' , '' , '' , '1' , '<p>کفسابی در کلیه نقاط ایران اعلام می دارد. همچنین این شرکت جهت دسترسی آسان در ارتفاع و موقعیت هاي خاص که غیر قابل دسترس می باشند،جهت اجرای عملیات مختلف،امکان اجرای خواسته ها و نیازهای کارفرمایان در ارتفاع و نماهای بیرونی پروژه ها که از دستگاه های(کلایمبر) وسازه های فلزی مانند داربست استفاده می نمایند،جدا ازهزینه های سنگین، اشغال فضای عابر پیاده و تحمیل زمان زیاد،ایجاد خسارتهای مالی و جانی که بدلیل این شیوه کاری در پروژه ها،غیر قابل انکار است. همواره درجهان دسترسی با طناب شیوه ای منتخب برای رسیدن به نقاط مرتفع و غیر قابل دسترس می باشد.عملیاتي که با تکنیک راپل و به وسیله ابزار های صخره نوردي صنعتی قابل انجام می باشد بدین شرح است(شستشو نمای ساختمان و برجهای مرتفع با آب گرم و یا سرد)(انجام واتر سند بلاست)(انجام رنگ آمیزی نمای ساختمان</p>' , '' , '0' , '0' , '' , '' , '' , '0' , '0' , '' , '' , '0' , '' , '' );");  
mysql_query("CREATE TABLE `ebasket` (
  `basketID` int(10) NOT NULL AUTO_INCREMENT,
  `trackKey` varchar(50) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `ip` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `time` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `paymentStatus` tinyint(1) NOT NULL DEFAULT '0',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `tell` varchar(15) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `adminStatus` text NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`basketID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `ebasket_items` (
  `itemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `basketID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `count` int(10) NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `ebasket_transactions` (
  `transactionID` int(10) NOT NULL AUTO_INCREMENT,
  `transactionCode` varchar(100) NOT NULL,
  `trackKey` varchar(100) NOT NULL,
  `basketID` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `gateway` varchar(100) NOT NULL,
  `sendData` text NOT NULL,
  `reciveData` text NOT NULL,
  PRIMARY KEY (`transactionID`),
  UNIQUE KEY `trackKey` (`trackKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `ecategory_fields` (
  `fieldID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `name` varchar(35) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`fieldID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `ecategory_fields` VALUES('1' , '1' , '216159082528c2141c4696165c92437f' , 'نوع فایل' );");  
mysql_query("CREATE TABLE `eproduct_categories` (
  `categoryID` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `productCount` int(5) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `parentID` int(3) NOT NULL DEFAULT '0',
  `afterPayment` text NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `eproduct_categories` VALUES('1' , 'تست فروش لینک' , 'تست فروش لینک' , '0' , 'http://127.0.0.1/irancms/files/bg-cyan.jpg' , '0' , '&lt;p&gt;با تشکر از خرید شما شما قادر به مشاهده لینک خریداری شده میباشید&lt;/p&gt;' );");  
mysql_query("CREATE TABLE `eproduct_fields` (
  `productFeildID` bigint(20) NOT NULL AUTO_INCREMENT,
  `productID` int(10) NOT NULL,
  `fieldID` int(10) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`productFeildID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `eproduct_fields` VALUES('1' , '1' , '1' , 'تست فیلد اضافی' );"); 
mysql_query("INSERT INTO `eproduct_fields` VALUES('2' , '2' , '1' , 'تست فیلد اضافی' );"); 
mysql_query("INSERT INTO `eproduct_fields` VALUES('3' , '3' , '1' , 'تست فیلد اضافی' );"); 
mysql_query("INSERT INTO `eproduct_fields` VALUES('4' , '4' , '1' , 'تست فیلد اضافی' );");  
mysql_query("CREATE TABLE `eproduct_images` (
  `imageID` int(10) NOT NULL AUTO_INCREMENT,
  `imageUrl` varchar(255) NOT NULL,
  `productID` int(10) NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `eproduct_images` VALUES('5' , 'http://127.0.0.1/irancms/files/bg-red_450x271.jpg' , '1' );"); 
mysql_query("INSERT INTO `eproduct_images` VALUES('6' , 'http://127.0.0.1/irancms/files/adsa_490x312.jpg' , '2' );"); 
mysql_query("INSERT INTO `eproduct_images` VALUES('7' , 'http://127.0.0.1/irancms/files/sample/00940484.jpg' , '3' );"); 
mysql_query("INSERT INTO `eproduct_images` VALUES('8' , 'http://127.0.0.1/irancms/files/sample/425081_343.jpg' , '4' );");  
mysql_query("CREATE TABLE `eproducts` (
  `productID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(100) NOT NULL,
  `category` int(3) NOT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `eproducts` VALUES('1' , 'محصول تست' , '&lt;p&gt;محصول تست&lt;/p&gt;' , '123323' , '1' , '0' , 'http://127.0.0.1/eshop/?productID=4' );"); 
mysql_query("INSERT INTO `eproducts` VALUES('2' , 'محصول تست  3' , '&lt;p&gt;محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3محصول تست&nbsp; 3&lt;/p&gt;' , '2234234' , '1' , '0' , 'http://novinresaneh.com/' );"); 
mysql_query("INSERT INTO `eproducts` VALUES('3' , 'محصول تست  333333333333333333333' , '&lt;p&gt;محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333محصول تست&nbsp; 333333333333333333333&lt;/p&gt;' , '134323323' , '1' , '0' , 'زمین در جاده رشت' );"); 
mysql_query("INSERT INTO `eproducts` VALUES('4' , 'محصول تasadasdست' , '&lt;p&gt;محصول تasadasdستمحصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdستمحصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdستمحصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdستمحصول تasadasdست&lt;/p&gt;\r\n&lt;p&gt;محصول تasadasdستمحصول تasadasdستمحصول تasadasdستمحصول تasadasdست&lt;/p&gt;' , '134323323' , '1' , '0' , 'www.pardisgame.net' );");  
mysql_query("CREATE TABLE `eshopsearch` (
  `searchID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`searchID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `extra` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `users` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `extra` VALUES('18' , 'test' , '<p>test is good</p>' , '2' );"); 
mysql_query("INSERT INTO `extra` VALUES('19' , 'aaaaaaaaaaaaaaaa' , '<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>\n<p><img src=\"http://127.0.0.1/irancms/files/first.jpg\" alt=\"\" width=\"208\" height=\"123\" /></p>' , '2' );"); 
mysql_query("INSERT INTO `extra` VALUES('20' , 'نمئدموئذردمنئلانملب' , '<p>بلالنمدئالبکمائبلملا</p>' , '2' );");  
mysql_query("CREATE TABLE `fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `orderid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `fields` VALUES('1' , 'city' , 'شهر' , '1' );");  
mysql_query("CREATE TABLE `form` (
  `formID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `mailto` varchar(50) NOT NULL,
  `submitMSG` varchar(255) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`formID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `form` VALUES('12' , 'استخدام' , '1' , 'rashcms@gmail.com' , 'اطلاعات ثبت نام با موفقیت دریافت شد.\r\nکد رهگیری : [id]' , 'YTo3OntzOjE1OiJ3MWJrMGJwNHUyZzMyZHQiO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6Njoi2YbYp9mFIjtzOjk6ImZpZWxkdHlwZSI7czo1OiJpbnB1dCI7czo1OiJ2YWx1ZSI7czowOiIiO3M6ODoicmVxdWlyZWQiO3M6MToiMSI7czozOiJtYXgiO3M6MjoiMjAiO3M6MzoibWluIjtzOjE6IjMiO31zOjE1OiJtY2VpOHdqYXBmM3B2MzAiO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6MjM6ItmG2KfZhSDYrtin2YbZiNin2K/ar9uMIjtzOjk6ImZpZWxkdHlwZSI7czo1OiJpbnB1dCI7czo1OiJ2YWx1ZSI7czowOiIiO3M6ODoicmVxdWlyZWQiO3M6MToiMSI7czozOiJtYXgiO3M6MjoiNDAiO3M6MzoibWluIjtzOjA6IiI7fXM6MTU6ImtoYjc3ZmRhYXk2YmxtbCI7YTo2OntzOjk6ImZpZWxkbmFtZSI7czoxNzoi2qnZhNmF2Ycg2LnYqNmI2LEiO3M6OToiZmllbGR0eXBlIjtzOjg6InBhc3N3b3JkIjtzOjU6InZhbHVlIjtzOjA6IiI7czo4OiJyZXF1aXJlZCI7czoxOiIxIjtzOjM6Im1heCI7czowOiIiO3M6MzoibWluIjtzOjE6IjYiO31zOjE1OiJobTloaTIyb2tsdnhqbm8iO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6MjU6Itm+2LPYqiDYp9mE2qnYqtix2YjZhtuM2qkiO3M6OToiZmllbGR0eXBlIjtzOjU6ImlucHV0IjtzOjU6InZhbHVlIjtzOjA6IiI7czo4OiJyZXF1aXJlZCI7czoxOiIxIjtzOjM6Im1heCI7czowOiIiO3M6MzoibWluIjtzOjA6IiI7fXM6MTU6InFkMThycGJ3OXZsMGR3NyI7YTo2OntzOjk6ImZpZWxkbmFtZSI7czoxNDoi2KrZiNi224zYrdin2KoiO3M6OToiZmllbGR0eXBlIjtzOjg6InRleHRhcmVhIjtzOjU6InZhbHVlIjtzOjA6IiI7czo4OiJyZXF1aXJlZCI7czoxOiIwIjtzOjM6Im1heCI7czo0OiIxMDAwIjtzOjM6Im1pbiI7czowOiIiO31zOjE1OiJuczNyM3ZjZ21yZXFzZngiO2E6NTp7czo5OiJmaWVsZG5hbWUiO3M6MTk6ItmI2LbYuduM2Kog2KrYp9mH2YQiO3M6OToiZmllbGR0eXBlIjtzOjk6InNlbGVjdGJveCI7czo2OiJwdmFsdWUiO3M6MjA6ItmF2KzYsdivDQrZhdiq2KfZh9mEIjtzOjU6InZhbHVlIjtzOjEwOiLZhdiq2KfZh9mEIjtzOjg6InJlcXVpcmVkIjtzOjE6IjEiO31zOjE1OiJ6c3ZzbDZpa3h3MjZoZjMiO2E6Mzp7czo5OiJmaWVsZG5hbWUiO3M6MTA6Itiz2YfZhduM2YciO3M6OToiZmllbGR0eXBlIjtzOjg6ImNoZWNrYm94IjtzOjY6InB2YWx1ZSI7czo1Njoi2K7Yp9mG2YjYp9iv2Ycg2LTZh9iv2KcNCtis2KfZhtio2KfYsg0K2KjYs9uM2Kwg2YHYudin2YQiO319' );"); 
mysql_query("INSERT INTO `form` VALUES('13' , 'خبرنامه' , '1' , '' , 'اطلاعات با موفیت ارسال شد.' , 'YTozOntzOjE1OiJ0ZWNrYWNtdGI5M212emMiO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6Njoi2YbYp9mFIjtzOjk6ImZpZWxkdHlwZSI7czo1OiJpbnB1dCI7czo1OiJ2YWx1ZSI7czowOiIiO3M6ODoicmVxdWlyZWQiO3M6MToiMSI7czozOiJtYXgiO3M6MDoiIjtzOjM6Im1pbiI7czowOiIiO31zOjE1OiJneDl3ajRtbmZ5N2JtdWsiO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6MjM6ItmG2KfZhSDYrtin2YbZiNin2K/ar9uMIjtzOjk6ImZpZWxkdHlwZSI7czo1OiJpbnB1dCI7czo1OiJ2YWx1ZSI7czowOiIiO3M6ODoicmVxdWlyZWQiO3M6MToiMSI7czozOiJtYXgiO3M6MDoiIjtzOjM6Im1pbiI7czowOiIiO31zOjE1OiJjODI5eHhyMWU3eHF4aGkiO2E6Njp7czo5OiJmaWVsZG5hbWUiO3M6MTQ6Itiu2KjYsdmG2KfZhdmHIjtzOjk6ImZpZWxkdHlwZSI7czo1OiJpbnB1dCI7czo1OiJ2YWx1ZSI7czowOiIiO3M6ODoicmVxdWlyZWQiO3M6MToiMSI7czozOiJtYXgiO3M6MDoiIjtzOjM6Im1pbiI7czowOiIiO319' );");  
mysql_query("CREATE TABLE `formdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `formID` int(10) NOT NULL,
  `value` text NOT NULL,
  `time` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `formdata` VALUES('5' , '12' , 'YTo3OntzOjE1OiJ3MWJrMGJwNHUyZzMyZHQiO2E6Mjp7aTowO3M6Njoi2LHYttinIjtpOjE7czo2OiLZhtin2YUiO31zOjE1OiJtY2VpOHdqYXBmM3B2MzAiO2E6Mjp7aTowO3M6MTY6Iti02KfZh9ix2K7bjNin2YYiO2k6MTtzOjIzOiLZhtin2YUg2K7Yp9mG2YjYp9iv2q/bjCI7fXM6MTU6ImtoYjc3ZmRhYXk2YmxtbCI7YToyOntpOjA7czo2OiIxMjM0NTYiO2k6MTtzOjE3OiLaqdmE2YXZhyDYudio2YjYsSI7fXM6MTU6ImhtOWhpMjJva2x2eGpubyI7YToyOntpOjA7czoxNzoicmFzaGNtc0BnbWFpbC5jb20iO2k6MTtzOjI1OiLZvtiz2Kog2KfZhNqp2KrYsdmI2YbbjNqpIjt9czoxNToicWQxOHJwYnc5dmwwZHc3IjthOjI6e2k6MDtzOjI1OiLYp9uM2YYg24zaqSDYqtiz2Kog2KfYs9iqIjtpOjE7czoxNDoi2KrZiNi224zYrdin2KoiO31zOjE1OiJuczNyM3ZjZ21yZXFzZngiO2E6Mjp7aTowO3M6MTA6ItmF2KrYp9mH2YQiO2k6MTtzOjE5OiLZiNi22LnbjNiqINiq2KfZh9mEIjt9czoxNToienN2c2w2aWt4dzI2aGYzIjthOjI6e2k6MDthOjA6e31pOjE7czoxMDoi2LPZh9mF24zZhyI7fX0=' , '1377277915' , '127.0.0.1/irancms' );"); 
mysql_query("INSERT INTO `formdata` VALUES('6' , '12' , 'YTo3OntzOjE1OiJ3MWJrMGJwNHUyZzMyZHQiO2E6Mjp7aTowO3M6Njoi2LnZhNuMIjtpOjE7czo2OiLZhtin2YUiO31zOjE1OiJtY2VpOHdqYXBmM3B2MzAiO2E6Mjp7aTowO3M6MTA6ItmF2K3Zhdiv24wiO2k6MTtzOjIzOiLZhtin2YUg2K7Yp9mG2YjYp9iv2q/bjCI7fXM6MTU6ImtoYjc3ZmRhYXk2YmxtbCI7YToyOntpOjA7czo2OiIxMjM0NTYiO2k6MTtzOjE3OiLaqdmE2YXZhyDYudio2YjYsSI7fXM6MTU6ImhtOWhpMjJva2x2eGpubyI7YToyOntpOjA7czoxNDoidGVzdEBnbWFpbC5jb20iO2k6MTtzOjI1OiLZvtiz2Kog2KfZhNqp2KrYsdmI2YbbjNqpIjt9czoxNToicWQxOHJwYnc5dmwwZHc3IjthOjI6e2k6MDtzOjI1OiLYp9uM2YYg24zaqSDYqtiz2Kog2KfYs9iqIjtpOjE7czoxNDoi2KrZiNi224zYrdin2KoiO31zOjE1OiJuczNyM3ZjZ21yZXFzZngiO2E6Mjp7aTowO3M6ODoi2YXYrNix2K8iO2k6MTtzOjE5OiLZiNi22LnbjNiqINiq2KfZh9mEIjt9czoxNToienN2c2w2aWt4dzI2aGYzIjthOjI6e2k6MDthOjA6e31pOjE7czoxMDoi2LPZh9mF24zZhyI7fX0=' , '1377279853' , '127.0.0.1/irancms' );"); 
mysql_query("INSERT INTO `formdata` VALUES('7' , '12' , 'YTo3OntzOjE1OiJ3MWJrMGJwNHUyZzMyZHQiO2E6Mjp7aTowO3M6Njoi2LHYttinIjtpOjE7czo2OiLZhtin2YUiO31zOjE1OiJtY2VpOHdqYXBmM3B2MzAiO2E6Mjp7aTowO3M6NToiYWRtaW4iO2k6MTtzOjIzOiLZhtin2YUg2K7Yp9mG2YjYp9iv2q/bjCI7fXM6MTU6ImtoYjc3ZmRhYXk2YmxtbCI7YToyOntpOjA7czoxMzoiZWRzZXNkZnNkZnNkZiI7aToxO3M6MTc6Itqp2YTZhdmHINi52KjZiNixIjt9czoxNToiaG05aGkyMm9rbHZ4am5vIjthOjI6e2k6MDtzOjE3OiJyYXNoY21zQGdtYWlsLmNvbSI7aToxO3M6MjU6Itm+2LPYqiDYp9mE2qnYqtix2YjZhtuM2qkiO31zOjE1OiJxZDE4cnBidzl2bDBkdzciO2E6Mjp7aTowO3M6NzoiZWVydGVydCI7aToxO3M6MTQ6Itiq2YjYttuM2K3Yp9iqIjt9czoxNToibnMzcjN2Y2dtcmVxc2Z4IjthOjI6e2k6MDtzOjEwOiLZhdiq2KfZh9mEIjtpOjE7czoxOToi2YjYtti524zYqiDYqtin2YfZhCI7fXM6MTU6InpzdnNsNmlreHcyNmhmMyI7YToyOntpOjA7YTowOnt9aToxO3M6MTA6Itiz2YfZhduM2YciO319' , '1377281631' , '127.0.0.1/irancms' );"); 
mysql_query("INSERT INTO `formdata` VALUES('8' , '12' , 'YTo3OntzOjE1OiJ3MWJrMGJwNHUyZzMyZHQiO2E6Mjp7aTowO3M6Njoi2LHYttinIjtpOjE7czo2OiLZhtin2YUiO31zOjE1OiJtY2VpOHdqYXBmM3B2MzAiO2E6Mjp7aTowO3M6NToiYWRtaW4iO2k6MTtzOjIzOiLZhtin2YUg2K7Yp9mG2YjYp9iv2q/bjCI7fXM6MTU6ImtoYjc3ZmRhYXk2YmxtbCI7YToyOntpOjA7czoxMzoiZWRzZXNkZnNkZnNkZiI7aToxO3M6MTc6Itqp2YTZhdmHINi52KjZiNixIjt9czoxNToiaG05aGkyMm9rbHZ4am5vIjthOjI6e2k6MDtzOjE3OiJyYXNoY21zQGdtYWlsLmNvbSI7aToxO3M6MjU6Itm+2LPYqiDYp9mE2qnYqtix2YjZhtuM2qkiO31zOjE1OiJxZDE4cnBidzl2bDBkdzciO2E6Mjp7aTowO3M6NzoiZWVydGVydCI7aToxO3M6MTQ6Itiq2YjYttuM2K3Yp9iqIjt9czoxNToibnMzcjN2Y2dtcmVxc2Z4IjthOjI6e2k6MDtzOjEwOiLZhdiq2KfZh9mEIjtpOjE7czoxOToi2YjYtti524zYqiDYqtin2YfZhCI7fXM6MTU6InpzdnNsNmlreHcyNmhmMyI7YToyOntpOjA7YToyOntzOjIzOiLYrtin2YbZiNin2K/ZhyDYtNmH2K/YpyI7czoxOiIxIjtzOjEyOiLYrNin2YbYqNin2LIiO3M6MToiMSI7fWk6MTtzOjEwOiLYs9mH2YXbjNmHIjt9fQ==' , '1377281948' , '127.0.0.1/irancms' );");  
mysql_query("CREATE TABLE `gallery_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `users` int(11) NOT NULL,
  `sub` int(11) NOT NULL,
  `star` int(1) NOT NULL,
  `tvote` int(10) NOT NULL,
  `ajax` tinyint(1) NOT NULL,
  `nov` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('1' , 'عمومی' , 'عمومی' , 'http://127.0.0.1/irancms/files/404.gif' , '2' , '0' , '1' , '11' , '1' , '3' );"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('2' , 'aa' , 'a' , 'aaaa' , '2' , '1' , '1' , '0' , '1' , '0' );"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('3' , 'fff' , 'fff' , 'fff' , '2' , '2' , '1' , '0' , '1' , '0' );"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('4' , 'ttt' , 'ttt' , 'ttt' , '2' , '2' , '1' , '0' , '1' , '0' );"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('5' , 'لل' , 'للل' , 'لل' , '2' , '0' , '1' , '0' , '1' , '0' );"); 
mysql_query("INSERT INTO `gallery_cat` VALUES('6' , 'یبل' , 'ل' , 'یبلبی' , '2' , '4' , '1' , '0' , '1' , '0' );");  
mysql_query("CREATE TABLE `gallery_config` (
  `numcolumns` int(11) NOT NULL,
  `numrows` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `gallery_config` VALUES('4' , '10' );");  
mysql_query("CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `img` varchar(1000) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `users` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `star` int(1) NOT NULL,
  `tvote` int(10) NOT NULL,
  `nov` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `gallery_images` VALUES('2' , 'تست' , 'ششش' , 'http://127.0.0.1/irancms/files/36.jpg' , 'http://127.0.0.1/irancms/files/36.jpg' , '2' , '2' , '3' , '1' , '0' , '0' );"); 
mysql_query("INSERT INTO `gallery_images` VALUES('4' , 'a' , 'a' , 'http://127.0.0.1/irancms/files/404.gif' , 'http://127.0.0.1/irancms/files/404.gif' , '2' , '1' , '7' , '1' , '0' , '0' );");  
mysql_query("CREATE TABLE `jobs` (
  `jobID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `time` varchar(50) NOT NULL,
  PRIMARY KEY (`jobID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `jobs` VALUES('3' , 'تست است تست' , '1381618800' );");  
mysql_query("CREATE TABLE `keys` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET ucs2 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `keys` VALUES('1' , 'تست' );"); 
mysql_query("INSERT INTO `keys` VALUES('2' , 'است' );"); 
mysql_query("INSERT INTO `keys` VALUES('3' , 'تست تگ' );"); 
mysql_query("INSERT INTO `keys` VALUES('4' , 'تگ' );"); 
mysql_query("INSERT INTO `keys` VALUES('5' , 'سیستم' );"); 
mysql_query("INSERT INTO `keys` VALUES('6' , 'پست' );"); 
mysql_query("INSERT INTO `keys` VALUES('7' , '123' );"); 
mysql_query("INSERT INTO `keys` VALUES('8' , 'sdfgh' );"); 
mysql_query("INSERT INTO `keys` VALUES('9' , 'rtyui' );"); 
mysql_query("INSERT INTO `keys` VALUES('10' , 'tyu' );"); 
mysql_query("INSERT INTO `keys` VALUES('11' , 'dtfg' );"); 
mysql_query("INSERT INTO `keys` VALUES('12' , 'reza' );"); 
mysql_query("INSERT INTO `keys` VALUES('13' , 'rash' );"); 
mysql_query("INSERT INTO `keys` VALUES('14' , 'rsh' );"); 
mysql_query("INSERT INTO `keys` VALUES('15' , 'ass' );"); 
mysql_query("INSERT INTO `keys` VALUES('16' , 'hpsas' );"); 
mysql_query("INSERT INTO `keys` VALUES('17' , 'سیش' );"); 
mysql_query("INSERT INTO `keys` VALUES('18' , 'سشیسشیش' );"); 
mysql_query("INSERT INTO `keys` VALUES('19' , 'سشس' );"); 
mysql_query("INSERT INTO `keys` VALUES('20' , 'یش' );"); 
mysql_query("INSERT INTO `keys` VALUES('21' , 'لبی' );"); 
mysql_query("INSERT INTO `keys` VALUES('22' , 'qwe' );"); 
mysql_query("INSERT INTO `keys` VALUES('23' , 'qweqw' );"); 
mysql_query("INSERT INTO `keys` VALUES('24' , 'eqw' );"); 
mysql_query("INSERT INTO `keys` VALUES('25' , 'eqwe' );"); 
mysql_query("INSERT INTO `keys` VALUES('26' , 'qweerwr' );"); 
mysql_query("INSERT INTO `keys` VALUES('27' , 'qqq' );"); 
mysql_query("INSERT INTO `keys` VALUES('28' , 'qqqdfsfs' );"); 
mysql_query("INSERT INTO `keys` VALUES('29' , 'asas' );"); 
mysql_query("INSERT INTO `keys` VALUES('30' , 'asa' );"); 
mysql_query("INSERT INTO `keys` VALUES('31' , 'sas' );"); 
mysql_query("INSERT INTO `keys` VALUES('32' , 'asasa' );"); 
mysql_query("INSERT INTO `keys` VALUES('33' , 'aasaa' );"); 
mysql_query("INSERT INTO `keys` VALUES('34' , 'sass' );"); 
mysql_query("INSERT INTO `keys` VALUES('35' , 'fgfdgdg' );"); 
mysql_query("INSERT INTO `keys` VALUES('36' , 'sda' );"); 
mysql_query("INSERT INTO `keys` VALUES('37' , 'das' );"); 
mysql_query("INSERT INTO `keys` VALUES('38' , 'dasdas' );"); 
mysql_query("INSERT INTO `keys` VALUES('39' , 'dasd' );"); 
mysql_query("INSERT INTO `keys` VALUES('40' , 'asd' );"); 
mysql_query("INSERT INTO `keys` VALUES('41' , 'asdasd' );"); 
mysql_query("INSERT INTO `keys` VALUES('42' , 'ehsan' );"); 
mysql_query("INSERT INTO `keys` VALUES('43' , 'easd;' );"); 
mysql_query("INSERT INTO `keys` VALUES('44' , 'خساسشیت' );"); 
mysql_query("INSERT INTO `keys` VALUES('45' , 'سی' );"); 
mysql_query("INSERT INTO `keys` VALUES('46' , 'سشی' );"); 
mysql_query("INSERT INTO `keys` VALUES('47' , 'شی' );"); 
mysql_query("INSERT INTO `keys` VALUES('48' , 'شسی' );"); 
mysql_query("INSERT INTO `keys` VALUES('49' , 'شس' );"); 
mysql_query("INSERT INTO `keys` VALUES('50' , 'سش' );"); 
mysql_query("INSERT INTO `keys` VALUES('51' , 'یشس' );"); 
mysql_query("INSERT INTO `keys` VALUES('52' , 'یشی' );"); 
mysql_query("INSERT INTO `keys` VALUES('53' , 'شسیش' );"); 
mysql_query("INSERT INTO `keys` VALUES('54' , 'سیشس' );"); 
mysql_query("INSERT INTO `keys` VALUES('55' , 'سسشیسش' );"); 
mysql_query("INSERT INTO `keys` VALUES('56' , 'یشسی' );"); 
mysql_query("INSERT INTO `keys` VALUES('57' , 'شسیشس' );"); 
mysql_query("INSERT INTO `keys` VALUES('58' , 'لس' );"); 
mysql_query("INSERT INTO `keys` VALUES('59' , 'لب' );"); 
mysql_query("INSERT INTO `keys` VALUES('60' , 'یب' );"); 
mysql_query("INSERT INTO `keys` VALUES('61' , 'لا' );"); 
mysql_query("INSERT INTO `keys` VALUES('62' , 'لبا' );"); 
mysql_query("INSERT INTO `keys` VALUES('63' , 'لباب' );"); 
mysql_query("INSERT INTO `keys` VALUES('64' , 'ابل' );"); 
mysql_query("INSERT INTO `keys` VALUES('65' , 'با' );"); 
mysql_query("INSERT INTO `keys` VALUES('66' , 'بل' );"); 
mysql_query("INSERT INTO `keys` VALUES('67' , 'ابلا' );"); 
mysql_query("INSERT INTO `keys` VALUES('68' , 'بب' );"); 
mysql_query("INSERT INTO `keys` VALUES('69' , 'البا' );"); 
mysql_query("INSERT INTO `keys` VALUES('70' , 'لالب' );"); 
mysql_query("INSERT INTO `keys` VALUES('71' , 'الب' );"); 
mysql_query("INSERT INTO `keys` VALUES('72' , 'اسشیاشسعیاشسنیماشسمینلشسیلشسعل' );"); 
mysql_query("INSERT INTO `keys` VALUES('73' , 'برزیل' );"); 
mysql_query("INSERT INTO `keys` VALUES('74' , 'فوتبال' );"); 
mysql_query("INSERT INTO `keys` VALUES('75' , 'جام جهانی' );"); 
mysql_query("INSERT INTO `keys` VALUES('76' , 'علی کریمی' );"); 
mysql_query("INSERT INTO `keys` VALUES('77' , 'بانک' );"); 
mysql_query("INSERT INTO `keys` VALUES('78' , 'بانک مرکزی' );"); 
mysql_query("INSERT INTO `keys` VALUES('79' , 'پیتزا' );"); 
mysql_query("INSERT INTO `keys` VALUES('80' , 'تئاتر فجر' );"); 
mysql_query("INSERT INTO `keys` VALUES('81' , 'تئاتر' );"); 
mysql_query("INSERT INTO `keys` VALUES('82' , 'تگ1' );"); 
mysql_query("INSERT INTO `keys` VALUES('83' , 'تگ12' );"); 
mysql_query("INSERT INTO `keys` VALUES('84' , 'تگ123' );"); 
mysql_query("INSERT INTO `keys` VALUES('85' , 'تک2' );"); 
mysql_query("INSERT INTO `keys` VALUES('86' , 'تک3' );"); 
mysql_query("INSERT INTO `keys` VALUES('87' , 'نظافت' );"); 
mysql_query("INSERT INTO `keys` VALUES('88' , 'احسان' );"); 
mysql_query("INSERT INTO `keys` VALUES('89' , 'sadasd' );"); 
mysql_query("INSERT INTO `keys` VALUES('90' , 'داربست' );");  
mysql_query("CREATE TABLE `keys_join` (
  `key_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `keys_join` VALUES('73' , '14' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('74' , '14' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('75' , '14' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('76' , '14' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('77' , '15' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('78' , '15' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('79' , '16' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('80' , '17' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('81' , '17' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '18' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('83' , '18' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('84' , '18' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '19' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '19' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '19' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '20' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '20' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '20' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '21' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '21' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '21' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '22' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '22' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '22' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '23' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '23' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '23' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '24' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '24' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '24' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '25' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '25' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '25' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '26' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '26' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '26' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '27' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '27' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '27' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '28' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '28' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '28' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '30' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '30' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '30' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '31' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '31' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '31' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('87' , '36' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('88' , '36' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '34' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '34' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '34' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '29' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '29' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '29' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('89' , '37' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '35' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '35' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '35' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '33' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '33' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '33' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('82' , '32' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('85' , '32' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('86' , '32' );"); 
mysql_query("INSERT INTO `keys_join` VALUES('90' , '41' );");  
mysql_query("CREATE TABLE `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `des` text CHARACTER SET utf8 NOT NULL,
  `hits` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `link` VALUES('7' , '[reseller]' , '[resellerurl]' , '[reseller]' , '0' );");  
mysql_query("CREATE TABLE `member` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `prv` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pass` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `yid` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'yahoo id',
  `gid` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'google id',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL,
  `about` text CHARACTER SET utf8 NOT NULL,
  `showname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `tell` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(255) NOT NULL,
  `stat` int(1) NOT NULL DEFAULT '1',
  `city` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `member` VALUES('1' , '80854eff8bccaf29c46e598acc1d8339' , 'مديريت' , 'admin' , 'd93a5def7511da3d0f2d171d9c344e91' , '123' , '127.0.0.1/irancms' , 'info@[resellername].com' , '[resellername]' , '[resellername]@gmail.com' , '7055eced15538bfb7c07f8a5b28fc5d0_1401909011_1158.png' , '' , 'مديريت' , '' , 'FF33CC' , '1' , 'مشهد' );"); 
mysql_query("INSERT INTO `member` VALUES('9' , '' , '' , 'ehsann' , '693cfed9dd8adf7c63afbf53cf3a8043' , '0' , '' , 'mas@yy.com' , '' , '' , '' , '' , '' , '' , '' , '1' , '' );");  
mysql_query("CREATE TABLE `menus` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `oid` int(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  `parent` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `menus` VALUES('2' , '1' , 'newpost' , 'ارسال مطلب' , 'new.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('3' , '2' , 'postmgr' , 'آرشیو مطالب' , 'postmgr.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('4' , '3' , 'comment' , 'نظرات\r\n' , 'comment.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('5' , '4' , 'cat' , 'دسته بندي ها' , 'cat.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('8' , '5' , 'block' , 'مديريت بلوك ها  ' , 'block.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('9' , '7' , 'extra' , 'صفحات اضافي' , 'extra.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('10' , '8' , 'member' , 'لیست اعضا' , 'member.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('11' , '11' , 'inbox' , 'صــنـدوق پـيـام هـا' , 'inbox.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('12' , '10' , 'uc' , 'مدیریت فایل' , 'uc.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('13' , '13' , 'banned' , 'ليست سياه' , 'banned.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('14' , '14' , 'newsletter' , 'خبرنامه' , 'newsletter.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('15' , '15' , 'backup' , 'پشتيبان گيري' , 'backup.php' , '1' , '73' );"); 
mysql_query("INSERT INTO `menus` VALUES('18' , '17' , 'setting' , 'تـنـظـيـمـات سـايـت' , 'setting.php' , '1' , '73' );"); 
mysql_query("INSERT INTO `menus` VALUES('21' , '9' , 'permission' , 'اختيارات كاربران' , 'permission.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('30' , '19' , 'pdf' , 'PDF' , 'plugins.php?plugin=pdf' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('31' , '20' , 'flashnews' , 'اخبار سريع' , 'flashnews.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('41' , '21' , 'menumaker' , 'مدیریت منو' , 'plugins.php?plugin=menumaker' , '0' , '75' );"); 
mysql_query("INSERT INTO `menus` VALUES('42' , '22' , 'thememanager' , 'مدیریت قالب' , 'thememanager.php' , '0' , '75' );"); 
mysql_query("INSERT INTO `menus` VALUES('43' , '22' , 'managefields' , 'مدیریت فیلد های عضویت' , 'managefields.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('44' , '23' , 'managepostfields' , 'مدیریت فیلدهای مطلب' , 'managepostfields.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('46' , '25' , 'slider' , 'اسلایدر' , 'plugins.php?plugin=slider' , '1' , '75' );"); 
mysql_query("INSERT INTO `menus` VALUES('47' , '26' , 'changepassword' , 'تغییر رمز مدیریت' , 'changepassword.php' , '1' , '73' );"); 
mysql_query("INSERT INTO `menus` VALUES('48' , '26' , 'autolinker' , 'تبادل لینک هوشمند' , 'autolinker.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('49' , '26' , 'weather' , 'آب و هوا' , 'plugins.php?plugin=weather' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('50' , '26' , 'gallery' , 'ماژول گالری تصاویر' , 'plugins.php?plugin=gallery' , '0' , '75' );"); 
mysql_query("INSERT INTO `menus` VALUES('54' , '26' , 'sociallink' , 'لینک شبکه های اجتماعی' , 'sociallink.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('55' , '26' , 'site-map' , 'نقشه سايت' , 'plugins.php?plugin=site-map' , '0' , '75' );"); 
mysql_query("INSERT INTO `menus` VALUES('57' , '27' , 'sendpost' , 'مدیریت مطالب کاربران' , 'sendpost.php' , '1' , '71' );"); 
mysql_query("INSERT INTO `menus` VALUES('59' , '28' , 'simplelink' , 'لينكدوني' , 'simplelink.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('66' , '30' , 'form' , 'ایجاد فرم جدید' , 'plugins.php?plugin=form' , '1' , '74' );"); 
mysql_query("INSERT INTO `menus` VALUES('67' , '31' , 'form_12' , 'فرم استخدام' , 'plugins.php?plugin=form&data=12' , '1' , '74' );"); 
mysql_query("INSERT INTO `menus` VALUES('68' , '32' , 'mail' , 'مدیریت ایمیل' , 'plugins.php?plugin=mail' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('69' , '33' , 'form_13' , 'فرم خبرنامه' , 'plugins.php?plugin=form&data=13' , '1' , '74' );"); 
mysql_query("INSERT INTO `menus` VALUES('70' , '1' , 'dashboard' , 'داشبورد' , 'index.php' , '0' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('71' , '2' , 'managenews' , 'مدیریت مطالب' , '' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('72' , '3' , 'managemembers' , 'مدیریت کاربران' , '' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('73' , '4' , 'managesite' , 'مدیریت سیستم' , '' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('74' , '5' , 'formmaker' , 'فرم ها' , '' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('75' , '6' , '6' , 'مدیریت ظاهر' , '' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('76' , '7' , 'plugins' , 'افزونه ها' , 'plugin.php' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('77' , '33' , 'poll' , 'نظرسنجی' , 'plugins.php?plugin=poll' , '0' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('78' , '0' , 'adduser' , 'افزودن کاربر' , 'adduser.php' , '1' , '72' );"); 
mysql_query("INSERT INTO `menus` VALUES('90' , '34' , 'importer' , 'blog importer' , 'plugins.php?plugin=importer' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('93' , '35' , 'ads' , 'تبلیغات' , 'plugins.php?plugin=ads' , '1' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('94' , '35' , 'eshop' , 'فروشگاه الکترونیک' , 'plugins.php?plugin=eshop' , '0' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('95' , '36' , 'eshop' , 'محصولات' , 'plugins.php?plugin=eshop&ac=product' , '0' , '94' );"); 
mysql_query("INSERT INTO `menus` VALUES('96' , '37' , 'eshop' , 'دسته ها' , 'plugins.php?plugin=eshop&ac=category' , '0' , '94' );"); 
mysql_query("INSERT INTO `menus` VALUES('97' , '38' , 'eshop' , 'سفارشات' , 'plugins.php?plugin=eshop&ac=orders' , '0' , '94' );"); 
mysql_query("INSERT INTO `menus` VALUES('98' , '38' , 'shop' , 'فروشگاه' , 'plugins.php?plugin=shop' , '0' , '0' );"); 
mysql_query("INSERT INTO `menus` VALUES('99' , '39' , 'shop' , 'محصولات' , 'plugins.php?plugin=shop&ac=product' , '0' , '98' );"); 
mysql_query("INSERT INTO `menus` VALUES('100' , '40' , 'shop' , 'دسته ها' , 'plugins.php?plugin=shop&ac=category' , '0' , '98' );"); 
mysql_query("INSERT INTO `menus` VALUES('101' , '41' , 'shop' , 'سفارشات' , 'plugins.php?plugin=shop&ac=orders' , '0' , '98' );");  
mysql_query("CREATE TABLE `mp_form` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `mp_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `mp_gallery` VALUES('4' , '3' , '' , '' , 'http://127.0.0.1/irancms/files/404.gif' , '' );"); 
mysql_query("INSERT INTO `mp_gallery` VALUES('5' , '1' , '' , '' , 'http://127.0.0.1/irancms/files/404.gif' , '' );"); 
mysql_query("INSERT INTO `mp_gallery` VALUES('7' , '4' , '' , '' , 'http://127.0.0.1/irancms/files/waterfall-baby.jpg' , 'http://127.0.0.1/irancms/files/thumbs/85c406dd34e0462e6eb1df010e740743.jpg' );"); 
mysql_query("INSERT INTO `mp_gallery` VALUES('11' , '2' , '' , '' , 'http://127.0.0.1/irancms/files/404.gif' , 'http://127.0.0.1/irancms/files/thumbs/bb18336fe5a1d022318fc41b02f2a034.gif' );");  
mysql_query("CREATE TABLE `mp_slider` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `mp_slider` VALUES('1' , '1' , 'aaaaaaaa' , 'تست تصویر یک توضیح' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s1.jpg' , '' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s1.jpg' );"); 
mysql_query("INSERT INTO `mp_slider` VALUES('2' , '3' , 'bbbb' , '' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s2.jpg' , '' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s2.jpg' );"); 
mysql_query("INSERT INTO `mp_slider` VALUES('3' , '2' , 'تست است' , 'این یک تست است' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s4.jpg' , '' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s4.jpg' );"); 
mysql_query("INSERT INTO `mp_slider` VALUES('13' , '6' , 'salam' , 'test' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s3.jpg' , '' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s3.jpg' );"); 
mysql_query("INSERT INTO `mp_slider` VALUES('14' , '5' , 'sadasda' , 'dsadasd' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s5.jpg' , 'asdasdasd' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s5.jpg' );"); 
mysql_query("INSERT INTO `mp_slider` VALUES('15' , '4' , 'sadasda' , 'dsadasd' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s6.jpg' , 'asdasdasd' , 'http://127.0.0.1/irancms/theme/core/fh3/images/s6.jpg' );");  
mysql_query("CREATE TABLE `msg` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `reade` int(3) NOT NULL DEFAULT '0',
  `send_id` int(11) NOT NULL,
  `re_id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `msg` VALUES('2' , '1' , '1' , '1' , 'با سلام و احترام\r\n			مطلب &#39;تست ارسال مطلب کاربران&#39; توسط مدیریت تایید و ثبت شد.\r\n			لینک مطلب :\r\n			http://127.0.0.1/irancms/post/32-تست+ارسال+مطلب+کاربران.php\r\n			سپاس' , 'مطلب ارسالی شما مورد تایید واقع شد.' );");  
mysql_query("CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) CHARACTER SET utf8 NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `news` VALUES('3' , 'test.co' , 'تست است' );");  
mysql_query("CREATE TABLE `nl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `nl` VALUES('4' , 'rashcms@gmail.com' );"); 
mysql_query("INSERT INTO `nl` VALUES('5' , 'maskmr85@gmail.com' );"); 
mysql_query("INSERT INTO `nl` VALUES('6' , 'demo@demo.com' );");  
mysql_query("CREATE TABLE `nls` (
  `SmtpHost` varchar(255) NOT NULL,
  `SmtpUser` varchar(255) NOT NULL,
  `SmtpPassword` varchar(255) NOT NULL,
  `mailperpack` varchar(255) NOT NULL DEFAULT '20',
  `msperpack` varchar(255) NOT NULL DEFAULT '10'
) ENGINE=MyISAM DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `nls` VALUES('' , '' , '' , '' , '' );");  
mysql_query("CREATE TABLE `ns_menu` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `class` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `ns_menu` VALUES('1' , '0' , 'صفحه اصلی' , 'http://127.0.0.1/irancms/' , 'http://127.0.0.1/irancms/files/icons/24/home.png' , '1' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('3' , '39' , 'طراحی سایت' , 'http://127.0.0.1/irancms/contact.html' , 'http://127.0.0.1/irancms/files/icons/16/world.png' , '1' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('36' , '0' , 'خدمات' , '' , 'http://127.0.0.1/irancms/files/icons/24/config.png' , '2' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('37' , '0' , 'تماس باما' , 'ret' , 'http://127.0.0.1/irancms/files/icons/24/customers.png' , '5' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('38' , '36' , 'پنل اس ام اس' , 'ret' , 'http://127.0.0.1/irancms/files/icons/16/ipod_cast.png' , '1' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('39' , '45' , 'محصولات' , 'fgh' , 'http://127.0.0.1/irancms/files/icons/24/attibutes.png' , '1' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('40' , '0' , 'درخواست نمایندگی' , 'sdfsdff' , 'http://127.0.0.1/irancms/files/icons/24/current-work.png' , '4' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('41' , '36' , 'درباره ما' , 'sdfsfsdf' , 'http://127.0.0.1/irancms/files/icons/24/v-card.png' , '2' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('42' , '0' , 'مقالات' , 'sdfsfsdf' , 'http://127.0.0.1/irancms/files/icons/24/consulting.png' , '3' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('45' , '1' , 'test' , 'http://127.0.0.1/irancms/post/36-test.php' , '' , '1' , '1' );"); 
mysql_query("INSERT INTO `ns_menu` VALUES('52' , '36' , 'تکنولوژی' , 'http://127.0.0.1/irancms/post/39-تکنولوژی.php' , '' , '3' , '1' );");  
mysql_query("CREATE TABLE `ns_menu_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `style` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `ns_menu_group` VALUES('1' , 'صفحه اصلي' , 'menumaker_default' );");  
mysql_query("CREATE TABLE `onlines` (
  `time` int(11) NOT NULL,
  `session` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `onlines` VALUES('1448982722' , '127.0.0.1' );");  
mysql_query("CREATE TABLE `permissions` (
  `u_id` int(11) NOT NULL,
  `newpost` int(1) NOT NULL DEFAULT '0',
  `editotherposts` int(1) NOT NULL DEFAULT '0',
  `backup` int(1) NOT NULL,
  `access_admin_area` int(1) NOT NULL DEFAULT '0',
  `ads` int(11) NOT NULL DEFAULT '0',
  `editposts` int(1) NOT NULL DEFAULT '0',
  `postmgr` int(1) NOT NULL DEFAULT '0',
  `comment` int(1) NOT NULL DEFAULT '0',
  `cat` int(1) NOT NULL DEFAULT '0',
  `simplelink` int(1) NOT NULL DEFAULT '0',
  `block` int(1) NOT NULL DEFAULT '0',
  `extra` int(1) NOT NULL DEFAULT '0',
  `member` int(1) NOT NULL DEFAULT '0',
  `inbox` int(1) NOT NULL DEFAULT '0',
  `uc` int(1) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `newsletter` int(1) NOT NULL DEFAULT '0',
  `poll` int(1) NOT NULL DEFAULT '0',
  `theme` int(1) NOT NULL DEFAULT '0',
  `setting` int(1) NOT NULL DEFAULT '0',
  `permission` int(1) NOT NULL,
  `module` int(1) NOT NULL DEFAULT '1',
  `changeindex` int(1) NOT NULL DEFAULT '0',
  `pdf` int(1) NOT NULL DEFAULT '0',
  `flashnews` int(1) NOT NULL DEFAULT '1',
  `filemanager` int(1) NOT NULL DEFAULT '0',
  `thememanager` int(1) NOT NULL DEFAULT '0',
  `rssreader` int(1) NOT NULL DEFAULT '0',
  `contact` int(1) NOT NULL,
  `managefields` int(1) NOT NULL DEFAULT '0',
  `managepostfields` int(1) NOT NULL DEFAULT '0',
  `gallery` int(1) NOT NULL DEFAULT '0',
  `changepassword` int(1) NOT NULL DEFAULT '1',
  `slider` int(1) NOT NULL DEFAULT '0',
  `form` int(1) NOT NULL DEFAULT '0',
  `mail` int(1) NOT NULL DEFAULT '0',
  `counter` int(10) DEFAULT '1',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `permissions` VALUES('1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '0' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' , '1' );");  
mysql_query("CREATE TABLE `plugins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `stat` int(1) NOT NULL,
  `options` text NOT NULL,
  `sortable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `plugins` VALUES('1' , 'author' , 'نويسنگان' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('3' , 'extra' , 'صفحات اضافي' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('4' , 'counter' , 'شمارنده' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('5' , 'cat' , 'موضوعات' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('6' , 'newsletter' , 'خبرنامه' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('7' , 'contact' , 'ارتباط با ما' , '0' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('8' , 'member' , 'سيستم كاربري' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('9' , 'module' , 'ماژول ها' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('13' , 'changeindex' , 'تغییر صفحه اصلی' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('14' , 'search' , 'جستجو' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('20' , 'pdf' , 'PDF' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('22' , 'recent' , 'آخرين مطالب سايت' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('23' , 'hots' , 'محبوب ترین مطالب' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('24' , 'flashnews' , 'اخبار سريع' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('25' , 'recentcomments' , 'آخرين نظرات سايت' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('27' , 'recentmembers' , 'آخرین اعضا' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('35' , 'menumaker' , 'منوساز' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('37' , 'recentpostbycat' , 'آخرین ارسال های دسته بندی' , '1' , 'YToxOntzOjU6InRoZW1lIjtiOjE7fQ==' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('39' , 'autolinker' , 'تبادل لینک هوشمند' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('40' , 'related' , 'مطالب مشابه' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('41' , 'monthlyarchive' , 'آرشیو ماهانه' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('43' , 'weather' , 'آب و هوا' , '1' , 'YTo0OTp7czo2OiJBYmFkYW4iO3M6ODoiSVJYWDAwMDEiO3M6MTA6IkF6YXIgU2hhaHIiO3M6ODoiSVJYWDAwMDIiO3M6NzoiRXNmYWhhbiI7czo4OiJJUlhYMDAwMyI7czoxMDoiRXNsYW1zaGFociI7czo4OiJJUlhYMDAwNCI7czo3OiJLYW5hdmlzIjtzOjg6IklSWFgwMDA1IjtzOjU6IkthcmFqIjtzOjg6IklSWFgwMDA2IjtzOjEwOiJNYXJ2IERhc2h0IjtzOjg6IklSWFgwMDA3IjtzOjc6Ik1hc2hoYWQiO3M6ODoiSVJYWDAwMDgiO3M6NjoiTWVocml6IjtzOjg6IklSWFgwMDA5IjtzOjk6Ik5hamFmYWJhZCI7czo4OiJJUlhYMDAxMCI7czo0OiJPc2t1IjtzOjg6IklSWFgwMDExIjtzOjEwOiJQZXJzZXBvbGlzIjtzOjg6IklSWFgwMDEyIjtzOjc6IlFvbXNoZWgiO3M6ODoiSVJYWDAwMTMiO3M6MzoiUmV5IjtzOjg6IklSWFgwMDE0IjtzOjY6IlNoaXJheiI7czo4OiJJUlhYMDAxNSI7czo2OiJUYWJyaXoiO3M6ODoiSVJYWDAwMTYiO3M6NDoiVGFmdCI7czo4OiJJUlhYMDAxNyI7czo2OiJUZWhyYW4iO3M6ODoiSVJYWDAwMTgiO3M6NDoiWWF6ZCI7czo4OiJJUlhYMDAxOSI7czo3OiJaYWhlZGFuIjtzOjg6IklSWFgwMDIwIjtzOjY6IlphcmdhbiI7czo4OiJJUlhYMDAyMSI7czo0OiJLaG95IjtzOjg6IklSWFgwMDIyIjtzOjc6Ik9ydW1pZWgiO3M6ODoiSVJYWDAwMjMiO3M6NjoiWmFuamFuIjtzOjg6IklSWFgwMDI0IjtzOjY6IlJhbXNhciI7czo4OiJJUlhYMDAyNSI7czo4OiJCYWJ1bHNhciI7czo4OiJJUlhYMDAyNiI7czo4OiJTYWJ6ZXZhciI7czo4OiJJUlhYMDAyNyI7czoxNjoiVG9yYmF0LUhleWRhcmllaCI7czo4OiJJUlhYMDAyOCI7czoxMDoiS2VybWFuc2hhaCI7czo4OiJJUlhYMDAyOSI7czo0OiJBcmFrIjtzOjg6IklSWFgwMDMwIjtzOjc6IkJpcmphbmQiO3M6ODoiSVJYWDAwMzEiO3M6NToiQWh3YXoiO3M6ODoiSVJYWDAwMzIiO3M6MTI6IkJhbmRhcmFiYmFzcyI7czo4OiJJUlhYMDAzMyI7czo2OiJLZXJtYW4iO3M6ODoiSVJYWDAwMzQiO3M6ODoiSGVsZXlsYWgiO3M6ODoiSVJYWDAwMzUiO3M6NDoiRmFzYSI7czo4OiJJUlhYMDAzNiI7czoxMjoiRG93IEdvbmJhZGFuIjtzOjg6IklSWFgwMDM3IjtzOjE1OiJNYXNqZWQgU29sZXltYW4iO3M6ODoiSVJYWDAwMzgiO3M6ODoiT21pZGl5ZWgiO3M6ODoiSVJYWDAwMzkiO3M6MTc6IkJhbmRhci1FIE1haHNoYWhyIjtzOjg6IklSWFgwMDQwIjtzOjc6IkJvam51cmQiO3M6ODoiSVJYWDAwNDIiO3M6MTU6IkJhbmRhci1FIEFuemFsaSI7czo4OiJJUlhYMDA0MyI7czo2OiJHb3JnYW4iO3M6ODoiSVJYWDAwNDQiO3M6NjoiTWFzaGVoIjtzOjg6IklSWFgwMDQ1IjtzOjU6Illhc3VqIjtzOjg6IklSWFgwMDQ2IjtzOjEwOiJTaGFocmVrb3JkIjtzOjg6IklSWFgwMDQ3IjtzOjc6IkhhbWVkYW4iO3M6ODoiSVJYWDAwNDgiO3M6ODoiTWFyYWdoZWgiO3M6ODoiSVJYWDAwNDkiO3M6MTE6Iktob3JyYW1hYmFkIjtzOjg6IklSWFgwMDUwIjt9' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('44' , 'gallery' , 'ماژول گالری تصاویر' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('48' , 'sociallink' , 'لینک های شبکه های اجتماعی' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('49' , 'site-map' , 'نقشه سايت' , '9' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('51' , 'sendpost' , 'ارسال مطلب توسط كاربران' , '9' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('52' , 'gllastpic' , 'گالری تصاویر - آخرین تصویر' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('53' , 'slider' , 'اسلایدر' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('55' , 'random' , 'مطالب تصادفی' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('57' , 'simplelink' , 'لينكدوني' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('60' , 'chat' , 'پشتیبانی آنلاین' , '0' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('62' , 'form' , 'فرم ساز' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('63' , 'mail' , 'مدیریت ایمیل' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('64' , 'jobs' , 'وظایف و کارها' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('66' , 'rssreader' , 'خبرخوان' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('67' , 'mostcomments' , 'پر بحث ترین' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('68' , 'mostviewed' , 'پربازدید ترین مطالب' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('69' , 'poll' , 'نظرسنجی' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('70' , 'quickblock' , 'بلوک سریع' , '1' , '' , '1' );"); 
mysql_query("INSERT INTO `plugins` VALUES('71' , 'importer' , 'blog importer' , '9' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('72' , 'events' , 'رخدادها' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('75' , 'ads' , 'تبلیغات' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('76' , 'eshop' , 'فروشگاه اینترنتی' , '1' , '' , '0' );"); 
mysql_query("INSERT INTO `plugins` VALUES('77' , 'shop' , 'فروشگاه' , '1' , '' , '0' );");  
mysql_query("CREATE TABLE `pollanswers` (
  `answerID` int(10) NOT NULL AUTO_INCREMENT,
  `pollID` int(11) DEFAULT NULL,
  `answer` varchar(250) DEFAULT NULL,
  `hits` int(10) DEFAULT '0',
  PRIMARY KEY (`answerID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `pollanswers` VALUES('1' , '1' , 'بله' , '500' );"); 
mysql_query("INSERT INTO `pollanswers` VALUES('2' , '1' , 'حتما' , '60' );"); 
mysql_query("INSERT INTO `pollanswers` VALUES('3' , '1' , 'باید بدهد' , '52' );");  
mysql_query("CREATE TABLE `polls` (
  `pollID` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(250) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`pollID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `polls` VALUES('1' , 'آیا احسان پول می دهد ؟' , 'active' );");  
mysql_query("CREATE TABLE `positions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `value` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2067 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `positions` VALUES('2066' , '4' , 'newsletter' , 'YToxOntzOjEwOiJwbHVnaW5EYXRhIjthOjI6e3M6NToidGl0bGUiO3M6MTU6Itiu2KjYsSDZhtin2YXZhyI7czo0OiJpY29uIjtzOjA6IiI7fX0=' , 'first' );");  
mysql_query("CREATE TABLE `postfields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `orderid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `postfields` VALUES('1' , 'textbox' , 'تکست باکس' , 'input' , '2' );"); 
mysql_query("INSERT INTO `postfields` VALUES('2' , 'image' , 'عکس' , 'image' , '1' );"); 
mysql_query("INSERT INTO `postfields` VALUES('3' , 'textarea' , 'توضیج کوتاه' , 'textarea' , '3' );"); 
mysql_query("INSERT INTO `postfields` VALUES('4' , 'Asdasd' , 'سشیشسیشسیشی' , 'image' , '4' );"); 
mysql_query("INSERT INTO `postfields` VALUES('5' , 'Asasdaddasd' , 'asdasdasd' , 'image' , '5' );");  
mysql_query("CREATE TABLE `product_categories` (
  `categoryID` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `productCount` int(5) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `parentID` int(3) NOT NULL DEFAULT '0',
  `afterPayment` text NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `product_categories` VALUES('9' , 'دسته بندی تست' , 'دسته بندی تست' , '0' , 'http://127.0.0.1/irancms/files/arrow.png' , '9' , '&lt;p&gt;توضیحات پس از خرید محصول&nbsp;تست&lt;/p&gt;\r\n&lt;div id=&quot;\\&quot;mcePasteBin\\&quot;&quot; contenteditable=&quot;\\&quot;false\\&quot;&quot; hidden=&quot;&quot;&gt;\r\n&lt;div contenteditable=&quot;\\&quot;true\\&quot;&quot;&gt;توضیحات پس از خرید محصول&lt;/div&gt;\r\n&lt;/div&gt;' );"); 
mysql_query("INSERT INTO `product_categories` VALUES('11' , 'maskmr85@gmail.com' , 'توضیحات پس از خرید محصول' , '0' , '' , '0' , '&lt;p&gt;توضیحات پس از خرید محصول&lt;/p&gt;' );"); 
mysql_query("INSERT INTO `product_categories` VALUES('12' , 'دسته بندی تست' , 'dfgdgdfgdfg' , '0' , 'http://127.0.0.1/irancms/files/arrow.png' , '0' , '&lt;p&gt;fdgdgfd&lt;/p&gt;' );");  
mysql_query("CREATE TABLE `product_fields` (
  `productFeildID` bigint(20) NOT NULL AUTO_INCREMENT,
  `productID` int(10) NOT NULL,
  `fieldID` int(10) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`productFeildID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `product_fields` VALUES('9' , '12' , '4' , 'fsdf' );"); 
mysql_query("INSERT INTO `product_fields` VALUES('11' , '13' , '2' , 'dsfdsfsf' );"); 
mysql_query("INSERT INTO `product_fields` VALUES('12' , '14' , '2' , 'محصول تست  333333333333333333333' );");  
mysql_query("CREATE TABLE `product_images` (
  `imageID` int(10) NOT NULL AUTO_INCREMENT,
  `imageUrl` varchar(255) NOT NULL,
  `productID` int(10) NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `product_images` VALUES('27' , 'http://127.0.0.1/irancms/files/adsa_490x312.jpg' , '10' );"); 
mysql_query("INSERT INTO `product_images` VALUES('29' , 'http://127.0.0.1/irancms/files/sample/00940484.jpg' , '11' );"); 
mysql_query("INSERT INTO `product_images` VALUES('30' , 'http://127.0.0.1/irancms/files/news/LG_1401712055_c4a20c6f06c87a0463c6df65e8933e3c.jpg' , '12' );"); 
mysql_query("INSERT INTO `product_images` VALUES('31' , 'http://127.0.0.1/irancms/files/sample/00992484.jpg' , '13' );"); 
mysql_query("INSERT INTO `product_images` VALUES('33' , 'http://127.0.0.1/irancms/files/bg-cyan.jpg' , '14' );");  
mysql_query("CREATE TABLE `products` (
  `productID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(100) NOT NULL,
  `category` int(3) NOT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `stock` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `products` VALUES('10' , 'محصول تasadasdست' , '&lt;p&gt;محصول تasadasdست&lt;/p&gt;' , '123323' , '11' , '0' , '2323' );"); 
mysql_query("INSERT INTO `products` VALUES('11' , 'محصول تasadasdست' , '&lt;p&gt;محصول تasadasdست&lt;/p&gt;' , '123323' , '11' , '0' , '2323' );"); 
mysql_query("INSERT INTO `products` VALUES('12' , 'محصول تستffffffffffffffffffffffff' , '&lt;p&gt;محصول تستffffffffffffffffffffffff&lt;/p&gt;' , '123323' , '9' , '0' , '324234' );"); 
mysql_query("INSERT INTO `products` VALUES('13' , 'محصول تست  4' , '&lt;p&gt;dsfsfsfsf&lt;/p&gt;' , '2234234' , '12' , '0' , '2323' );"); 
mysql_query("INSERT INTO `products` VALUES('14' , 'محصول تست  333333333333333333333محصول تست  333333333333333333333' , '&lt;p&gt;محصول تست&nbsp; 333333333333333333333&lt;/p&gt;' , '123323' , '12' , '0' , '324234' );");  
mysql_query("CREATE TABLE `redirects` (
  `name` varchar(255) NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `redirects` VALUES('contact' , 'contact.html' , 'plugins=contact' );"); 
mysql_query("INSERT INTO `redirects` VALUES('monthlyarchive' , 'archive/([0-9]+)/([0-9]+).php' , 'plugins=monthlyarchive&year=\\1&month=\\2' );"); 
mysql_query("INSERT INTO `redirects` VALUES('pageslinks' , 'page/([0-9]+)-(.+).php' , 'plugins=extra&id=\\1' );"); 
mysql_query("INSERT INTO `redirects` VALUES('plugins' , 'plugin/([A-Za-z0-9]+)(/(.*))?' , 'plugins=\\1&\\3' );"); 
mysql_query("INSERT INTO `redirects` VALUES('post' , 'post/([0-9]+)-(.*).php' , 'plugins=cat&pid=\\1' );"); 
mysql_query("INSERT INTO `redirects` VALUES('profile' , 'profile/([a-zA-z0-9]+)' , 'plugins=member&method=profile&user=\\1' );"); 
mysql_query("INSERT INTO `redirects` VALUES('subcat' , 'cat/([0-9]+)-(.*).php' , 'plugins=cat&catid=\\1' );"); 
mysql_query("INSERT INTO `redirects` VALUES('tags' , 'tag/(.+)' , 'plugins=cat&tag=\\1' );");  
mysql_query("CREATE TABLE `shopsearch` (
  `searchID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`searchID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");  
mysql_query("CREATE TABLE `sociallink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `icon` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1"); 
mysql_query("INSERT INTO `sociallink` VALUES('1' , 'گوگل پلاس' , 'http://plus.google.com/' , 'http://127.0.0.1/irancms/files/logos/gplus.png' );"); 
mysql_query("INSERT INTO `sociallink` VALUES('2' , 'فیس بوک' , 'http://facebook.com' , 'http://127.0.0.1/irancms/files/logos/facebook.png' );"); 
mysql_query("INSERT INTO `sociallink` VALUES('3' , 'توییتر' , 'http://twitter.com' , 'http://127.0.0.1/irancms/files/logos/twitter.png' );");  
mysql_query("CREATE TABLE `themearchive` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8"); 
mysql_query("INSERT INTO `themearchive` VALUES('3' , 'master-firoze' , 'back_5117_1401957539' , '1401957539' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('6' , 'master-blue' , 'back_65337_1401972932' , '1401972932' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('7' , 'familweb default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1429788860' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('10' , 'video default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1439482566' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('12' , 'tiab default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1447162846' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('14' , 'imusic default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1448030385' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('15' , 'blue default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1448620571' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('16' , 'master-fh3' , 'back_105176_1448804614' , '1448804614' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('18' , 'master-deashura' , 'back_141876_1448893659' , '1448893659' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('21' , 'master-demahdi' , 'back_151720_1448894422' , '1448894422' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('23' , 'master-depiyambar' , 'back_58602_1448894861' , '1448894861' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('24' , 'deashura default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1448894884' );"); 
mysql_query("INSERT INTO `themearchive` VALUES('25' , 'deneda default' , 'eyJwb3NpdGlvbnMiOlt7ImlkIjoiNDMiLCJwaWQiOiIyIiwidmFsdWUiOiJtZW1iZXIiLCJkYXRhIjoiWVRveE9udHpPakV3T2lKd2JIVm5hVzVFWVhSaElqdGhPakk2ZTNNNk5Ub2lkR2wwYkdVaU8zTTZNak02SXRpejI0ellzOWlxMllVZzJxbllwOWl4MktqWXNkdU1JanR6T2pRNkltbGpiMjRpTzNNNk1Eb2lJanQ5ZlE9PSIsInRoZW1' , '1448901575' );");  
?>