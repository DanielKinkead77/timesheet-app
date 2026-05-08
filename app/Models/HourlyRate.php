<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate_code',
        'description',
    ];

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class);
    }
}