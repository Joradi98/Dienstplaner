# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.9)
# Datenbank: dienstplaner
# Erstellt am: 2017-09-18 11:27:27 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle hilfe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hilfe`;

CREATE TABLE `hilfe` (
  `seite` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `sub` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`seite`,`sub`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `hilfe` WRITE;
/*!40000 ALTER TABLE `hilfe` DISABLE KEYS */;

INSERT INTO `hilfe` (`seite`, `sub`, `text`)
VALUES
	('mitarbeiter','uebersicht','Hier sehen Sie eine &Uuml;bersicht mit allen registrierten Benutzern.<br/><br/>\r\n\r\ngr&uuml;n = aktiviert<br/> \r\nrot = deaktiviert<br/><br/>\r\n                      \r\nSie haben die M&ouml;glichkeit Benutzer durch Klick auf den farbigen Button zu aktivieren/deaktivieren. Des Weiteren können Sie Mitarbeiter l&ouml;schen, sofern Sie Administrationsrechte besitzen.<br/>\r\nDamit sich ein Benutzer anmelden/einloggen kann, muss er aktiviert sein.'),
	('mitarbeiter','neu','Hier können Sie einen neuen Benutzer (Mitarbeiter) erstellen.<br/><br/>\r\n\r\nBitte füllen Sie alle durch * gekennzeichneten Pflichtfelder vollständig aus.'),
	('mitarbeiter','details','Hier können Sie die gespeicherten Informationen zu jedem registrierten Benutzer (Mitarbeiter) einsehen.<br/><br/>\r\n\r\nWählen Sie den gewünschten Mitarbeiter aus.'),
	('mitarbeiter','bearbeiten','Hier können Sie die gespeicherten Daten zu jedem Benutzer (Mitarbeiter) bearbeiten.<br/><br/>\r\n\r\nWählen Sie den Mitarbeiter aus, den Sie bearbeiten möchten.\r\n<br/><br/>\r\n\r\nBitte füllen Sie alle durch * gekennzeichneten Pflichtfelder vollständig aus.'),
	('mitarbeiter','urlaub','Hier haben Sie die Möglichkeit den einzelnen Mitarbeitern Urlaubszeiten zuzuteilen bzw. gespeicherte Daten zu löschen oder zu bearbeiten.<br/><br/>\r\n\r\nWählen Sie einen Benutzer (Mitarbeiter) aus und geben die entsprechenden Daten ein.'),
	('kalender','uebersicht','Hier sehen Sie eine Kalenderübersicht. Sie können den gewünschten Monat auswählen.<br/>\r\nFür jeden Tag sind die entsprechenden Schichten und die jeweilige Belegung ersichtlich<br/><br/>\r\n\r\nrot = noch kein Mitarbeiter eingeteilt<br/>\r\ngelb = noch nicht genügend Mitarbeiter eingeteilt<br/>\r\ngrün = voll belegt<br/>\r\n<br/>\r\n\r\nDurch Klick auf eine Schicht gelangen Sie in die Detailansicht dieser Schicht (nur Admin) in der Sie dieser Schicht die gewünschten Mitarbeiter zuteilen können.'),
	('kalender','detail','Hier sehen Sie welche Mitarbeiter dieser Schicht an diesem Datum zugeteilt sind.<br/><br/>\r\n\r\nWenn Sie Adminrechte besitzen haben Sie die Möglichkeit die Belegung zu bearbeiten.<br/>\r\nSie können eingeteilte Mitarbeiter entfernen, oder neue hinzufügen.<br/>'),
	('dienstplan','uebersicht','Hier können Sie neue Dienstpläne erstellen oder archivierte Pläne einsehen.<br/><br/>\r\n\r\nZum Erstellen geben Sie ein Anfangs- und Enddatum an und wählen aus, ob der Plan für alle Mitarbeiter, oder nur für Sie selbst erstellt werden soll.'),
	('konfig','uebersicht','Hier sehen Sie eine Übersicht von allen gespeicherten Schichten.<br/><br/>\r\n\r\nSie haben die Möglichkeit einzelne Schichten zu löschen.'),
	('konfig','arbeitstage','Hier wählen Sie die Arbeitstage aus.<br/>\r\nDanach haben Sie die Möglichkeit für jeden Tag und jede Schicht die notwendige Belegung anzugeben.<br/><br/>\r\n\r\nUm diese Einstellung vornehmen zu können, muss mindestens eine Schicht angelegt sein.'),
	('konfig','neu','Hier können Sie neue Schichten anlegen.<br/><br/>\r\n\r\nFüllen Sie bitte alle Felder vollständig aus.<br/>\r\nZur Übersichtlichkeit wird jeder Schicht eine Farbe zugeordnet.'),
	('konfig','bearbeiten','Hier können Sie gespeicherte Schichten bearbeiten.<br/>\r\nWählen Sie die gewünschte Schicht aus.<br/><br/>\r\nFüllen Sie bitte alle Felder vollständig aus.'),
	('konfig','belegung','Hier werden für die einzelnen Schichten und Tage die benötige Anzahl an Mitarbeitern angegeben.<br/><br/>\r\n\r\nFür die korrekte Berechnung des Dienstplanes muss jeder Schicht an jedem Tag eine Belegung gegeben sein.'),
	('kalender','tag','Hier können Sie die Sonderschichten für den ausgewählten Tag verwalten. Die ist vor allem dann sinnvoll, wenn ein Mitarbeiter außerhalb der normalen Schichten eingesetzt werden soll<br/><br/>\r\nWenn Sie Adminrechte besitzen haben Sie die Möglichkeit die Belegung zu bearbeiten.<br/>\r\nDurch einen Klick auf \"Erstellen\" wird für den Mitarbeiter eine Sonderschicht angelegt. <br/><br/>\nSie können diese neue Sonderschicht anpassen, indem Sie \"zurück zum Kalelnder\" gehen und von dort aus die Sonderschicht auwählen, die im Kalender erscheinen wird.\n\n\r\n');

/*!40000 ALTER TABLE `hilfe` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle kalender_schicht
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kalender_schicht`;

CREATE TABLE `kalender_schicht` (
  `ksid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `termin` date NOT NULL,
  PRIMARY KEY (`ksid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `kalender_schicht` WRITE;
/*!40000 ALTER TABLE `kalender_schicht` DISABLE KEYS */;

INSERT INTO `kalender_schicht` (`ksid`, `sid`, `termin`)
VALUES
	(10,5,'2011-05-09'),
	(9,5,'2011-05-10'),
	(8,1,'2011-05-10'),
	(12,1,'2011-05-08'),
	(11,6,'2011-05-09'),
	(7,1,'2011-05-10'),
	(13,6,'2011-05-08'),
	(14,3,'2011-05-09'),
	(15,1,'2011-05-11'),
	(16,5,'2011-05-11'),
	(17,1,'2011-05-13'),
	(18,5,'2011-05-13');

/*!40000 ALTER TABLE `kalender_schicht` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle ma_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ma_info`;

CREATE TABLE `ma_info` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `ab` date DEFAULT NULL,
  `bis` date DEFAULT NULL,
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Export von Tabelle mitarbeiter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mitarbeiter`;

CREATE TABLE `mitarbeiter` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `vname` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `adresse` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `max_h_d` int(11) DEFAULT NULL,
  `max_h_w` int(11) DEFAULT NULL,
  `max_h_m` int(11) DEFAULT NULL,
  `max_u` int(11) DEFAULT NULL,
  `recht` int(11) NOT NULL,
  `angemeldet` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `pw` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `mitarbeiter` WRITE;
/*!40000 ALTER TABLE `mitarbeiter` DISABLE KEYS */;

INSERT INTO `mitarbeiter` (`mid`, `name`, `vname`, `adresse`, `tel`, `email`, `max_h_d`, `max_h_w`, `max_h_m`, `max_u`, `recht`, `angemeldet`, `pw`, `aktiv`)
VALUES
	(1,'Schiller','Martin','',' ','admin@dienstplaner.de',0,0,0,20,1,'','21232f297a57a5a743894a0e4a801fc3',1),
	(2,'Müller','Bert','','','bert@mueller.de',8,40,200,24,0,'','54ab4c9df68f32840787ae47b1ba9afc',1),
	(819,'Meier','Susanne','','','susi@meier.com',8,40,200,24,0,'','7a3bbfa99f014f41f2a4b368391c092c',1),
	(821,'Fischer','Lutz','','','lutz@fischer.com',8,40,200,24,0,'','0a8e3638e3c0deb4e5e49c72286a5b83',1),
	(828,'Weiler','Emily','','','emily@weiler.de',0,0,0,24,0,'','9e3ba813c8a21b92135e71a60ae10d6e',1);

/*!40000 ALTER TABLE `mitarbeiter` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle schicht
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schicht`;

CREATE TABLE `schicht` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `bez` varchar(40) COLLATE latin1_general_ci DEFAULT NULL,
  `kbez` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `ab` time DEFAULT NULL,
  `bis` time DEFAULT NULL,
  `color` char(6) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `schicht` WRITE;
/*!40000 ALTER TABLE `schicht` DISABLE KEYS */;

INSERT INTO `schicht` (`sid`, `bez`, `kbez`, `ab`, `bis`, `color`)
VALUES
	(3,'Vormittags','VM','06:05:00','12:10:00','ffb2fe'),
	(5,'Nachmittag','NM','12:00:00','18:00:00','bbeab2'),
	(6,'Abend','A','18:00:00','22:00:00','b4b2e0'),
	(0,'Sonderschicht','SONDER','22:00:00','23:00:00','d2b2b2');

/*!40000 ALTER TABLE `schicht` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle schicht_ma
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schicht_ma`;

CREATE TABLE `schicht_ma` (
  `tid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `ma` int(11) DEFAULT NULL,
  PRIMARY KEY (`tid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `schicht_ma` WRITE;
/*!40000 ALTER TABLE `schicht_ma` DISABLE KEYS */;

INSERT INTO `schicht_ma` (`tid`, `sid`, `ma`)
VALUES
	(1,3,3),
	(1,5,3),
	(1,6,2),
	(1,0,0),
	(2,3,3),
	(2,5,3),
	(2,6,2),
	(2,0,0),
	(3,3,3),
	(3,5,3),
	(3,6,2),
	(3,0,0),
	(4,3,3),
	(4,5,3),
	(4,6,2),
	(4,0,0),
	(5,3,3),
	(5,5,3),
	(5,6,0),
	(5,0,0),
	(6,3,0),
	(6,5,0),
	(6,6,0),
	(6,0,0),
	(7,3,0),
	(7,5,0),
	(7,6,0),
	(7,0,0);

/*!40000 ALTER TABLE `schicht_ma` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle schicht_mitarbeiter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schicht_mitarbeiter`;

CREATE TABLE `schicht_mitarbeiter` (
  `smid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `termin` date NOT NULL,
  `von` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `bis` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`smid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `schicht_mitarbeiter` WRITE;
/*!40000 ALTER TABLE `schicht_mitarbeiter` DISABLE KEYS */;

INSERT INTO `schicht_mitarbeiter` (`smid`, `sid`, `mid`, `termin`, `von`, `bis`)
VALUES
	(221,0,1,'2017-09-16','10:00','12:00');

/*!40000 ALTER TABLE `schicht_mitarbeiter` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `statusID` int(11) NOT NULL,
  `bez` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`statusID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Export von Tabelle statusquo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statusquo`;

CREATE TABLE `statusquo` (
  `sqid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL,
  `aktuell_h_d` int(11) NOT NULL,
  `aktuell_h_w` int(11) NOT NULL,
  `aktuell_h_m` int(11) NOT NULL,
  `aktuell_u` int(11) NOT NULL,
  `statusID` int(11) NOT NULL,
  PRIMARY KEY (`sqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Export von Tabelle stosszeiten
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stosszeiten`;

CREATE TABLE `stosszeiten` (
  `stid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `ab` date DEFAULT NULL,
  `bis` date DEFAULT NULL,
  `plus_ma` int(11) DEFAULT NULL,
  PRIMARY KEY (`stid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Export von Tabelle tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `tid` int(11) NOT NULL,
  `name` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;

INSERT INTO `tag` (`tid`, `name`)
VALUES
	(1,'Montag'),
	(2,'Dienstag'),
	(3,'Mittwoch'),
	(4,'Donnerstag'),
	(5,'Freitag'),
	(6,'Samstag'),
	(7,'Sonntag');

/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle urlaub
# ------------------------------------------------------------

DROP TABLE IF EXISTS `urlaub`;

CREATE TABLE `urlaub` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL,
  `ab` date DEFAULT NULL,
  `bis` date DEFAULT NULL,
  `tage` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

LOCK TABLES `urlaub` WRITE;
/*!40000 ALTER TABLE `urlaub` DISABLE KEYS */;

INSERT INTO `urlaub` (`uid`, `mid`, `ab`, `bis`, `tage`)
VALUES
	(43,2,'2017-09-11','2017-09-15',5);

/*!40000 ALTER TABLE `urlaub` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
