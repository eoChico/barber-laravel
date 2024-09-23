<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;
    protected $table = 'Availability';
    protected $fillable = ['barber_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'start_time', 'end_time'];

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }
}
