<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owners extends Model
{
    use HasFactory;
    protected $table = 'owners';
    protected $guarded = ['id'];

    public function setBirthAttribute($value)
    {
        $this->attributes['birth'] = Carbon::parse($value);
    }
}
