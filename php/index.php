<?php
require_once 'bootstrap.inc.php';

try {
    $response = $client->createSession([
        "client_session_token_ttl" => 600,
        "resources_ttl" => 604800,
        "user_tracking_id" => session_id(),
        "notifications" => [
            "endpoint" => "https://yourdomain.com/idverify/updates",
            "topics" => [
                "session_completion"
            ],
            "auth_token" => "XXX"
        ],
        "requested_checks" => [
            [
                "type" => "ID_DOCUMENT_AUTHENTICITY",
                "config" => (object) []
            ],
        ],
        "requested_tasks" => [
            [
                "type" => "ID_DOCUMENT_TEXT_DATA_EXTRACTION",
                "config" => [
                    "manual_check" => "NEVER" // | "FALLBACK" | "ALWAYS"
                ]
            ]
        ],
        "sdk_config" => [
            "capture_method" => "CAMERA_AND_UPLOAD", // | "CAMERA"
            "primary_colour" => "#2d9fff",
            "secondary_colour" => "#FFFFFF",
            "font_colour" => "#FFFFFF",
            "locale" => "en-GB",
            "preset_issuing_country" => "GBR",
            "success_url" => 'https://' . $_SERVER['HTTP_HOST'] . '/success.php',
            "error_url" => 'https://' . $_SERVER['HTTP_HOST'] . '/error.php'
        ]
    ]);

    $session = json_decode($response->getBody());
    $_SESSION['YOTI_SESSION_ID'] = $session->session_id;
    $_SESSION['YOTI_SESSION_TOKEN'] = $session->client_session_token;

    $iframe_url = YOTI_IFRAME_URL . '?' . http_build_query([
        'sessionID' => $_SESSION['YOTI_SESSION_ID'],
        'sessionToken' => $_SESSION['YOTI_SESSION_TOKEN'],
    ]);
} catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Doc Scan Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="/" class="navbar-brand">Yoti Doc Scan</a>
    </nav>
    <iframe src="<?php echo htmlspecialchars($iframe_url); ?>" width="100%" height="750" allow="camera" style="border:none;"></iframe>
</body>

</html>