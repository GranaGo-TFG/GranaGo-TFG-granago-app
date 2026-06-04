<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unsignedInteger('cantidad')->default(0);
            $table->timestamp('ultima_compra_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'producto_id']);
            $table->index(['user_id', 'ultima_compra_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_producto');
    }
};
