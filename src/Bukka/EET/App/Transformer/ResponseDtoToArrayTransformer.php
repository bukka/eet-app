<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ResponseDto;

class ResponseDtoToArrayTransformer
{
    /**
     * @param ResponseDto $response
     * @return array
     */
    public function transform(ResponseDto $response)
    {
        $receipt = $response->getReceipt();

        return [
            'uuid_zpravy' => $receipt->getUuid(),
            'dat_odesl' => $receipt->getDatOdesl()->format('j.n.Y G:i:s'),
            'prvni_zadani' => $receipt->isPrvniZaslani() ? 'ano' : 'ne',
            'overeni' => $receipt->isOvereni() ? 'ano' : 'ne',
            'dic_popl' => $receipt->getDicPopl(),
            'id_provoz' => $receipt->getIdProvoz(),
            'id_pokl' => $receipt->getIdPokl(),
            'porad_cis' => $receipt->getPoradCis(),
            'dat_trzby' => $receipt->getDatTrzby()->format('j.n.Y'),
            'celk_trzba' => $receipt->getCelkTrzba(),
            'rezim' => $receipt->getRezim(),
            'zakl_dan1' => $receipt->getZaklDan1(),
            'dan1' => $receipt->getDan1(),
            'zakl_dan2' => $receipt->getZaklDan2(),
            'dan2' => $receipt->getDan2(),
            'fik' => 'b3309b52-7c87-4014-a496-4c7a53cf9125',
            'pkp' => $response->getPkp(),
            'bkp' => $response->getBkp(),
            'error' => '',
        ];
    }
}