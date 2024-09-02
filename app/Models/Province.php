<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'provinces';
    protected $primaryKey = 'code';
    public $incrementing = false;

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
}
