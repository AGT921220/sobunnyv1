<?php

namespace Modules\Chat\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchVendorChatRecordRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
