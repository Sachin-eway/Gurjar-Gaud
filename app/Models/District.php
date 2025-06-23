<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts'; // optional if your table name is not plural
    protected $primaryKey = 'district_id'; // important for custom primary key
    public $timestamps = false; // if your table doesn't have created_at/updated_at

    protected $fillable = [
        'state_id',
        'name',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
}
