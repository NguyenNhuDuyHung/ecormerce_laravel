<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;
class MenuCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'keyword',
        'publish'
    ];

}
