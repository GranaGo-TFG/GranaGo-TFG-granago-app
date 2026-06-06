<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserNombrePublicoTest extends TestCase
{
    public function test_usa_el_nickname_como_nombre_publico_si_existe(): void
    {
        $user = new User([
            'nombre' => 'Usuario Real',
            'nickname' => 'exploradorGranada',
        ]);

        $this->assertSame('exploradorGranada', $user->nombre_publico);
    }

    public function test_usa_el_nombre_si_no_hay_nickname(): void
    {
        $user = new User([
            'nombre' => 'Usuario Real',
            'nickname' => '   ',
        ]);

        $this->assertSame('Usuario Real', $user->nombre_publico);
    }
}
