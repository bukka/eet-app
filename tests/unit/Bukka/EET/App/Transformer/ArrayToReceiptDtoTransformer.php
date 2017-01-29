<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;
use PHPUnit_Framework_TestCase as TestCase;

class ArrayToReceiptDtoTransformerTest extends TestCase
{
    public function testTransformBasic()
    {
        $data = [
            'uuid_zpravy' => 1,
            'dat_odesl' => "10.1.2017 9:10:01",
            'prvni_zadani' => "ano",
            'overeni' => "true",
            'dic_popl' => "CZ24222224",
            'id_provoz' => 101,
            'id_pokl' => 3,
            'porad_cis' => 5862,
            'dat_trzby' => "9.1.2017",
            'celk_trzba' => 100,
            'zakl_dan1' => 83,
            'dan1' => 17,
            'rezim' => 0,
        ];

        $transformer = new ArrayToReceiptDtoTransformer();
        $dto = $transformer->transform($data);

        $this->assertInstanceOf(ReceiptDto::class, $dto);
        $this->assertSame(1, $dto->getUuid());
        $this->assertEquals(new \DateTime('2017-1-10 9:10:01'), $dto->getDatOdesl());
        $this->assertTrue($dto->isPrvniZaslani());
        $this->assertTrue($dto->isPrvniZaslani());
        $this->assertSame("CZ24222224", $dto->getDicPopl());
        $this->assertSame(101, $dto->getIdProvoz());
        $this->assertSame(3, $dto->getIdPokl());
        $this->assertSame(5862, $dto->getPoradCis());
        $this->assertEquals(new \DateTime('2017-1-9 00:00:00'), $dto->getDatTrzby());
        $this->assertSame(100.0, $dto->getCelkTrzba());
        $this->assertSame(83.0, $dto->getZaklDan1());
        $this->assertSame(17.0, $dto->getDan1());
        $this->assertSame(0, $dto->getRezim());
    }
}