<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class SkipActivitiesRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['activities'=>['array'], 'activities.*'=>['integer','exists:activities,id']]; }
}
