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
use NexoPro\Porteria\DTOs\RelevarTurnoDTO;
use NexoPro\Porteria\DTOs\ValidarPaseVisitaDTO;
use NexoPro\Porteria\DTOs\SolicitarCitofoniaDTO;
use NexoPro\Porteria\DTOs\PoliticaTarifariaDTO;
use NexoPro\Porteria\DTOs\RegistrarMinutaForenseDTO;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Str;
use DomainException;
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

    public function validarPase(Request $request)
    {
        $validated = $request->validate([
            'token_o_pin' => 'required|string|max:100',
            'visitante_nombre' => 'nullable|string|max:255',
            'visitante_documento' => 'nullable|string|max:50',
            'placa_vehiculo' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $dto = new ValidarPaseVisitaDTO(
            tokenOPin: $validated['token_o_pin'],
            guardaId: (string) $user->id,
            visitanteNombre: $validated['visitante_nombre'] ?? null,
            visitanteDocumento: $validated['visitante_documento'] ?? null,
            placaVehiculo: $validated['placa_vehiculo'] ?? null
        );

        try {
            $visita = $this->porteriaService->validarIngresoConPase($dto);
            return redirect()->back()->with('success', "Acceso autorizado para {$visita->visitante_nombre} mediante Pase QR.");
        } catch (DomainException $e) {
            return redirect()->back()->withErrors(['token_o_pin' => $e->getMessage()]);
        }
    }

    public function solicitarCitofonia(Request $request)
    {
        $validated = $request->validate([
            'unidad_id' => 'required|uuid',
            'visitante_nombre' => 'required|string|max:255',
            'visitante_documento' => 'nullable|string|max:50',
            'tipo_visita' => 'nullable|string|in:personal,domicilio,contratista,taxi',
        ]);

        $user = Auth::user();
        $dto = new SolicitarCitofoniaDTO(
            copropiedadId: (string) $user->current_copropiedad_id,
            unidadId: $validated['unidad_id'],
            guardaId: (string) $user->id,
            visitanteNombre: $validated['visitante_nombre'],
            visitanteDocumento: $validated['visitante_documento'] ?? null,
            tipoVisita: $validated['tipo_visita'] ?? 'personal'
        );

        $solicitud = $this->porteriaService->solicitarCitofoniaVirtual($dto);

        return redirect()->back()->with('success', 'Llamada de citofonía virtual enviada al residente. Esperando respuesta...');
    }

    public function responderCitofonia(Request $request, string $token)
    {
        $validated = $request->validate([
            'respuesta' => 'required|string|in:autorizado,denegado',
        ]);

        $user = Auth::user();

        try {
            $solicitud = $this->porteriaService->responderCitofoniaVirtual(
                tokenRespuesta: $token,
                respuesta: $validated['respuesta'],
                respondidoPorId: (string) ($user?->id ?? Str::uuid())
            );

            return response()->json([
                'success' => true,
                'estado' => $solicitud->estado,
                'mensaje' => $solicitud->estado === 'autorizado' 
                    ? 'Ingreso de visitante autorizado exitosamente.' 
                    : 'Ingreso denegado.',
            ]);
        } catch (DomainException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
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
            'pin' => 'nullable|string|regex:/^[0-9]{4}$/',
        ]);

        if (!empty($validated['pin'])) {
            try {
                $this->porteriaService->entregarPaqueteConPin($paquete, $validated['pin'], $validated['entregado_a_nombre']);
            } catch (DomainException $e) {
                return redirect()->back()->withErrors(['pin' => $e->getMessage()]);
            }
        } else {
            $this->porteriaService->entregarPaquete($paquete, $validated['entregado_a_nombre']);
        }

        return redirect()->back()->with('success', 'Paquete entregado satisfactoriamente.');
    }

    public function relevarTurno(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|regex:/^[0-9]{4}$/',
        ]);

        $user = Auth::user();
        $dto = new RelevarTurnoDTO(
            copropiedadId: (string) $user->current_copropiedad_id,
            guardaSalienteId: (string) $user->id,
            guardaSalienteNombre: (string) $user->name,
            pinEntrante: $validated['pin']
        );

        try {
            $resultado = $this->porteriaService->relevarTurno($dto);

            if (!empty($resultado['guarda_entrante_id'])) {
                $nuevoGuarda = User::find($resultado['guarda_entrante_id']);
                if ($nuevoGuarda) {
                    Auth::login($nuevoGuarda);
                }
            }

            return redirect()->back()->with('success', "Relevo de turno exitoso. Bienvenido, {$resultado['guarda_entrante_nombre']}.");
        } catch (DomainException $e) {
            return redirect()->back()->withErrors(['pin' => $e->getMessage()]);
        }
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

        $capacidad = (int) ($user->currentCopropiedad?->settings['parqueaderos_visitantes'] ?? 20);
        $dto = RegistrarParqueaderoDTO::fromRequest($validated, $user->id);

        try {
            $this->porteriaService->registrarIngresoVehiculoConCapacidad($dto, $capacidad);
            return redirect()->back()->with('success', 'Ingreso de vehículo registrado.');
        } catch (DomainException $e) {
            return redirect()->back()->withErrors(['placa_vehiculo' => $e->getMessage()]);
        }
    }

    public function liquidarParqueadero(ParqueaderoRegistro $registro)
    {
        $user = Auth::user();
        $politica = PoliticaTarifariaDTO::fromSettings($user->currentCopropiedad?->settings);
        $liquidacion = $this->porteriaService->liquidarTarifaVehiculo($registro, $politica);

        return response()->json([
            'success' => true,
            'liquidacion' => $liquidacion,
        ]);
    }

    public function procesarPagoParqueadero(Request $request, ParqueaderoRegistro $registro)
    {
        $validated = $request->validate([
            'metodo_pago' => 'required|string|in:wompi_pse,wompi_tarjeta,efectivo,cortesia',
        ]);

        $referencia = $registro->referencia_pago ?? ('PKG-' . Str::upper(Str::random(10)));
        $this->porteriaService->procesarPagoParqueadero($registro, $validated['metodo_pago'], $referencia);

        return redirect()->back()->with('success', 'Pago de parqueadero registrado satisfactoriamente.');
    }

    public function salidaParqueadero(ParqueaderoRegistro $registro)
    {
        try {
            $this->porteriaService->marcarSalidaVehiculoConValidacionPago($registro);
            return redirect()->back()->with('success', 'Salida vehicular registrada.');
        } catch (DomainException $e) {
            return redirect()->back()->withErrors(['salida' => $e->getMessage()]);
        }
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

        $auditoria = $this->porteriaService->verificarIntegridadCadenaMinuta($copropiedadId);

        return Inertia::render('Porteria/Minuta/Index', [
            'minutas' => $minutas,
            'auditoria' => $auditoria,
        ]);
    }

    public function storeMinuta(Request $request)
    {
        $validated = $request->validate([
            'tipo_evento' => 'required|string|in:cambio_turno,novedad,ronda,incidente,aviso',
            'prioridad' => 'required|string|in:informativo,advertencia,urgente',
            'descripcion' => 'required|string|max:2000',
            'evidencia_foto' => 'nullable|image|max:10240',
        ]);

        $user = Auth::user();
        $fotoPath = null;

        if ($request->hasFile('evidencia_foto')) {
            $fotoPath = $request->file('evidencia_foto')->store('porteria/minutas', 'public');
        }

        $dto = new RegistrarMinutaForenseDTO(
            copropiedadId: $user->current_copropiedad_id,
            guardaId: $user->id,
            tipoEvento: $validated['tipo_evento'],
            prioridad: $validated['prioridad'],
            descripcion: $validated['descripcion'],
            evidenciaFotoPath: $fotoPath
        );

        $minuta = $this->porteriaService->registrarMinutaForense($dto);

        return redirect()->back()->with('success', 'Novedad de minuta firmada criptográficamente (Hash: ' . substr($minuta->hash_inmutable, 0, 12) . '...).');
    }

    public function supervision(Request $request): Response
    {
        $user = Auth::user();
        $dashboard = $this->porteriaService->obtenerDashboardSupervisionB2B($user->id);

        return Inertia::render('Porteria/Supervision/Index', [
            'dashboard' => $dashboard,
        ]);
    }
}
