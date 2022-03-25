<?php

namespace App\Http\Requests;

class RequestRegister extends FormRequestBase
{
    public function rules()
    {
        /* 正規表現の内容はswagger.ymlを参照のこと */
        return [
            'nickname' => 'required|min:1|max:20',
            'name'     => 'required|regex:/^[a-zA-Z0-9-_\.]{3,20}$/|regex:/^(?!.*\.$).*$/',
            'email'    => 'required|email',
            'password' => 'required|regex:/\A(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,20}+\z/i',
        ];
    }
}
