<?php

namespace App\Repositories\Eloquent;

use App\Modules\Operations\Models\Announcement;
use App\Repositories\Interfaces\AnnouncementRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentAnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function getAllLatest(): Collection
    {
        return Announcement::latest()->get();
    }

    public function create(array $data): Announcement
    {
        return Announcement::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $announcement = Announcement::findOrFail($id);
        return $announcement->update($data);
    }

    public function delete(string $id): bool
    {
        $announcement = Announcement::findOrFail($id);
        return $announcement->delete();
    }
}
