<?php

namespace Bukka\EET\App\Validator;

use Bukka\EET\App\Dto\ReceiptDto;
use PHPUnit_Framework_TestCase as TestCase;

class RequiredReceiptFieldsValidatorTest extends TestCase
{
    public function testValidateOk()
    {
        $dto = (new ReceiptDto())
            ->setUuid('uuid')
            ->setDatOdesl(new \DateTime())
            ->setPrvniZaslani(true)
            ->setDicPopl('CZ24222224')
            ->setIdProvoz(101)
            ->setIdPokl(3)
            ->setPoradCis(5862)
            ->setDatTrzby(new \DateTime())
            ->setCelkTrzba(100.2)
            ->setRezim(0);

        $validator = new RequiredReceiptFieldsValidator();

        $this->assertNull($validator->validate($dto));
    }

    /**
     * @param ReceiptDto $dto
     * @expectedException \Bukka\EET\App\Validator\Exception\ValidatorException
     * @dataProvider getInvalidDtos
     */
    public function testValidateFail(ReceiptDto $dto)
    {
        $validator = new RequiredReceiptFieldsValidator();
        $validator->validate($dto);
    }

    /**
     * @return array
     */
    public function getInvalidDtos()
    {
        return [
            [ // missing uuid
                (new ReceiptDto())
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing dat_odesl
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing dic_popl
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing id_provoz
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing id_pokl
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing porad_cis
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setDatTrzby(new \DateTime())
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing dat_trzby
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setCelkTrzba(100.2)
                    ->setRezim(0)
            ],
            [ // missing celk_trzba
                (new ReceiptDto())
                    ->setUuid('uuid')
                    ->setDatOdesl(new \DateTime())
                    ->setPrvniZaslani(true)
                    ->setDicPopl('CZ24222224')
                    ->setIdProvoz(101)
                    ->setIdPokl(3)
                    ->setPoradCis(5862)
                    ->setDatTrzby(new \DateTime())
                    ->setRezim(0)
            ],
        ];
    }
}