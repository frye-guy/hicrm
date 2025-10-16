<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreAppointmentRequest extends FormRequest
{
public function authorize(): bool { return auth()->check(); }
public function rules(): array
{
return [
'contact_id' => ['required','exists:contacts,id'],
'sales_rep_id' => ['required','exists:sales_reps,id'],
'subproduct_id' => ['nullable','exists:subproducts,id'],
'scheduled_for' => ['required','date','after:now'],
'duration_min' => ['required','integer','min:30','max:240'],
'appointment_disposition_id' => ['nullable','exists:dispositions,id']
];
}
}