<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validasi bulan untuk minimal bulan 1 dan maksimal bulan 12.
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            // Validasi tahun untuk maksimal tahun di tahun sekarang.
            'year'  => ['required', 'integer', 'min:2000', 'max:' . now()->year],
        ];
    }
}
