<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    // Relacionamento com a model User (um barbeiro pertence a um usuário)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
