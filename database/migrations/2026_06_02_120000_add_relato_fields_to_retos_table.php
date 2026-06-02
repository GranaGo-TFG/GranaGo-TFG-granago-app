<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->string('titulo_relato')->nullable()->after('descripcion');
            $table->text('leyenda_relato')->nullable()->after('titulo_relato');
            $table->text('contenido_relato')->nullable()->after('leyenda_relato');
            $table->text('cierre_relato')->nullable()->after('contenido_relato');
        });
    }

    public function down(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->dropColumn([
                'titulo_relato',
                'leyenda_relato',
                'contenido_relato',
                'cierre_relato',
            ]);
        });
    }
};
