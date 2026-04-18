<?php
function getProxy($url) {
    // Essai direct (chez soi)
    $response = @file_get_contents($url);

    // Si ça échoue, on essaie via le proxy du lycée
    if ($response === false) {
        $options = [
            'http' => [
                'proxy' => 'tcp://172.16.0.54:8080',
                'request_fulluri' => true,
            ],
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
    }

    if ($response === false) {
        echo "Failed to get data from $url";
        return null;
    }

    return $response;
}
?>