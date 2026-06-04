<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname', 30)->nullable()->after('nombre');
        });

        $users = DB::table('users')
            ->select('id', 'nombre', 'email')
            ->orderBy('id')
            ->get();

        $nicknamesUsados = [];

        foreach ($users as $user) {
            $baseNombre = preg_replace('/[^a-z0-9]/', '', Str::ascii(Str::lower((string) $user->nombre)));

            if ($baseNombre === '') {
                $baseNombre = preg_replace('/[^a-z0-9]/', '', Str::ascii(Str::lower((string) Str::before((string) $user->email, '@'))));
            }

            if ($baseNombre === '') {
                $baseNombre = 'jugador';
            }

            $baseNombre = Str::limit($baseNombre, 24, '');
            $nickname = $baseNombre;
            $contador = 1;

            while (in_array($nickname, $nicknamesUsados, true)) {
                $sufijo = (string) $contador;
                $nickname = Str::limit($baseNombre, 24 - strlen($sufijo), '') . $sufijo;
                $contador++;
            }

            $nicknamesUsados[] = $nickname;

            DB::table('users')
                ->where('id', $user->id)
                ->update(['nickname' => $nickname]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('nickname');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nickname']);
            $table->dropColumn('nickname');
        });
    }
};
