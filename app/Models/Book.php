<?php

namespace App\Models;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $fillable=[
        'category_id',
        'name',
        'author',
        'price'
    ];



    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeFilter($query, Filter $filters)
    {
        return $filters->apply($query);
    }
}
