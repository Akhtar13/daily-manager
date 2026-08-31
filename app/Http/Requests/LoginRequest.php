<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['username'=>['required','string','min:2','max:40','regex:/^[A-Za-z0-9_.-]+$/']]; }
    public function messages(): array { return ['username.regex'=>'Use letters, numbers, dots, dashes, or underscores only.']; }
}
