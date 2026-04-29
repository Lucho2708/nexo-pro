<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class TransferManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'new_admin_id' => 'required|exists:users,id',
            'old_admin_id' => 'nullable|exists:users,id',
        ];
    }
}
