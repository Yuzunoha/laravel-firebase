<?php

namespace App\Http\Requests;

class RequestPostIdentificationPaper extends FormRequestBase
{
    public function rules()
    {
        return [
            'identificationPaper' => 'required|file|mimes:jpg,jpeg,png,pdf',
        ];
    }
}
