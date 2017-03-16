-- MySQL dump 9.09
--
-- Host: localhost    Database: locations
---------------------------------------------------------
-- Server version	4.0.15-Max

--
-- Table structure for table `continents`
--

CREATE TABLE continents (
  code char(2) NOT NULL default '',
  name char(16) NOT NULL default '',
  PRIMARY KEY  (code)
) TYPE=MyISAM;

--
-- Dumping data for table `continents`
--

INSERT INTO continents VALUES ('na','North America');
INSERT INTO continents VALUES ('eu','Europe');
INSERT INTO continents VALUES ('sa','South America');
INSERT INTO continents VALUES ('as','Asia');
INSERT INTO continents VALUES ('oc','Oceania');

--
-- Table structure for table `countries`
--

CREATE TABLE countries (
  code char(2) NOT NULL default '',
  name char(16) NOT NULL default '',
  continent char(2) NOT NULL default '',
  PRIMARY KEY  (code,continent)
) TYPE=MyISAM;

--
-- Dumping data for table `countries`
--

INSERT INTO countries VALUES ('us','United States','na');
INSERT INTO countries VALUES ('ca','Canada','na');
INSERT INTO countries VALUES ('pt','Portugal','eu');
INSERT INTO countries VALUES ('de','Germany','eu');
INSERT INTO countries VALUES ('br','Brazil','sa');
INSERT INTO countries VALUES ('ar','Argentina','sa');
INSERT INTO countries VALUES ('jp','Japan','as');
INSERT INTO countries VALUES ('kr','Korea','as');
INSERT INTO countries VALUES ('au','Australia','oc');
INSERT INTO countries VALUES ('nz','New Zeland','oc');

--
-- Table structure for table `locations`
--

CREATE TABLE locations (
  code char(2) NOT NULL default '',
  name char(16) NOT NULL default '',
  country char(2) NOT NULL default '',
  PRIMARY KEY  (code,country)
) TYPE=MyISAM;

--
-- Dumping data for table `locations`
--

INSERT INTO locations VALUES ('ny','New York','us');
INSERT INTO locations VALUES ('la','Los Angeles','us');
INSERT INTO locations VALUES ('to','Toronto','ca');
INSERT INTO locations VALUES ('mo','Montréal','ca');
INSERT INTO locations VALUES ('li','Lisbon','pt');
INSERT INTO locations VALUES ('av','Aveiro','pt');
INSERT INTO locations VALUES ('fr','Frankfurt','de');
INSERT INTO locations VALUES ('be','Berlin','de');
INSERT INTO locations VALUES ('sa','São Paulo','br');
INSERT INTO locations VALUES ('ri','Rio de Janeiro','br');
INSERT INTO locations VALUES ('bu','Buenos Aires','ar');
INSERT INTO locations VALUES ('ma','Mar del Plata','ar');
INSERT INTO locations VALUES ('to','Tokio','jp');
INSERT INTO locations VALUES ('os','Osaka','jp');
INSERT INTO locations VALUES ('se','Seoul','ky');
INSERT INTO locations VALUES ('yo','Yosu','kr');
INSERT INTO locations VALUES ('sy','Sydney','au');
INSERT INTO locations VALUES ('me','Melbourne','au');
INSERT INTO locations VALUES ('we','Wellington','nz');
INSERT INTO locations VALUES ('au','Auckland','nz');

