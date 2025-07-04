<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CensusMember extends Model
{
    use HasFactory;

    protected $table = 'census_members';

    protected $primaryKey = 'id';

    protected $fillable = [
        'census_form_id',
        'full_name',
        'gender',
        'dob',
        'marital_status',
        'education',
        'occupation',
        'income_source',
        'mobile',
        'whatsapp',
        'identity_proof',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // Relationships
    public function form()
    {
        return $this->belongsTo(CensusForm::class, 'census_form_id');
    }
}
