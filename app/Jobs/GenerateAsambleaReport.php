<?php

namespace App\Jobs;

use App\Models\Asamblea;
use App\Models\User;
use App\Services\AsambleaReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateAsambleaReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Asamblea $asamblea,
        public User $admin
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AsambleaReportService $reportService): void
    {
        try {
            $pdf = $reportService->generatePdf($this->asamblea);
            $fileName = "reports/asamblea_{$this->asamblea->id}_" . now()->timestamp . ".pdf";
            
            Storage::disk('local')->put($fileName, $pdf->output());

            Log::info("Reporte de asamblea {$this->asamblea->id} generado exitosamente por Job.", [
                'admin_id' => $this->admin->id,
                'file' => $fileName
            ]);

            // Aquí se podría disparar una notificación real al admin
            // $this->admin->notify(new \App\Notifications\AsambleaReportGenerated($this->asamblea, $fileName));
            
        } catch (\Throwable $e) {
            Log::error("Fallo al generar reporte de asamblea {$this->asamblea->id} en Job: " . $e->getMessage());
            throw $e;
        }
    }
}
