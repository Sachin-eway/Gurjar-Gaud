<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'city'; // Table name is 'city' (not 'cities')
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'city',
        'state_id',
        'district_id',
    ];

    // Relationships
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
