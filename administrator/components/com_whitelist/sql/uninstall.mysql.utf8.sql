DROP TABLE IF EXISTS `#__whitelist`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_whitelist.%');