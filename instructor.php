<?php
require_once "../config.php";
include 'tool-config_dist.php';
include 'src/Template.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
// stop PHP from automatically embedding PHPSESSID on local URLs
ini_set('session.use_trans_sid', false);
error_reporting(E_ALL);

use \Tsugi\Util\U;
use \Tsugi\Util\Net;
use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;
use \Tsugi\UI\SettingsForm;
use \Tsugi\Blob\BlobUtil;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();
$p = $CFG->dbprefix;

$upload_max_size_bytes = BlobUtil::maxUploadBytes();
$upload_max_size = U::displaySize($upload_max_size_bytes);

$menu = false; // We are not using a menu

// $postdata = LTIX::ltiRawPostArray();

$EID = $LAUNCH->ltiRawParameter('ext_d2l_username', $LAUNCH->ltiRawParameter('lis_person_sourcedid', $USER->id));
$context = [
    'instructor' => $USER->instructor,
    'EID'        => $EID,
    'context_id' => $CONTEXT->id,
    // 'postdata'   => $postdata,
    'styles'     => [ addSession('static/css/app.min.css'),
                        addSession('static/css/blueimp-gallery.min.css'),
                        addSession('static/css/jquery.fileupload.css'), addSession('static/css/jquery.fileupload-ui.css'),
                     ],
    'scripts'    => [ addSession('static/js/vendor/jquery.ui.widget.js'), addSession('static/js/tmpl.min.js'),
                        addSession('static/js/load-image.all.min.js'),
                        addSession('static/js/canvas-to-blob.min.js'),
                        addSession('static/js/jquery.blueimp-gallery.min.js'),
                        addSession('static/js/jquery.iframe-transport.js'),
                        addSession('static/js/jquery.fileupload.js'),
                        addSession('static/js/jquery.fileupload-process.js'),
                        addSession('static/js/jquery.fileupload-image.js'),
                        addSession('static/js/jquery.fileupload-audio.js'),
                        addSession('static/js/jquery.fileupload-video.js'),
                        addSession('static/js/jquery.fileupload-validate.js'),
                        addSession('static/js/jquery.fileupload-ui.js'),
                    ],
    'max-size'   => $upload_max_size,
    'manage_url'      => addSession( str_replace("\\","/",$CFG->getCurrentFileUrl('actions/manage.php')) ),
    'simple_url'      => addSession( str_replace("\\","/",$CFG->getCurrentFileUrl('actions/simple.php')) )
];

if (!$USER->instructor) {
    header('Location: ' . addSession('student.php'));
}

// Start of the output
$OUTPUT->header();

Template::view('templates/header.html', $context);

$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);

if ($tool['debug']) {
    echo '<pre>'; print_r($context); echo '</pre>';
}

Template::view('templates/instructor-body.html', $context);
// Template::view('templates/simple.html', $context);

$OUTPUT->footerStart();

Template::view('templates/instructor-footer.html', $context);
include('templates/instructor-tmpl.html');

$OUTPUT->footerEnd();
?>