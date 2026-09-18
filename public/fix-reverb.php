<?php
// Upload this file to Hostinger public_html, run once: php fix-reverb.php, then delete it

$scaling = <<<'PHP'
    'scaling' => [
        'enabled' => false,
        'channel' => 'reverb',
        'server' => [
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '6379',
            'username' => null,
            'password' => null,
            'database' => '0',
            'timeout' => 60,
        ],
    ],
PHP;

$file = __DIR__ . '/config/reverb.php';
$content = '<?php
return [
    \'default\' => env(\'REVERB_SERVER\', \'reverb\'),
    \'servers\' => [
        \'reverb\' => [
            \'host\' => env(\'REVERB_SERVER_HOST\', \'0.0.0.0\'),
            \'port\' => env(\'REVERB_SERVER_PORT\', 8080),
            \'path\' => env(\'REVERB_SERVER_PATH\', \'\'),
            \'hostname\' => env(\'REVERB_HOST\'),
            \'options\' => [\'tls\' => []],
            \'max_request_size\' => env(\'REVERB_MAX_REQUEST_SIZE\', 10000),
' . $scaling . '
            \'pulse_ingest_interval\' => env(\'REVERB_PULSE_INGEST_INTERVAL\', 15),
            \'telescope_ingest_interval\' => env(\'REVERB_TELESCOPE_INGEST_INTERVAL\', 15),
        ],
    ],
    \'apps\' => [
        \'provider\' => \'config\',
        \'apps\' => [[
            \'key\' => env(\'REVERB_APP_KEY\'),
            \'secret\' => env(\'REVERB_APP_SECRET\'),
            \'app_id\' => env(\'REVERB_APP_ID\'),
            \'options\' => [\'host\' => env(\'REVERB_HOST\')],
            \'allowed_origins\' => [\'*\'],
            \'ping_interval\' => env(\'REVERB_APP_PING_INTERVAL\', 60),
            \'max_message_size\' => env(\'REVERB_APP_MAX_MESSAGE_SIZE\', 10000),
        ]],
    ],
];';

if (file_put_contents($file, $content)) {
    echo "SUCCESS: config/reverb.php fixed\n";
} else {
    echo "FAILED: Could not write config/reverb.php\n";
}
