<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Security\UuidGenerator;
use PHPUnit_Framework_TestCase as TestCase;

class ArrayToReceiptDtoTransformerTest extends TestCase
{
    /**
     * @var ArrayToReceiptDtoTransformer
     */
    private $transformer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $uuidGenerator;

    public function setUp()
    {
        $this->uuidGenerator = $this->createMock(UuidGenerator::class);
        $this->transformer = new ArrayToReceiptDtoTransformer($this->uuidGenerator, false);
    }

    public function testTransformBasic()
    {
        $data = [
            'uuid_zpravy' => 'b3a09b52-7c87-4014-a496-4c7a53cf9125',
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
            'zakl_dan2' => 80,
            'dan2' => 20,
            'zakl_dan3' => 70,
            'dan3' => 30,
            'rezim' => 0,
        ];

        $dto = $this->transformer->transform($data);

        $this->assertInstanceOf(ReceiptDto::class, $dto);
        $this->assertSame('b3a09b52-7c87-4014-a496-4c7a53cf9125', $dto->getUuid());
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
        $this->assertSame(80.0, $dto->getZaklDan2());
        $this->assertSame(20.0, $dto->getDan2());
        $this->assertSame(70.0, $dto->getZaklDan3());
        $this->assertSame(30.0, $dto->getDan3());
        $this->assertSame(0, $dto->getRezim());
    }

    public function testTransformWithoutUuid()
    {
        $data = [
            'id' => 'CZ/xyz',
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
            'zakl_dan2' => '',
            'dan2' => '',
            'zakl_dan3' => '',
            'dan3' => '',
            'rezim' => 0,
        ];

        $this->uuidGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('b3a09b53-7c87-4014-a496-4c7a53cf9125');

        $dto = $this->transformer->transform($data);

        $this->assertInstanceOf(ReceiptDto::class, $dto);
        $this->assertSame('CZ/xyz', $dto->getExternalId());
        $this->assertSame('b3a09b53-7c87-4014-a496-4c7a53cf9125', $dto->getUuid());
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
        $this->assertSame(0.0, $dto->getZaklDan2());
        $this->assertSame(0.0, $dto->getDan2());
        $this->assertSame(0.0, $dto->getZaklDan3());
        $this->assertSame(0.0, $dto->getDan3());
        $this->assertSame(0, $dto->getRezim());
    }

    public function testTransformWithWrongDate()
    {
        $data = [
            'id' => 'CZ/xyz',
            'dat_odesl' => "xyz",
            'prvni_zadani' => "ano",
            'overeni' => "true",
            'dic_popl' => "CZ24222224",
            'id_provoz' => 101,
            'id_pokl' => 3,
            'porad_cis' => 5862,
            'dat_trzby' => "avc",
            'celk_trzba' => 100,
            'zakl_dan1' => 83,
            'dan1' => 17,
            'zakl_dan2' => '',
            'dan2' => '',
            'zakl_dan3' => '',
            'dan3' => '',
            'rezim' => 0,
        ];

        $this->uuidGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('b3a09b53-7c87-4014-a496-4c7a53cf9125');

        $dto = $this->transformer->transform($data);

        $this->assertInstanceOf(ReceiptDto::class, $dto);
        $this->assertSame('CZ/xyz', $dto->getExternalId());
        $this->assertSame('b3a09b53-7c87-4014-a496-4c7a53cf9125', $dto->getUuid());
        $this->assertNull($dto->getDatOdesl());
        $this->assertTrue($dto->isPrvniZaslani());
        $this->assertTrue($dto->isPrvniZaslani());
        $this->assertSame("CZ24222224", $dto->getDicPopl());
        $this->assertSame(101, $dto->getIdProvoz());
        $this->assertSame(3, $dto->getIdPokl());
        $this->assertSame(5862, $dto->getPoradCis());
        $this->assertNull($dto->getDatTrzby());
        $this->assertSame(100.0, $dto->getCelkTrzba());
        $this->assertSame(83.0, $dto->getZaklDan1());
        $this->assertSame(17.0, $dto->getDan1());
        $this->assertSame(0.0, $dto->getZaklDan2());
        $this->assertSame(0.0, $dto->getDan2());
        $this->assertSame(0.0, $dto->getZaklDan3());
        $this->assertSame(0.0, $dto->getDan3());
        $this->assertSame(0, $dto->getRezim());
    }
}
