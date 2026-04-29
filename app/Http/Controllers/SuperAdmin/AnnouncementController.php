<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Repositories\Interfaces\AnnouncementRepositoryInterface;
use App\Http\Requests\SuperAdmin\StoreGlobalAnnouncementRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function __construct(
        private AnnouncementRepositoryInterface $repository
    ) {}

    public function index()
    {
        return Inertia::render('SuperAdmin/Announcements/Index', [
            'announcements' => $this->repository->getAllLatest(),
        ]);
    }

    public function store(StoreGlobalAnnouncementRequest $request)
    {
        $validated = $request->validated();

        $this->repository->create($validated);

        return back()->with('success', 'Anuncio publicado correctamente.');
    }

    public function update(StoreGlobalAnnouncementRequest $request, Announcement $announcement)
    {
        $validated = $request->validated();

        $this->repository->update($announcement->id, $validated);
        return back()->with('success', 'Anuncio actualizado.');
    }

    public function destroy(Announcement $announcement)
    {
        $this->repository->delete($announcement->id);
        return back()->with('success', 'Anuncio eliminado.');
    }
}
