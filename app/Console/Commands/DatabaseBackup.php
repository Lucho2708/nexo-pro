<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza un respaldo de la base de datos y lo guarda en el almacenamiento local.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de respaldo de seguridad...');

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver !== 'mysql') {
            $this->warn("El comando de respaldo actualmente solo está optimizado para MySQL. Driver actual: {$driver}");
            return;
        }

        $databaseName = config("database.connections.{$connection}.database");
        $userName = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");
        $host = config("database.connections.{$connection}.host");

        $fileName = "backups/nexo_pro_backup_" . now()->format('Y-m-d_H-i-s') . ".sql";
        $folderPath = storage_path('app/private/backups');
        $filePath = "{$folderPath}/" . basename($fileName);

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($userName),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($databaseName),
            escapeshellarg($filePath)
        );

        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("¡Respaldo exitoso! Archivo generado: {$fileName}");
            Log::info("Backup de base de datos generado correctamente.", ['file' => $fileName]);
            
            // Recomendación: Subir a almacenamiento en la nube (S3 / Google Cloud Storage)
            // Storage::disk('s3')->put($fileName, file_get_contents($filePath));
        } else {
            $this->error("Error al ejecutar mysqldump. Verifica las credenciales y permisos.");
            Log::error("Fallo el respaldo automático de la base de datos. Código: {$returnVar}");
        }
    }
}
