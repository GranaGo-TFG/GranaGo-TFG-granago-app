<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('racha_ultimo_reto')->nullable()->after('racha_multiplicador');
        });

        DB::table('users')
            ->where('racha_multiplicador', '>', 1)
            ->update([
                'racha_ultimo_reto' => Carbon::now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('racha_ultimo_reto');
        });
    }
};
