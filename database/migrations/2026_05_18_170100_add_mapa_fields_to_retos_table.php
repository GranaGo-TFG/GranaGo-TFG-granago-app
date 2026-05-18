<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->string('ubicacion_referencia', 120)->nullable()->after('descripcion');
            $table->decimal('latitud', 10, 7)->nullable()->after('puntos_recompensa');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->index(['latitud', 'longitud'], 'retos_lat_long_index');
        });
    }

    public function down(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->dropIndex('retos_lat_long_index');
            $table->dropColumn(['ubicacion_referencia', 'latitud', 'longitud']);
        });
    }
};
