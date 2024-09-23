<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id(); // Coluna ID auto-incremento
            $table->string('name'); // Nome do serviço
            $table->decimal('value', 8, 2); // Valor do serviço (máximo 8 dígitos, 2 casas decimais)
            $table->integer('duration'); // Duração do serviço em minutos
            $table->timestamps();
            $table->unsignedBigInteger('barber_id');
            $table->foreign('barber_id')->references('id')->on('barbers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
