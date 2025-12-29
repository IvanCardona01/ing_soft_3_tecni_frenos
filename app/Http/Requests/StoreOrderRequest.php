<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'folio_number' => ['required', 'digits:6', Rule::unique('orders', 'folio_number')],

            'client_full_name' => ['required', 'string', 'max:150'],
            'client_document_type' => ['required', 'string', Rule::in(['cc', 'nit', 'ti'])],
            'client_document_number' => ['required', 'string', 'max:50'],
            'client_phone' => ['required', 'string', 'max:30'],
            'client_address' => ['required', 'string', 'max:180'],

            'driver_name' => ['nullable', 'string', 'max:150'],
            'driver_phone' => ['nullable', 'string', 'max:30'],
            'driver_email' => ['nullable', 'email', 'max:120'],

            'vehicle_brand' => ['required', 'string', 'max:80'],
            'vehicle_year' => ['required', 'integer', 'digits:4'],
            'vehicle_plate' => ['required', 'string', 'max:15'],
            'vehicle_cilindraje' => ['required', 'string', 'max:20'],
            'vehicle_model' => ['required', 'string', 'max:80'],
            'vehicle_vin' => ['required', 'string', 'max:30'],
            'vehicle_motor' => ['required', 'string', 'max:30'],
            'vehicle_kilometraje' => ['required', 'integer', 'min:0'],
            'vehicle_observaciones' => ['nullable', 'string'],

            'ingreso_en_grua' => ['required', 'boolean'],
            'gasolina' => ['required', 'integer', 'between:0,100'],
            'testigos' => ['nullable', 'json'],
            'assigned_technician' => ['nullable', 'integer', 'exists:users,id'],

            'observation_damage_front' => ['nullable', 'string'],
            'observation_damage_behind' => ['nullable', 'string'],
            'observation_damage_left_side' => ['nullable', 'string'],
            'observation_damage_right_side' => ['nullable', 'string'],
            'damage_points_front' => ['nullable', 'json'],
            'damage_points_behind' => ['nullable', 'json'],
            'damage_points_left_side' => ['nullable', 'json'],
            'damage_points_right_side' => ['nullable', 'json'],
        ];
    }

    public function messages(): array
    {
        return [
            'folio_number.unique' => 'El número de folio ya fue asignado a otra orden, por favor intenta de nuevo.',
            'gasolina.between' => 'El nivel de gasolina debe estar entre 0% y 100%.',
        ];
    }
}
