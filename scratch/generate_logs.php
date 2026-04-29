<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Log;

$levels = ['info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];
foreach ($levels as $level) {
    for ($i = 1; $i <= 20; $i++) {
        $message = 'Simulación de ' . strtoupper($level) . ' #' . $i . ': Proceso de prueba ' . bin2hex(random_bytes(4));
        $context = [
            'iteracion' => $i,
            'modulo' => 'AuditoriaTest',
            'sistema' => 'NexoPro_Core'
        ];
        Log::log($level, $message, $context);
    }
}

echo "Generados 140 logs exitosamente.\n";
