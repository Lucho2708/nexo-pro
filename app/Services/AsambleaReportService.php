<?php

namespace App\Services;

use App\Models\Asamblea;
use App\Models\Pregunta;
use App\Models\Voto;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AsambleaReportService
{
    public function __construct(
        protected AsambleaService $asambleaService
    ) {}

    /**
     * Generate the audit report PDF for an assembly.
     */
    public function generatePdf(Asamblea $asamblea)
    {
        $data = $this->prepareReportData($asamblea);

        return Pdf::loadView('pdf.asamblea-audit', $data);
    }

    /**
     * Prepare all necessary data for the report.
     */
    public function prepareReportData(Asamblea $asamblea): array
    {
        $tableName = $asamblea->getLogTableName();
        $this->asambleaService->ensureLogTableExists($tableName);

        // 1. Get unique participants from dynamic logs with names and unit details
        $participants = DB::table($tableName)
            ->join('users', 'users.id', '=', "{$tableName}.user_id")
            ->join('unidades', 'unidades.id', '=', "{$tableName}.unidad_id")
            ->where("{$tableName}.event_type", 'login')
            ->select([
                "{$tableName}.user_id", 
                "{$tableName}.unidad_id", 
                "{$tableName}.ip_address", 
                "{$tableName}.created_at",
                'users.name as user_name',
                'unidades.nombre as unit_name',
                'unidades.torre as unit_tower'
            ])
            ->get()
            ->unique('unidad_id');

        // 2. Get detailed voting results
        $results = $asamblea->preguntas()->with(['opciones', 'votos'])->get()->map(function ($pregunta) {
            $totalPeso = $pregunta->votos->sum('peso');
            
            $opcionesResult = $pregunta->opciones->map(function ($opcion) use ($totalPeso) {
                $votosOpcion = $opcion->votos;
                $pesoOpcion = $votosOpcion->sum('peso');
                
                return [
                    'titulo' => $opcion->titulo,
                    'cantidad' => $votosOpcion->count(),
                    'peso' => $pesoOpcion,
                    'porcentaje' => $totalPeso > 0 ? ($pesoOpcion / $totalPeso) * 100 : 0
                ];
            });

            return [
                'titulo' => $pregunta->titulo,
                'total_votos' => $pregunta->votos->count(),
                'total_peso' => $totalPeso,
                'opciones' => $opcionesResult
            ];
        });

        return [
            'asamblea' => $asamblea,
            'copropiedad' => $asamblea->copropiedad,
            'participants' => $participants,
            'results' => $results,
            'generated_at' => now(),
        ];
    }
}
