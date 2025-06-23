<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';
    protected $primaryKey = 'state_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'country_id',
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'state_id', 'state_id');
    }
}
