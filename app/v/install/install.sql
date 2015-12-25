CREATE DATABASE  IF NOT EXISTS `{{ @DB_NAME }}` ;
USE `{{ @DB_NAME }}`;

DROP TABLE IF EXISTS `{{ @DB_PREFIX }}_settings`;
CREATE TABLE `{{ @DB_PREFIX }}_settings` (
  `param` varchar(255) NOT NULL,
  `val` text,
  PRIMARY KEY (`param`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
LOCK TABLES `{{ @DB_PREFIX }}_settings` WRITE;
INSERT INTO `{{ @DB_PREFIX }}_settings` VALUES 
    ('password' , ''),
    ('api_key',''),
    ('last_balance_check',''),
    ('balance',''),    
    ('default_captcha','reklamper'),
    ('rewards','90*10,9*100,1*500'),
    ('referral','15'),
    ('timer','180'),
    ('currency','BTC'),
    ('site_name',''),
    ('site_name_short',''),
    ('faucet_solvemedia_challenge_key',''),
    ('faucet_solvemedia_verification_key',''),
    ('faucet_solvemedia_auth_key',''),
    ('faucet_recaptcha_public_key',''),
    ('faucet_recaptcha_private_key',''),
    ('faucet_ayah_publisher_key',''),
    ('faucet_ayah_scoring_key',''),
    ('faucet_captchme_public_key', ''),
    ('faucet_captchme_private_key', ''),
    ('faucet_captchme_authentication_key', ''),
    ('faucet_funcaptcha_private_key', ''),
    ('faucet_funcaptcha_public_key', ''),
    ('pages_faucet_ad',''),
    ('pages_top_ad',''),
    ('pages_bottom_ad',''),
    ('show_admin_link','1');
UNLOCK TABLES;
DROP TABLE IF EXISTS `{{ @DB_PREFIX }}_addresses`;
CREATE TABLE `{{ @DB_PREFIX }}_addresses` (
  `address` varchar(60) NOT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
UNLOCK TABLES;
DROP TABLE IF EXISTS `{{ @DB_PREFIX }}_refs`;
CREATE TABLE `{{ @DB_PREFIX }}_refs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(60) NOT NULL,
  `balance` bigint(20) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `address_UNIQUE` (`address`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `{{ @DB_PREFIX }}_ips`;
CREATE TABLE `{{ @DB_PREFIX }}_ips` (
  `ip` varchar(20) NOT NULL,
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;