<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\TransferPropertyManagementAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\TransferManagementRequest;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Inertia\Inertia;

class CopropiedadManagementController extends Controller
{
    /**
     * Show the management view for a specific property.
     */
    public function manage(Copropiedad $copropiedad)
    {
        $copropiedad->loadCount(['users', 'unidades']);
        $admins = User::where('role', 'admin')->get(['id', 'name', 'email']);
        $currentAdmins = $copropiedad->administradores;

        return Inertia::render('SuperAdmin/Licenses/Management', [
            'copropiedad' => $copropiedad,
            'availableAdmins' => $admins,
            'currentAdmins' => $currentAdmins,
        ]);
    }

    /**
     * Transfer management of a single property.
     */
    public function transfer(TransferManagementRequest $request, Copropiedad $copropiedad, TransferPropertyManagementAction $action)
    {
        $action->execute(
            $copropiedad, 
            $request->old_admin_id, 
            $request->new_admin_id
        );

        return back()->with('success', 'La transferencia de mando se ha realizado con éxito y se ha notificado al nuevo administrador.');
    }
}
