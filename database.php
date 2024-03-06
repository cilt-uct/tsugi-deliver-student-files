<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
    array( "{$CFG->dbprefix}student_files",
           "CREATE TABLE {$CFG->dbprefix}student_files (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `link_id` INT NOT NULL,
            `user_id` INT NOT NULL,
            `blob_id` INT NOT NULL,
            `thumb_id` INT NULL,
            PRIMARY KEY (`id`),
            INDEX `idx_link_id` (`link_id` ASC),
            INDEX `idx_user_id` (`user_id` ASC),
            INDEX `idx_blob_id` (`blob_id` ASC),
            INDEX `idx_thumb_id` (`thumb_id` ASC));")
);

$DATABASE_UPGRADE = function($oldversion) {
    global $CFG, $PDOX;

    if ( ! $PDOX->columnExists('thumb_id', "{$CFG->dbprefix}student_files") ) {
        $sql= "ALTER TABLE {$CFG->dbprefix}student_files ADD thumb_id INTEGER NULL";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    return 202002101610;
};