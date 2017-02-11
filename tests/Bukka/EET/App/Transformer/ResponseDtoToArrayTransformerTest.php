<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;
use PHPUnit_Framework_TestCase as TestCase;

class ResponseDtoToArrayTransformerTest extends TestCase
{
    /**
     * @var ResponseDtoToArrayTransformer
     */
    private $transformer;

    public function setUp()
    {
        $this->transformer = new ResponseDtoToArrayTransformer();
    }

    public function testTransformBasic()
    {
        $receiptDto = (new ReceiptDto())
            ->setExternalId(1)
            ->setUuid('b3309a52-7c87-4014-a496-4c7a53cf9125')
            ->setDatOdesl(new \DateTime('2017-01-10 9:10:01'))
            ->setPrvniZaslani(true)
            ->setOvereni(false)
            ->setDicPopl('CZ24222224')
            ->setIdProvoz(101)
            ->setIdPokl(3)
            ->setPoradCis(5862)
            ->setDatTrzby(new \DateTime('2017-01-09'))
            ->setCelkTrzba(100.2)
            ->setZaklDan1(83)
            ->setDan1(17)
            ->setZaklDan2(80)
            ->setDan2(20)
            ->setRezim(0);

        $bkp = 'Ca8sTbURReQjjgcy/znXBKjPOnZof3AxWK5WySpyMrUXF0o7cz1BP6adQzktODKh2d8s' .
            'oAhn1R/S07lVDTa/6r9xTuI3NBH/+7YfYz/t92eb5Y6aNvLm6tXfOdE3C94EqmT0SEEz' .
            '9rInGXXP1whIKYX7K0HgVrxjdxCFkZF8Lt12XbahhAzJ47LcPxuBZZp6U6wJ2sWI5os3' .
            'KY9u/ZchzAUaCec7H56QwkMnu3U3Ftwi/YrxSzQZTmPTpFYKXnYanrFaLDJm+1/yg+VQ' .
            'ntoByBM+HeDXigBK+Shaxx+Nd0sSmm1Im4v685BRVdUId+4CobcnSQ3CBsjAhqmIrtWT' .
            'GQ==';

        $responseDto = (new ResponseDto())
            ->setFik('b3309b52-7c87-4014-a496-4c7a53cf9125')
            ->setBkp($bkp)
            ->setPkp('03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3')
            ->setReceipt($receiptDto);

        $this->assertSame(
            [
                'id' => 1,
                'dat_odesl' => "10.1.2017 9:10:01",
                'prvni_zadani' => "ano",
                'overeni' => "ne",
                'dic_popl' => "CZ24222224",
                'id_provoz' => 101,
                'id_pokl' => 3,
                'porad_cis' => 5862,
                'dat_trzby' => "9.1.2017",
                'celk_trzba' => 100.2,
                'rezim' => 0,
                'zakl_dan1' => 83,
                'dan1' => 17,
                'zakl_dan2' => 80,
                'dan2' => 20,
                'uuid_zpravy' => 'b3309a52-7c87-4014-a496-4c7a53cf9125',
                'fik' => 'b3309b52-7c87-4014-a496-4c7a53cf9125',
                'pkp' => '03ec1d0e-6d9f77fb-1d798ccb-f4739666-a4069bc3',
                'bkp' => $bkp,
                'error' => '',
            ],
            $this->transformer->transform($responseDto)
        );
    }
}