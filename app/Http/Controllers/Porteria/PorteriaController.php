<?php

namespace App\Http\Controllers\Porteria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarReciboDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\MinutaPorteria;
use App\Modules\Property\Models\Unidad;
use Illuminate\Support\Facades\Auth;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Controlador central del módulo táctico de garita y portería.
 * Proporciona endpoints de alta velocidad para registro de visitas, correspondencia,
 * recibos públicos y aforo vehicular en tiempo real.
 *
 * [ASPECTO TÉCNICO / UI]
 * Controlador desacoplado que inyecta PorteriaServiceInterface bajo principios SOLID (DIP/SRP).
 * Integra vistas Inertia optimizadas para pantallas táctiles de portería.
 */
class PorteriaController extends Controller
{
    public function __construct(
        private PorteriaServiceInterface $porteriaService
    ) {}

    public function dashboard(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $visitasActivas = Visita::where('copropiedad_id', $copropiedadId)
            ->where('estado', 'ingresado')
            ->count();

        $paquetesPendientes = Paquete::where('copropiedad_id', $copropiedadId)
            ->whereIn('estado', ['recibido', 'notificado'])
            ->count();

        $recibosEnCasillero = ReciboPublico::where('copropiedad_id', $copropiedadId)
            ->where('estado', 'en_casillero')
            ->count();

        $vehiculosActivos = ParqueaderoRegistro::where('copropiedad_id', $copropiedadId)
            ->where('estado', 'activo')
            ->count();

        $capacidadVisitantes = (int) ($user->currentCopropiedad?->settings['parqueaderos_visitantes'] ?? 20);
        $aforo = $this->porteriaService->calcularDisponibilidadParqueadero($capacidadVisitantes, $vehiculosActivos);

        return Inertia::render('Porteria/Dashboard', [
            'metrics' => [
                'visitas_activas' => $visitasActivas,
                'paquetes_pendientes' => $paquetesPendientes,
                'recibos_en_casillero' => $recibosEnCasillero,
                'parqueaderos' => $aforo,
            ],
            'copropiedad' => $user->currentCopropiedad,
        ]);
    }

