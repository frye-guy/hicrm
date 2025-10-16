<?php
namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreQueueRequest extends FormRequest
{
public function authorize(): bool { return auth()->user()->hasRole(['Admin','MarketingManager']); }
public function rules(): array
{
return [
'name' => ['required','string','max:100'],
'filters' => ['required','array'],
];
}
}