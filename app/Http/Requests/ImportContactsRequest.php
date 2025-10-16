<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class ImportContactsRequest extends FormRequest
{
public function authorize(): bool { return auth()->user()->hasRole(['Admin','MarketingManager']); }
public function rules(): array
{
return [
'csv' => ['required','file','mimes:csv,txt'],
'mapping' => ['required','array'],
'lead_source_id' => ['required','exists:lead_sources,id'],
'office_id' => ['nullable','exists:offices,id']
];
}
}