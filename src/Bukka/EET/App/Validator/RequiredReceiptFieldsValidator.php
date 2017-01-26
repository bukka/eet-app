<?php

namespace Bukka\EET\App\Validator;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Validator\Exception\ValidatorException;

class RequiredReceiptFieldsValidator implements ReceiptValidatorInterface
{
    /**
     * @param ReceiptDto $receiptDto
     * @throws ValidatorException
     */
    public function validate(ReceiptDto $receiptDto)
    {
        switch (true) {
            case $receiptDto->getUuid() === null:
                $missing = 'uuid';
                break;
            case $receiptDto->getDatOdesl() === null:
                $missing = 'dat_odesl';
                break;
            case empty($receiptDto->getDicPopl()):
                $missing = 'dic_popl';
                break;
            case $receiptDto->getIdProvoz() === null:
                $missing = 'id_provoz';
                break;
            case $receiptDto->getIdPokl() === null:
                $missing = 'id_pokl';
                break;
            case $receiptDto->getPoradCis() === null:
                $missing = 'porad_cis';
                break;
            case $receiptDto->getDatTrzby() === null:
                $missing = 'dat_trzby';
                break;
            case $receiptDto->getCelkTrzba() === 0:
                $missing = 'celk_trzba';
                break;
            default:
                $missing = false;
        }

        if ($missing) {
            throw new ValidatorException('Missing field: ' . $missing);
        }
    }
}