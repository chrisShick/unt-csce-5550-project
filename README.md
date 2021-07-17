## Getting Started

### Requirements
* HTTP Server. For example: Apache. Having mod_rewrite is preferred, but by no means required. You can also use nginx, or Microsoft IIS if you prefer.

* PHP 8.0.x

* mbstring PHP extension

* intl PHP extension

* SimpleXML PHP extension

* PDO PHP extension

* MySQL Database
* Composer

* redis
* redis php extension

### Database SQL
```sql
# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.5-10.5.9-MariaDB)
# Database: password_manager
# Generation Time: 2021-07-17 03:58:06 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `iso` char(2) NOT NULL,
  `iso_three` char(3) DEFAULT NULL,
  `calling_code` int(5) NOT NULL,
  `title` varchar(80) NOT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`iso`, `iso_three`, `calling_code`, `title`)
VALUES
	('AC','ACS',247,'Ascension Island'),
	('AD','AND',376,'Andorra'),
	('AE','ARE',971,'United Arab Emirates'),
	('AF','AFG',93,'Afghanistan'),
	('AG','ATG',1,'Antigua and Barbuda'),
	('AI','AIA',1,'Anguilla'),
	('AL','ALB',355,'Albania'),
	('AM','ARM',374,'Armenia'),
	('AO','AGO',244,'Angola'),
	('AR','ARG',54,'Argentina'),
	('AS','ASM',1,'American Samoa'),
	('AT','AUT',43,'Austria'),
	('AU','AUS',61,'Australia'),
	('AW','ABW',297,'Aruba'),
	('AX','ALA',358,'Åland Islands'),
	('AZ','AZE',994,'Azerbaijan'),
	('BA','BIH',387,'Bosnia and Herzegovina'),
	('BB','BRB',1,'Barbados'),
	('BD','BGD',880,'Bangladesh'),
	('BE','BEL',32,'Belgium'),
	('BF','BFA',226,'Burkina Faso'),
	('BG','BGR',359,'Bulgaria'),
	('BH','BHR',973,'Bahrain'),
	('BI','BDI',257,'Burundi'),
	('BJ','BEN',229,'Benin'),
	('BL','BLM',590,'Saint Barthélemy'),
	('BM','BMU',1,'Bermuda'),
	('BN','BRN',673,'Brunei'),
	('BO','BOL',591,'Bolivia'),
	('BQ','BES',599,'Bonaire, Sint Eustatius and Saba'),
	('BR','BRA',55,'Brazil'),
	('BS','BHS',1,'Bahamas'),
	('BT','BTN',975,'Bhutan'),
	('BW','BWA',267,'Botswana'),
	('BY','BLR',375,'Belarus'),
	('BZ','BLZ',501,'Belize'),
	('CA','CAN',1,'Canada'),
	('CC','CCK',61,'Cocos Islands'),
	('CD','COD',243,'The Democratic Republic Of Congo'),
	('CF','CAF',236,'Central African Republic'),
	('CG','COG',242,'Congo'),
	('CH','CHE',41,'Switzerland'),
	('CI','CIV',225,'Côte d\'Ivoire'),
	('CK','COK',682,'Cook Islands'),
	('CL','CHL',56,'Chile'),
	('CM','CMR',237,'Cameroon'),
	('CN','CHN',86,'China'),
	('CO','COL',57,'Colombia'),
	('CR','CRI',506,'Costa Rica'),
	('CU','CUB',53,'Cuba'),
	('CV','CPV',238,'Cape Verde'),
	('CW','CUW',599,'Curaçao'),
	('CX','CXR',61,'Christmas Island'),
	('CY','CYP',357,'Cyprus'),
	('CZ','CZE',420,'Czech Republic'),
	('DE','DEU',49,'Germany'),
	('DJ','DJI',253,'Djibouti'),
	('DK','DNK',45,'Denmark'),
	('DM','DMA',1,'Dominica'),
	('DO','DOM',1,'Dominican Republic'),
	('DZ','DZA',213,'Algeria'),
	('EC','ECU',593,'Ecuador'),
	('EE','EST',372,'Estonia'),
	('EG','EGY',20,'Egypt'),
	('EH','ESH',212,'Western Sahara'),
	('ER','ERI',291,'Eritrea'),
	('ES','ESP',34,'Spain'),
	('ET','ETH',251,'Ethiopia'),
	('FI','FIN',358,'Finland'),
	('FJ','FJI',679,'Fiji'),
	('FK','FLK',500,'Falkland Islands'),
	('FM','FSM',691,'Micronesia'),
	('FO','FRO',298,'Faroe Islands'),
	('FR','FRA',33,'France'),
	('GA','GAB',241,'Gabon'),
	('GB','GBR',44,'United Kingdom'),
	('GD','GRD',1,'Grenada'),
	('GE','GEO',995,'Georgia'),
	('GF','GUF',594,'French Guiana'),
	('GG','GGY',44,'Guernsey'),
	('GH','GHA',233,'Ghana'),
	('GI','GIB',350,'Gibraltar'),
	('GL','GRL',299,'Greenland'),
	('GM','GMB',220,'Gambia'),
	('GN','GIN',224,'Guinea'),
	('GP','GLP',590,'Guadeloupe'),
	('GQ','GNQ',240,'Equatorial Guinea'),
	('GR','GRC',30,'Greece'),
	('GT','GTM',502,'Guatemala'),
	('GU','GUM',1,'Guam'),
	('GW','GNB',245,'Guinea-Bissau'),
	('GY','GUY',592,'Guyana'),
	('HK','HKG',852,'Hong Kong'),
	('HN','HND',504,'Honduras'),
	('HR','HRV',385,'Croatia'),
	('HT','HTI',509,'Haiti'),
	('HU','HUN',36,'Hungary'),
	('ID','IDN',62,'Indonesia'),
	('IE','IRL',353,'Ireland'),
	('IL','ISR',972,'Israel'),
	('IM','IMN',44,'Isle Of Man'),
	('IN','IND',91,'India'),
	('IO','IOT',246,'British Indian Ocean Territory'),
	('IQ','IRQ',964,'Iraq'),
	('IR','IRN',98,'Iran'),
	('IS','ISL',354,'Iceland'),
	('IT','ITA',39,'Italy'),
	('JE','JEY',44,'Jersey'),
	('JM','JAM',1,'Jamaica'),
	('JO','JOR',962,'Jordan'),
	('JP','JPN',81,'Japan'),
	('KE','KEN',254,'Kenya'),
	('KG','KGZ',996,'Kyrgyzstan'),
	('KH','KHM',855,'Cambodia'),
	('KI','KIR',686,'Kiribati'),
	('KM','COM',269,'Comoros'),
	('KN','KNA',1,'Saint Kitts And Nevis'),
	('KP','PRK',850,'North Korea'),
	('KR','KOR',82,'South Korea'),
	('KW','KWT',965,'Kuwait'),
	('KY','CYM',1,'Cayman Islands'),
	('KZ','KAZ',7,'Kazakhstan'),
	('LA','LAO',856,'Laos'),
	('LB','LBN',961,'Lebanon'),
	('LC','LCA',1,'Saint Lucia'),
	('LI','LIE',423,'Liechtenstein'),
	('LK','LKA',94,'Sri Lanka'),
	('LR','LBR',231,'Liberia'),
	('LS','LSO',266,'Lesotho'),
	('LT','LTU',370,'Lithuania'),
	('LU','LUX',352,'Luxembourg'),
	('LV','LVA',371,'Latvia'),
	('LY','LBY',218,'Libya'),
	('MA','MAR',212,'Morocco'),
	('MC','MCO',377,'Monaco'),
	('MD','MDA',373,'Moldova'),
	('ME','MNE',382,'Montenegro'),
	('MF','MAF',590,'Saint Martin'),
	('MG','MDG',261,'Madagascar'),
	('MH','MHL',692,'Marshall Islands'),
	('MK','MKD',389,'Macedonia'),
	('ML','MLI',223,'Mali'),
	('MM','MMR',95,'Myanmar'),
	('MN','MNG',976,'Mongolia'),
	('MO','MAC',853,'Macao'),
	('MP','MNP',1,'Northern Mariana Islands'),
	('MQ','MTQ',596,'Martinique'),
	('MR','MRT',222,'Mauritania'),
	('MS','MSR',1,'Montserrat'),
	('MT','MLT',356,'Malta'),
	('MU','MUS',230,'Mauritius'),
	('MV','MDV',960,'Maldives'),
	('MW','MWI',265,'Malawi'),
	('MX','MEX',52,'Mexico'),
	('MY','MYS',60,'Malaysia'),
	('MZ','MOZ',258,'Mozambique'),
	('NA','NAM',264,'Namibia'),
	('NC','NCL',687,'New Caledonia'),
	('NE','NER',227,'Niger'),
	('NF','NFK',672,'Norfolk Island'),
	('NG','NGA',234,'Nigeria'),
	('NI','NIC',505,'Nicaragua'),
	('NL','NLD',31,'Netherlands'),
	('NO','NOR',47,'Norway'),
	('NP','NPL',977,'Nepal'),
	('NR','NRU',674,'Nauru'),
	('NU','NIU',683,'Niue'),
	('NZ','NZL',64,'New Zealand'),
	('OM','OMN',968,'Oman'),
	('PA','PAN',507,'Panama'),
	('PE','PER',51,'Peru'),
	('PF','PYF',689,'French Polynesia'),
	('PG','PNG',675,'Papua New Guinea'),
	('PH','PHL',63,'Philippines'),
	('PK','PAK',92,'Pakistan'),
	('PL','POL',48,'Poland'),
	('PM','SPM',508,'Saint Pierre And Miquelon'),
	('PR','PRI',1,'Puerto Rico'),
	('PS','PSE',970,'Palestine'),
	('PT','PRT',351,'Portugal'),
	('PW','PLW',680,'Palau'),
	('PY','PRY',595,'Paraguay'),
	('QA','QAT',974,'Qatar'),
	('RE','REU',262,'Reunion'),
	('RO','ROU',40,'Romania'),
	('RS','SRB',381,'Serbia'),
	('RU','RUS',7,'Russia'),
	('RW','RWA',250,'Rwanda'),
	('SA','SAU',966,'Saudi Arabia'),
	('SB','SLB',677,'Solomon Islands'),
	('SC','SYC',248,'Seychelles'),
	('SD','SDN',249,'Sudan'),
	('SE','SWE',46,'Sweden'),
	('SG','SGP',65,'Singapore'),
	('SH','SHN',290,'Saint Helena'),
	('SI','SVN',386,'Slovenia'),
	('SJ','SJM',47,'Svalbard And Jan Mayen'),
	('SK','SVK',421,'Slovakia'),
	('SL','SLE',232,'Sierra Leone'),
	('SM','SMR',378,'San Marino'),
	('SN','SEN',221,'Senegal'),
	('SO','SOM',252,'Somalia'),
	('SR','SUR',597,'Suriname'),
	('SS','SSD',211,'South Sudan'),
	('ST','STP',239,'Sao Tome And Principe'),
	('SV','SLV',503,'El Salvador'),
	('SX','SXM',1,'Sint Maarten (Dutch part)'),
	('SY','SYR',963,'Syria'),
	('SZ','SWZ',268,'Swaziland'),
	('TA','',290,'Tristan da Cunha'),
	('TC','TCA',1,'Turks And Caicos Islands'),
	('TD','TCD',235,'Chad'),
	('TG','TGO',228,'Togo'),
	('TH','THA',66,'Thailand'),
	('TJ','TJK',992,'Tajikistan'),
	('TK','TKL',690,'Tokelau'),
	('TL','TLS',670,'Timor-Leste'),
	('TM','TKM',993,'Turkmenistan'),
	('TN','TUN',216,'Tunisia'),
	('TO','TON',676,'Tonga'),
	('TR','TUR',90,'Turkey'),
	('TT','TTO',1,'Trinidad and Tobago'),
	('TV','TUV',688,'Tuvalu'),
	('TW','TWN',886,'Taiwan'),
	('TZ','TZA',255,'Tanzania'),
	('UA','UKR',380,'Ukraine'),
	('UG','UGA',256,'Uganda'),
	('US','USA',1,'United States'),
	('UY','URY',598,'Uruguay'),
	('UZ','UZB',998,'Uzbekistan'),
	('VA','VAT',39,'Vatican'),
	('VC','VCT',1,'Saint Vincent And The Grenadines'),
	('VE','VEN',58,'Venezuela'),
	('VG','VGB',1,'British Virgin Islands'),
	('VI','VIR',1,'U.S. Virgin Islands'),
	('VN','VNM',84,'Vietnam'),
	('VU','VUT',678,'Vanuatu'),
	('WF','WLF',681,'Wallis And Futuna'),
	('WS','WSM',685,'Samoa'),
	('XK','KOS',383,'Kosovo'),
	('YE','YEM',967,'Yemen'),
	('YT','MYT',262,'Mayotte'),
	('ZA','ZAF',27,'South Africa'),
	('ZM','ZMB',260,'Zambia'),
	('ZW','ZWE',263,'Zimbabwe');

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table registrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `registrations`;

CREATE TABLE `registrations` (
  `id` char(36) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
  `id` char(36) NOT NULL DEFAULT '',
  `title` varchar(25) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;

INSERT INTO `user_roles` (`id`, `title`, `created`, `modified`)
VALUES
	('14b6c89f-6a55-49e4-8189-ff5f6aa5f510','Admin','2021-07-08 21:38:07','2021-07-08 21:38:07'),
	('280322c0-bfd8-40f2-8f7a-4f2724928d0e','User','2021-07-08 21:37:51','2021-07-08 21:37:51');

/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` char(36) NOT NULL DEFAULT '',
  `user_role_id` char(36) NOT NULL DEFAULT '280322c0-bfd8-40f2-8f7a-4f2724928d0e',
  `username` varchar(50) NOT NULL,
  `country_code` varchar(5) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `is_verified` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```


### Clone repo
```bash
git clone https://github.com/chrisShick/unt-csce-5550-project
```

### Install Libraries
```bash
cd unt-csce-5550-project
composer install
# run install again to set permission for files
composer install
```
### Setup
Next, you will need to alter the app_local.php to change the database settings to the database you have locally.
Then, you will add the following keys to app_local.php file. You will need to add your Twilio account values.
```php
'Twilio' => [
        'sid' => '',
        'token' => '',
        'serviceSid' => '',
    ],
```

### Install encryption key
```bash
bin/cake CreateEncryptionKey unt_project_encryption
```

### Enable quick server
```bash
bin/cake server
```
