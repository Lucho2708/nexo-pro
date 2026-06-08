<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Modules\Operations\Models\Announcement;
use App\Repositories\Interfaces\AnnouncementRepositoryInterface;
use App\Traits\Auditable;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    use Auditable;

    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    public function index()
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        if (!$copropiedadId) {
            return redirect()->route('dashboard')->with('error', 'Seleccione un conjunto primero.');
        }

        // Obtener anuncios específicos de este conjunto + globales (solo lectura)
        $announcements = Announcement::where('copropiedad_id', $copropiedadId)
            ->orWhereNull('copropiedad_id')
            ->latest()
            ->get();

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
            'can_create' => true
        ]);
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $user = Auth::user();
        $copropiedadId = $user->current_copropiedad_id;

        $validated = $request->validated();

        // Forzar propiedad y autor
        $validated['copropiedad_id'] = $copropiedadId;
        $validated['user_id'] = $user->id;
        $validated['is_active'] = true;

        $announcement = $this->repository->create($validated);

        $this->audit('ANUNCIOS', 'PUBLICACION_NUEVA', [
            'titulo' => $announcement->title,
        ]);

        return back()->with('success', 'Anuncio publicado para su conjunto.');
    }

    public function destroy(Announcement $announcement)
    {
        $user = Auth::user();
        
        // Solo puede borrar sus propios anuncios o los de su conjunto
        if ($announcement->copropiedad_id !== $user->current_copropiedad_id) {
            abort(403);
        }

        $this->repository->delete($announcement->id);

        $this->audit('ANUNCIOS', 'ELIMINACION_ANUNCIO', [
            'titulo' => $announcement->title,
        ]);

        return back()->with('success', 'Anuncio eliminado.');
    }
}
