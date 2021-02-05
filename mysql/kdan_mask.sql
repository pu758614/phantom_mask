

CREATE TABLE IF NOT EXISTS `kdan_mask_mask_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `uuid` varchar(64) NOT NULL COMMENT 'uuid',
  `pharmaciesId` int(11) unsigned NOT NULL COMMENT '藥局id',
  `name` varchar(64) NOT NULL COMMENT '名稱',
  `color` varchar(16) NOT NULL COMMENT '顏色',
  `per` int(11) unsigned NOT NULL COMMENT '幾片裝',
  `price` float unsigned NOT NULL COMMENT '價格',
  `fullName` varchar(64) NOT NULL DEFAULT '' COMMENT '完整名稱',
  `isDelete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已刪除',
  `modifyTime` datetime NOT NULL COMMENT '異動時間',
  `createTime` datetime NOT NULL COMMENT '建立時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_uuid_pharmaciesId_cloro_per` (`id`,`uuid`,`pharmaciesId`,`color`,`per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_pharmacies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) NOT NULL COMMENT 'uuid',
  `name` varchar(64) NOT NULL,
  `cashBalance` float unsigned NOT NULL DEFAULT '0',
  `MonTimeStart` time DEFAULT NULL COMMENT '禮拜一開始時間',
  `MonTimeEnd` time DEFAULT NULL COMMENT '禮拜一結束時間',
  `TueTimeStart` time DEFAULT NULL COMMENT '禮拜二開始時間',
  `TueTimeEnd` time DEFAULT NULL COMMENT '禮拜二結束時間',
  `WedTimeStart` time DEFAULT NULL COMMENT '禮拜三開始時間',
  `WedTimeEnd` time DEFAULT NULL COMMENT '禮拜三結束時間',
  `ThuTimeStart` time DEFAULT NULL COMMENT '禮拜四開始時間',
  `ThuTimeEnd` time DEFAULT NULL COMMENT '禮拜四結束時間',
  `FriTimeStart` time DEFAULT NULL COMMENT '禮拜五開始時間',
  `FriTimeEnd` time DEFAULT NULL COMMENT '禮拜五結束時間',
  `SatTimeStart` time DEFAULT NULL COMMENT '禮拜六開始時間',
  `SatTimeEnd` time DEFAULT NULL COMMENT '禮拜六結束時間',
  `SunTimeStart` time DEFAULT NULL COMMENT '禮拜日開始時間',
  `SunTimeEnd` time DEFAULT NULL COMMENT '禮拜日結束時間',
  `modifyTime` datetime NOT NULL COMMENT '異動時間',
  `createTime` datetime NOT NULL   COMMENT '建立時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_sell_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `userId` int(11) unsigned NOT NULL COMMENT '使用者id',
  `pharmaciesId` int(11) unsigned NOT NULL COMMENT '藥房id',
  `name` varchar(64) NOT NULL COMMENT '口罩名稱',
  `color` varchar(11) NOT NULL COMMENT '口罩顏色',
  `per` int(6) unsigned NOT NULL COMMENT '口罩幾片裝',
  `sellAmount` float unsigned NOT NULL COMMENT '口罩價格',
  `sellDate` datetime NOT NULL COMMENT '購買時間',
  `createTime` datetime NOT NULL   COMMENT '建立時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `uuid` varchar(64) NOT NULL COMMENT 'uuid',
  `name` varchar(64) NOT NULL COMMENT '名稱',
  `cashBalance` float unsigned NOT NULL DEFAULT '0' COMMENT '錢包餘額',
  `modifyTime` datetime NOT NULL COMMENT '異動時間',
  `createTime` datetime NOT NULL COMMENT '建立時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `kdan_mask_api_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `action` varchar(64) NOT NULL COMMENT 'api類型',
  `ip` varchar(64) NOT NULL COMMENT '來源ip',
  `request` text NOT NULL COMMENT '請求資料',
  `response` text NOT NULL COMMENT '回應資料',
  `status` tinyint(1) NOT NULL COMMENT '狀態',
  `createTime` datetime NOT NULL COMMENT '建立時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
