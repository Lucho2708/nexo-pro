<?php

namespace App\Logging;

use Monolog\Logger;

class DatabaseLogger
{
    /**
     * Crea una instancia customizada de Monolog.
     */
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        
        // Agregar nuestro Handler personalizado que guarda en DB
        $logger->pushHandler(new DatabaseHandler());

        // Aseguramos que también incluya las excepciones detalladas, etc, si así lo deseas
        return $logger;
    }
}
