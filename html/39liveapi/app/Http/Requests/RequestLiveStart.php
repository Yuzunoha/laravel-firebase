<?php

namespace App\Http\Requests;

class RequestLiveStart extends FormRequestBase
{
    public function rules()
    {
        return [
            'name'    => 'required|string',
            'tid_csv' => 'string',
        ];
    }
}
