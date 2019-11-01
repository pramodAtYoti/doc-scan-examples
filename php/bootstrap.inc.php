<?php
session_start();

require_once 'vendor/autoload.php';

define('YOTI_IFRAME_URL', getenv('YOTI_IFRAME_URL'));

$client = new Yoti\DocScan\Client(
    getenv('YOTI_SDK_ID'),
    file_get_contents(getenv('YOTI_KEY_FILE_PATH')),
    getenv('YOTI_API_BASE_URL')
);