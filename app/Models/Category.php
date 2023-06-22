<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['name_ar' , 'name_en','active' , 'updated_at' , 'created_at'];


    public function scopeSelection($query){
        return $query -> select('id', 'name_'.app() -> getLocale() . ' as name');
    }

}
