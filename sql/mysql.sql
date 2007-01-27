CREATE TABLE `oscgiving_donationamounts` (
  `don_id` mediumint(9) unsigned NOT NULL default '0' ,
  `dna_Amount` decimal(10,2) default NULL,
  `dna_fun_ID` tinyint(3) unsigned default NULL,
  PRIMARY KEY `don_ID` (`don_id`),
  KEY `don_ID` (`don_id`)
);

CREATE TABLE `oscgiving_donationfunds` (
  `fund_id` mediumint(3) NOT NULL auto_increment,
  `fund_Active` enum('true','false') NOT NULL default 'true',
  `fund_Name` varchar(30) default NULL,
  `fund_Description` varchar(100) default NULL,
  PRIMARY KEY  (`fund_id`),
  UNIQUE KEY `fund_id` (`fund_id`)
);


CREATE TABLE `oscgiving_donations` (
  `don_id` mediumint(9) unsigned NOT NULL auto_increment,
  `don_DonorID` mediumint(9) unsigned default NULL,
  `don_PaymentType` tinyint(3) default NULL,
  `don_CheckNumber` mediumint(9) unsigned NOT NULL default '0',
  `don_Date` date NOT NULL default '0000-00-00',
  `don_Envelope` smallint(5) unsigned default NULL,
  PRIMARY KEY  (`don_id`),
  KEY `don_DonorID` (`don_DonorID`),
  KEY `don_Date` (`don_Date`)
) ;



# Sample data for member classifications
INSERT INTO oscgiving_donationfunds 
VALUES (1,'true','General Donation','Default fund: General operating expenses.');

INSERT INTO oscgiving_donationfunds VALUES(2,'true','Missions','Support for missions.');

INSERT INTO oscgiving_donationfunds VALUES(3,'true','Building','New Building Fund');
