DROP TABLE IF EXISTS `glpi_plugin_videoprojecteurs_videoprojecteurs`;
CREATE TABLE `glpi_plugin_videoprojecteurs_videoprojecteurs` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `serial` varchar(255) collate utf8_unicode_ci default NULL,
   `plugin_videoprojecteurs_videoprojecteurtypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_videoprojecteurs_videoprojecteurtypes (id)',
   `plugin_videoprojecteurs_videoprojecteurmarques_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_videoprojecteurs_videoprojecteurmarques (id)',
   `plugin_videoprojecteurs_videoprojecteurmodeles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_videoprojecteurs_videoprojecteurmodeles (id)',
   `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)',
   `salle` varchar(255) collate utf8_unicode_ci default NULL,
   `date_affectation` date default NULL,
   `states_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_states (id)',
   `users_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
   `is_helpdesk_visible` int(11) NOT NULL default '1',
   `date_mod` datetime default NULL,
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `is_deleted` tinyint(1) NOT NULL default '0',
        `is_bookable` tinyint(1) NOT NULL default '1',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_videoprojecteurs_videoprojecteurtypes_id` (`plugin_videoprojecteurs_videoprojecteurtypes_id`),
   KEY `plugin_videoprojecteurs_videoprojecteurmarques_id` (`plugin_videoprojecteurs_videoprojecteurmarques_id`),
   KEY `plugin_videoprojecteurs_videoprojecteurmodeles_id` (`plugin_videoprojecteurs_videoprojecteurmodeles_id`),
   KEY `locations_id` (`locations_id`),
   KEY `states_id` (`states_id`),
   KEY `users_id` (`users_id`),
   KEY `is_helpdesk_visible` (`is_helpdesk_visible`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_videoprojecteurs_videoprojecteurtypes`;
   CREATE TABLE `glpi_plugin_videoprojecteurs_videoprojecteurtypes` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_videoprojecteurs_videoprojecteurmarques`;
   CREATE TABLE `glpi_plugin_videoprojecteurs_videoprojecteurmarques` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_videoprojecteurs_videoprojecteurmodeles`;
   CREATE TABLE `glpi_plugin_videoprojecteurs_videoprojecteurmodeles` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_videoprojecteurs_profiles`;
CREATE TABLE `glpi_plugin_videoprojecteurs_profiles` (
   `id` int(11) NOT NULL auto_increment,
   `profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
   `videoprojecteurs` char(1) collate utf8_unicode_ci default NULL,
   `open_ticket` char(1) collate utf8_unicode_ci default NULL,
   PRIMARY KEY  (`id`),
   KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_videoprojecteurs_configs` VALUES (1, '30', '30', '30');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginVideoprojecteursVideoprojecteur','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginVideoprojecteursVideoprojecteur','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginVideoprojecteursVideoprojecteur','5','4','0');