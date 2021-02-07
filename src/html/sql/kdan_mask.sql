CREATE TABLE IF NOT EXISTS `kdan_mask_mask_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ,
  `uuid` varchar(64) NOT NULL ,
  `pharmaciesId` int(11) unsigned NOT NULL ,
  `name` varchar(64) NOT NULL ,
  `color` varchar(16) NOT NULL ,
  `per` int(11) unsigned NOT NULL ,
  `price` float unsigned NOT NULL ,
  `fullName` varchar(64) NOT NULL DEFAULT '' ,
  `isDelete` tinyint(1) NOT NULL DEFAULT '0' ,
  `modifyTime` datetime NOT NULL ,
  `createTime` datetime NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_uuid_pharmaciesId_cloro_per` (`id`,`uuid`,`pharmaciesId`,`color`,`per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_pharmacies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) NOT NULL COMMENT 'uuid',
  `name` varchar(64) NOT NULL,
  `cashBalance` float unsigned NOT NULL DEFAULT '0',
  `MonTimeStart` time DEFAULT NULL ,
  `MonTimeEnd` time DEFAULT NULL ,
  `TueTimeStart` time DEFAULT NULL ,
  `TueTimeEnd` time DEFAULT NULL ,
  `WedTimeStart` time DEFAULT NULL ,
  `WedTimeEnd` time DEFAULT NULL,
  `ThuTimeStart` time DEFAULT NULL ,
  `ThuTimeEnd` time DEFAULT NULL ,
  `FriTimeStart` time DEFAULT NULL ,
  `FriTimeEnd` time DEFAULT NULL ,
  `SatTimeStart` time DEFAULT NULL ,
  `SatTimeEnd` time DEFAULT NULL ,
  `SunTimeStart` time DEFAULT NULL ,
  `SunTimeEnd` time DEFAULT NULL ,
  `modifyTime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_sell_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ,
  `userId` int(11) unsigned NOT NULL ,
  `pharmaciesId` int(11) unsigned NOT NULL ,
  `name` varchar(64) NOT NULL ,
  `color` varchar(11) NOT NULL ,
  `per` int(6) unsigned NOT NULL ,
  `sellAmount` float unsigned NOT NULL ,
  `sellDate` datetime NOT NULL ,
  `createTime` datetime NOT NULL   ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) NOT NULL ,
  `name` varchar(64) NOT NULL ,
  `cashBalance` float unsigned NOT NULL DEFAULT '0' ,
  `modifyTime` datetime NOT NULL ,
  `createTime` datetime NOT NULL ,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_api_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ,
  `action` varchar(64) NOT NULL ,
  `ip` varchar(64) NOT NULL ,
  `request` text NOT NULL ,
  `response` text NOT NULL ,
  `status` tinyint(1) NOT NULL ,
  `createTime` datetime NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
