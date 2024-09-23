<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'Service';
    protected $fillable = [
        'name',
        'value',
        'barber_id',
        'duration',
    ];
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_service', 'service_id', 'appointment_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
