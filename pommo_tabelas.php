<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

/* Tabela  suscritores */
$sql_excluir_subscribers ="DROP TABLE IF EXISTS `pommo_subscribers`";
$res_excluir_subscribers = $db_pommo->Execute($sql_excluir_subscribers);

$tabela_suscritores = "
CREATE TABLE IF NOT EXISTS `pommo_subscribers` (
  `subscriber_id` int(10) unsigned NOT NULL auto_increment,
  `email` char(60) NOT NULL default '',
  `time_touched` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `time_registered` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL default '0' COMMENT '0: NULL, 1-8: REMOVE, 9: UPDATE',
  `ip` int(10) unsigned default NULL COMMENT 'Stored with INET_ATON(), Fetched with INET_NTOA()',
  `status` tinyint(1) NOT NULL default '2' COMMENT '0: Inactive, 1: Active, 2: Pending',
  PRIMARY KEY  (`subscriber_id`),
  KEY `status` (`status`,`subscriber_id`),
  KEY `status_2` (`status`,`email`),
  KEY `status_3` (`status`,`time_touched`),
  KEY `status_4` (`status`,`time_registered`),
  KEY `status_5` (`status`,`ip`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
";
// echo $tabela_suscritores . "<br>";
$res_subs = $db_pommo->Execute($tabela_suscritores);
if($res_subs === false) die ("Não foi possível criar a tabela pommo_subscribers");

/* Tabela data */
$sql_excluir_data ="DROP TABLE IF EXISTS `pommo_subscriber_data`";
$res_excluir_data = $db_pommo->Execute($sql_excluir_data);

$tabela_data = "
CREATE TABLE IF NOT EXISTS `pommo_subscriber_data` (
  `data_id` bigint(20) unsigned NOT NULL auto_increment,
  `field_id` int(10) unsigned NOT NULL default '0',
  `subscriber_id` int(10) unsigned NOT NULL default '0',
  `value` char(60) NOT NULL default '',
  PRIMARY KEY  (`data_id`),
  KEY `subscriber_id` (`subscriber_id`,`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
";
$res_subs_data = $db_pommo->Execute($tabela_data);
if($res_subs_data === false) die ("Não foi possível criar a tabela pommo_subscribers_data");

/* Tabela fields */
$sql_excluir_fields ="DROP TABLE IF EXISTS `pommo_fields`";
$res_excluir_fields = $db_pommo->Execute($sql_excluir_fields);

$tabela_fields = "
CREATE TABLE IF NOT EXISTS `pommo_fields` (
  `field_id` smallint(5) unsigned NOT NULL auto_increment,
  `field_active` enum('on','off') NOT NULL default 'off',
  `field_ordering` smallint(5) unsigned NOT NULL default '0',
  `field_name` varchar(60) default NULL,
  `field_prompt` varchar(60) default NULL,
  `field_normally` varchar(60) default NULL,
  `field_array` text,
  `field_required` enum('on','off') NOT NULL default 'off',
  `field_type` enum('checkbox','multiple','text','date','number','comment') default NULL,
  PRIMARY KEY  (`field_id`),
  KEY `active` (`field_active`,`field_ordering`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
";
$res_pommo_fields = $db_pommo->Execute($tabela_fields);
if($res_pommo_fields === false) die ("Não foi possível criar a tabela pommo_fields");

?>
