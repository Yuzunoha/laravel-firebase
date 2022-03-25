<?php

namespace App\Http\Requests;

class RequestLogin extends FormRequestBase
{
    public function rules()
    {
        /* 正規表現の内容はswagger.ymlを参照のこと */
        return [
            'email'    => 'required|string',
            'password' => 'required|string',
        ];
    }
}
