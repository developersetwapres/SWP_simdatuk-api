<?php

namespace Tests\Unit;

use App\Repositories\RecapitulationRepository;
use PHPUnit\Framework\TestCase;

class RecapitulationRepositoryTest extends TestCase
{
    public function test_normalize_non_asn_position_name_removes_parenthetical_suffixes()
    {
        $repository = new RecapitulationRepository();
        $method = new \ReflectionMethod($repository, 'normalizeNonAsnPositionName');
        $method->setAccessible(true);

        $this->assertSame(
            'Asisten Sekretaris Pribadi Wakil Presiden',
            $method->invoke($repository, 'Asisten Sekretaris Pribadi Wakil Presiden (9A)')
        );

        $this->assertSame(
            'Pembantu Asisten Sekretaris Pribadi Wakil Presiden',
            $method->invoke($repository, 'Pembantu Asisten Sekretaris Pribadi Wakil Presiden (9Ea)')
        );

        $this->assertSame(
            'Staf Khusus Wakil Presiden',
            $method->invoke($repository, 'Staf Khusus Wakil Presiden (A)')
        );

        $this->assertSame(
            'Asisten Staf Khusus Wakil Presiden',
            $method->invoke($repository, 'Asisten Staf Khusus Wakil Presiden (B)')
        );

        $this->assertSame(
            'Pengemudi VVIP',
            $method->invoke($repository, 'Pengemudi VVIP')
        );
    }
}
