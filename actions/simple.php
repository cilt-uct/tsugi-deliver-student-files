<?php
require_once "../../config.php";
include '../tool-config_dist.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use \Tsugi\Core\LTIX;
use \Tsugi\Blob\BlobUtil;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();
$PDOX = LTIX::getConnection();

$p = $CFG->dbprefix;

function get_upload_data($id) {
    return @$_FILES[$id];
}

function get_server_var($id) {
    return @$_SERVER[$id];
}

function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null,
                            $pdo, $p, $link_id, $user_id) {
    $file = new \stdClass();
    // $file->name = $this->get_file_name($uploaded_file, $name, $size, $type, $error, $index, $content_range);
    // $file->size = $this->fix_integer_overflow((int)$size);
    $file->type = $type;

    $construct = [
        'name' => $name,
        'full_path' => $name,
        'type' => $type,
        'tmp_name' => $uploaded_file,
        'error' => $error,
        'size' => $size
    ];
    print json_encode($construct);
    print "$name, $size, $type, $error, $index, $content_range";

    $safety1 = BlobUtil::validateUpload($construct);
    if ($safety1 !== true) {
        echo "Error: " . $safety1;
        error_log("Upload Error: " . $safety1);
    }

    $blob_id = BlobUtil::uploadToBlob($construct);
    if ($blob_id === false) {
        echo 'Problem storing file in server: ' . $file->name;
        error_log('error');
    }

    // Save success so add info to gallery database
    $newStmt = $pdo->prepare("INSERT INTO {$p}student_files (link_id, user_id, blob_id) values (:link_id, :userId, :blobId)");
    $newStmt->execute(array(":link_id" => $link_id, ":userId" => $user_id, ":blobId" => $blob_id));

    return $file;
}

function do_post($pdo, $p, $link_id, $user_id) {
    $param_name = 'files';//$this->options['param_name']
    $upload = get_upload_data($param_name);

    // Parse the Content-Disposition header, if available:
    $content_disposition_header = get_server_var('HTTP_CONTENT_DISPOSITION');
    $file_name = $content_disposition_header ?
        rawurldecode(preg_replace(
            '/(^[^"]+")|("$)/',
            '',
            $content_disposition_header
        )) : null;
    // Parse the Content-Range header, which has the following form:
    // Content-Range: bytes 0-524287/2000000
    $content_range_header = get_server_var('HTTP_CONTENT_RANGE');
    $content_range = $content_range_header ?
        preg_split('/[^0-9]+/', $content_range_header) : null;
    $size =  @$content_range[3];
    $files = array();

    if ($upload) {

        if (is_array($upload['tmp_name'])) {
            echo "array: " . count($upload['tmp_name']);;
            // param_name is an array identifier like "files[]",
            // $upload is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range, $pdo, $p, $link_id, $user_id
                );
            }

        } else {
            print "single file:\n\r";
            // print json_encode($upload);
            // param_name is a single object identifier like "file",
            // $upload is a one-dimensional array:
            $files[] = handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                $file_name ? $file_name : (isset($upload['name']) ? $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ? $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ? $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range, $pdo, $p, $link_id, $user_id
            );
        }
    }
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
    case 'HEAD':
        break;
    case 'GET':
        break;
    case 'PATCH':
    case 'PUT':
    case 'POST':
        do_post($PDOX, $p, $LINK->id, $USER->id);
        break;
    case 'DELETE':
        break;
    default:
        echo 'HTTP/1.1 405 Method Not Allowed';
}