<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\IAM\Models\User;

class TransferManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'new_admin_id' => ['required', Rule::exists(User::class, 'id')],
            'old_admin_id' => ['nullable', Rule::exists(User::class, 'id')],
        ];
    }
}
