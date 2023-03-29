<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
