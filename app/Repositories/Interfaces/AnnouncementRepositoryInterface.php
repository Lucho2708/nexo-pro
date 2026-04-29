<?php

namespace App\Repositories\Interfaces;

use App\Models\Announcement;
use Illuminate\Support\Collection;

interface AnnouncementRepositoryInterface
{
    public function getAllLatest(): Collection;
    
    public function create(array $data): Announcement;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
}
