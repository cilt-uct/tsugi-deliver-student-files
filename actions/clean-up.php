<?php
require_once "../../config.php";
include "../tool-config_dist.php";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
// stop PHP from automatically embedding PHPSESSID on local URLs
ini_set('session.use_trans_sid', false);
error_reporting(E_ALL);

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;
use \Tsugi\Blob\BlobUtil;

$p = $CFG->dbprefix;

$PDOX = LTIX::getConnection();

// Get Blobs
$blob_stmt = $PDOX->prepare("SELECT `A`.blob_id ".
                        "FROM {$p}student_files `A` ".
                        "LEFT JOIN {$p}blob_file `blob` on `blob`.file_id = `A`.blob_id and `blob`.link_id = `A`.link_id ".
                        "WHERE `blob`.context_id in (select context_id from {$p}lti_context where context_key = :context_key)");
$blob_stmt->execute(array(":context_key" => '456434513'));
$blobs = $blob_stmt->fetchAll(PDO::FETCH_ASSOC);

$rows = $blob_stmt->rowCount();
$success = 0;
$error = 0;
foreach ($blobs as $row) {
    BlobUtil::deleteBlob($row['blob_id'], true);

    $verifyStmt = $PDOX->prepare("select file_id from {$p}blob_file where file_id = :blob_id");
    $verifyStmt->execute(array(":blob_id" => $row['blob_id']));
    if ($verifyStmt->rowCount() == 0) {
        $success ++;
    } else {
        $error ++;
    }
}

// Clean up empty links
$del_stmt = $PDOX->prepare("DELETE FROM {$p}student_files WHERE blob_id not in (SELECT file_id FROM tsugi_dev.blob_file)");
$del_stmt->execute();

echo json_encode(['del' => $del_stmt->rowCount(), 'blob'=> ['count' => $rows, 'success' => $success, 'error' => $error]]);
