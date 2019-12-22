<?php
$installer = $this;
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('ordertrack')};
CREATE TABLE IF NOT EXISTS {$this->getTable('ordertrack')} (
	`ordertrack_id` int(11) unsigned NOT NULL auto_increment COMMENT 'ordertrack_id',
	`emailtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`email` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`ordertrack_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
");
$installer->endSetup();
?>
