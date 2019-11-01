<?php
require_once 'bootstrap.inc.php';

try {
    $response = $client->getSessionMedia($_SESSION['YOTI_SESSION_ID'], $_GET['id']);
    header('Content-type:image/jpg');
    echo $response->getBody();
    exit;
} catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
}
