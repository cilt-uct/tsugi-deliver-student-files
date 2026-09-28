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

$PDOX->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function statementError($stmt)
{
    $errorInfo = $stmt->errorInfo();
    return $errorInfo[2] ?? 'Unknown database error';
}

$blob_stmt = $PDOX->prepare(
    "SELECT `A`.blob_id ".
    "FROM {$p}student_files `A` ".
    "LEFT JOIN {$p}blob_file `blob` ".
    "ON `blob`.file_id = `A`.blob_id ".
    "AND `blob`.link_id = `A`.link_id ".
    "WHERE `blob`.context_id IN ".
    "(SELECT context_id FROM {$p}lti_context ".
    "WHERE context_key = :context_key)"
);

$blobExecute = $blob_stmt->execute([
    ':context_key' => '456434513'
]);

$blobs = $blobExecute ? $blob_stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$rows = $blobExecute ? count($blobs) : 0;
$success = 0;
$error = 0;
$response = [
    'blob' => [
        'count' => $rows,
        'success' => 0,
        'error' => 0,
    ],
    'del' => [
        'count' => 0,
        'success' => false,
    ],
];

if (!$blobExecute) {
    $response['blob']['query_error'] = statementError($blob_stmt);
}

foreach ($blobs as $row) {
    BlobUtil::deleteBlob($row['blob_id'], true);

    $verifyStmt = $PDOX->prepare("select file_id from {$p}blob_file where file_id = :blob_id");
    $verifyExecute = $verifyStmt->execute(array(":blob_id" => $row['blob_id']));
    if (!$verifyExecute) {
        $error ++;
        $response['blob']['verify_errors'][$row['blob_id']] = statementError($verifyStmt);
        continue;
    }

    if ($verifyStmt->rowCount() == 0) {
        $success ++;
    } else {
        $error ++;
    }
}

$response['blob']['success'] = $success;
$response['blob']['error'] = $error;

// Clean up empty links
$del_stmt = $PDOX->prepare(
    "DELETE FROM {$p}student_files `student_file` ".
    "WHERE NOT EXISTS (".
    "SELECT 1 FROM {$p}blob_file `blob` ".
    "WHERE `blob`.file_id = `student_file`.blob_id)"
);
$deleteSuccess = $del_stmt->execute();

if ($deleteSuccess) {
    $response['del']['count'] = $del_stmt->rowCount();
    $response['del']['success'] = true;
} else {
    $response['del']['error'] = statementError($del_stmt);
}

echo json_encode($response);
