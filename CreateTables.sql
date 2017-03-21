CREATE TABLE `events` (
  `eventId` char(64) COLLATE utf8_bin NOT NULL,
  `aggregateId` char(64) COLLATE utf8_bin NOT NULL,
  `timestamp` float(18,4) NOT NULL,
  `eventClassName` char(255) COLLATE utf8_bin NOT NULL,
  `eventData` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`aggregateId`,`eventId`),
  KEY `TSTAMP` (`timestamp`),
  KEY `ENAME` (`eventClassName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
