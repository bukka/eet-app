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
        $datOdesl = $receipt->getDatOdesl();
        $datTrzby = $receipt->getDatTrzby();

        return [
            'id' => $receipt->getExternalId(),
            'dat_odesl' => $datOdesl ? $datOdesl->format('j.n.Y G:i:s') : '',
            'prvni_zadani' => $receipt->isPrvniZaslani() ? 'ano' : 'ne',
            'overeni' => $receipt->isOvereni() ? 'ano' : 'ne',
            'dic_popl' => $receipt->getDicPopl(),
            'id_provoz' => $receipt->getIdProvoz(),
            'id_pokl' => $receipt->getIdPokl(),
            'porad_cis' => $receipt->getPoradCis(),
            'dat_trzby' => $datTrzby ? $datTrzby->format('j.n.Y') : '',
            'celk_trzba' => $receipt->getCelkTrzba(),
            'rezim' => $receipt->getRezim(),
            'zakl_dan1' => $receipt->getZaklDan1(),
            'dan1' => $receipt->getDan1(),
            'zakl_dan2' => $receipt->getZaklDan2(),
            'dan2' => $receipt->getDan2(),
            'zakl_dan3' => $receipt->getZaklDan3(),
            'dan3' => $receipt->getDan3(),
            'uuid_zpravy' => $receipt->getUuid(),
            'fik' => $response->getFik(),
            'pkp' => $response->getPkp(),
            'bkp' => $response->getBkp(),
            'chyba' => $response->getErrorMsg() ?: '',
        ];
    }
}
