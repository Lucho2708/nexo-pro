<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SystemSettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('SuperAdmin/Settings/Index', [
            'settings' => [
                '2fa_enabled' => GlobalSetting::get('2fa_enabled', true),
                'maintenance_mode' => GlobalSetting::get('maintenance_mode', false),
                'log_retention_days' => GlobalSetting::get('log_retention_days', 30),
                'audit_retention_days' => GlobalSetting::get('audit_retention_days', 90),
                'allow_new_registrations' => GlobalSetting::get('allow_new_registrations', true),
                'system_announcements' => GlobalSetting::get('system_announcements', true),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            '2fa_enabled' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
            'log_retention_days' => 'nullable|integer|min:1|max:365',
            'audit_retention_days' => 'nullable|integer|min:1|max:1000',
            'allow_new_registrations' => 'nullable|boolean',
            'system_announcements' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            GlobalSetting::set($key, $value);
        }

        return back()->with('success', 'Núcleo de configuración actualizado exitosamente.');
    }
}