    public function visitas(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $visitas = Visita::where('copropiedad_id', $copropiedadId)
            ->with(['unidad:id,nombre,bloque'])
            ->latest('ingreso_at')
            ->take(30)
            ->get();

        $unidades = Unidad::where('copropiedad_id', $copropiedadId)
            ->select('id', 'nombre', 'bloque')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Porteria/Visitas/Index', [
            'visitas' => $visitas,
            'unidades' => $unidades,
        ]);
    }

    public function registrarIngreso(Request $request)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|uuid',
            'visitante_nombre' => 'required|string|max:255',
            'visitante_documento' => 'required|string|max:50',
            'tipo_visita' => 'nullable|string|in:personal,domicilio,contratista,taxi',
            'placa_vehiculo' => 'nullable|string|max:20',
            'empresa_servicio' => 'nullable|string|max:100',
            'pre_autorizado' => 'nullable|boolean',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $validated['copropiedad_id'] = $user->current_copropiedad_id;

        $dto = RegistrarVisitaDTO::fromRequest($validated, $user->id);
        $this->porteriaService->registrarVisita($dto);

        return redirect()->back()->with('success', 'Visitante registrado exitosamente.');
    }

    public function registrarSalida(Visita $visita)
    {
        $this->porteriaService->marcarSalidaVisita($visita);
        return redirect()->back()->with('success', 'Salida de visitante registrada.');
    }

    public function recibos(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $recibos = ReciboPublico::where('copropiedad_id', $copropiedadId)
            ->with(['unidad:id,nombre,bloque'])
            ->latest('created_at')
            ->take(50)
            ->get();

        $unidades = Unidad::where('copropiedad_id', $copropiedadId)
            ->select('id', 'nombre', 'bloque')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Porteria/Recibos/Index', [
            'recibos' => $recibos,
            'unidades' => $unidades,
        ]);
    }

    public function storeRecibo(Request $request)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|uuid',
            'tipo_servicio' => 'required|string|in:acueducto,energia,gas,telecomunicaciones,otros',
            'empresa_emisora' => 'nullable|string|max:100',
            'periodo_facturado' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $validated['copropiedad_id'] = $user->current_copropiedad_id;

        $dto = RegistrarReciboDTO::fromRequest($validated, $user->id);
        $this->porteriaService->registrarRecibo($dto);

        return redirect()->back()->with('success', 'Recibo público radicado en casillero.');
    }

    public function paquetes(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $paquetes = Paquete::where('copropiedad_id', $copropiedadId)
            ->with(['unidad:id,nombre,bloque'])
            ->latest('created_at')
            ->take(40)
            ->get();

        $unidades = Unidad::where('copropiedad_id', $copropiedadId)
            ->select('id', 'nombre', 'bloque')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Porteria/Paquetes/Index', [
            'paquetes' => $paquetes,
            'unidades' => $unidades,
        ]);
    }

    public function storePaquete(Request $request)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|uuid',
            'empresa_mensajeria' => 'required|string|max:100',
            'codigo_guia' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $validated['copropiedad_id'] = $user->current_copropiedad_id;

        $dto = RegistrarPaqueteDTO::fromRequest($validated, $user->id);
        $this->porteriaService->recepcionarPaquete($dto);

        return redirect()->back()->with('success', 'Paquete registrado en garita.');
    }

    public function entregarPaquete(Request $request, Paquete $paquete)
    {
        $validated = $request->validate([
            'entregado_a_nombre' => 'required|string|max:255',
        ]);

        $this->porteriaService->entregarPaquete($paquete, $validated['entregado_a_nombre']);

        return redirect()->back()->with('success', 'Paquete entregado satisfactoriamente.');
    }

    public function parqueaderos(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $registros = ParqueaderoRegistro::where('copropiedad_id', $copropiedadId)
            ->where('estado', 'activo')
            ->with(['unidad:id,nombre'])
            ->latest('ingreso_at')
            ->get();

        $capacidad = (int) ($user->currentCopropiedad?->settings['parqueaderos_visitantes'] ?? 20);
        $disponibilidad = $this->porteriaService->calcularDisponibilidadParqueadero($capacidad, $registros->count());

        return Inertia::render('Porteria/Parqueaderos/Index', [
            'registros' => $registros,
            'disponibilidad' => $disponibilidad,
        ]);
    }

    public function storeParqueadero(Request $request)
    {
        $validated = $request->validate([
            'placa_vehiculo' => 'required|string|max:20',
            'tipo_espacio' => 'required|string|in:visitante,comunal,asignado',
            'unidad_id' => 'nullable|uuid',
            'numero_bahia' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $validated['copropiedad_id'] = $user->current_copropiedad_id;

        $dto = RegistrarParqueaderoDTO::fromRequest($validated, $user->id);
        $this->porteriaService->registrarIngresoVehiculo($dto);

        return redirect()->back()->with('success', 'Ingreso de vehículo registrado.');
    }

    public function salidaParqueadero(ParqueaderoRegistro $registro)
    {
        $this->porteriaService->marcarSalidaVehiculo($registro);
        return redirect()->back()->with('success', 'Salida vehicular registrada.');
    }

    public function minuta(Request $request): Response
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $minutas = MinutaPorteria::where('copropiedad_id', $copropiedadId)
            ->with(['guarda:id,name'])
            ->latest('created_at')
            ->take(50)
            ->get();

        return Inertia::render('Porteria/Minuta/Index', [
            'minutas' => $minutas,
        ]);
    }

    public function storeMinuta(Request $request)
    {
        $validated = $request->validate([
            'tipo_evento' => 'required|string|in:cambio_turno,novedad,ronda,incidente,aviso',
            'prioridad' => 'required|string|in:informativo,advertencia,urgente',
            'descripcion' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        MinutaPorteria::create([
            'copropiedad_id' => $user->current_copropiedad_id,
            'guarda_id' => $user->id,
            'tipo_evento' => $validated['tipo_evento'],
            'prioridad' => $validated['prioridad'],
            'descripcion' => $validated['descripcion'],
        ]);

        return redirect()->back()->with('success', 'Novedad de minuta registrada.');
    }
}
