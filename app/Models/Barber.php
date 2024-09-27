<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barber extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    // Relacionamento com a model User (um barbeiro pertence a um usuário)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function appointment()
    {
        return $this->hasMany(Appointment::class, 'barber_id');
    }
}
