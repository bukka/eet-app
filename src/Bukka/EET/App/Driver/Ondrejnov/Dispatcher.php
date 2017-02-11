<?php

namespace Bukka\EET\App\Driver\Ondrejnov;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Dto\ResponseDto;
use Ondrejnov\EET\Exceptions\ClientException;
use Ondrejnov\EET\Exceptions\RequirementsException;
use Ondrejnov\EET\SoapClient;
use Ondrejnov\EET\Utils\Format;
use RobRichards\XMLSecLibs\XMLSecurityKey;

/**
 * Receipt for Ministry of Finance
 */
class Dispatcher {

    /**
     * Certificate key
     * @var string
     */
    private $key;

    /**
     * Certificate
     * @var string
     */
    private $cert;

    /**
     * WSDL path or URL
     * @var string
     */
    private $service;

    /**
     * @var boolean
     */
    public $trace;

    /**
     *
     * @var SoapClient
     */
    private $soapClient;

    /**
     * @param string $service
     * @param string $key
     * @param string $cert
     */
    public function __construct($service, $key, $cert) {
        $this->service = $service;
        $this->key = $key;
        $this->cert = $cert;
        $this->checkRequirements();
    }

    /**
     *
     * @param boolean $tillLastRequest optional If not set/false connection time till now is returned.
     * @return float
     */
    public function getConnectionTime($tillLastRequest = false) {
        !$this->trace && $this->throwTraceNotEnabled();
        return $this->getSoapClient()->__getConnectionTime($tillLastRequest);
    }

    /**
     *
     * @return int
     */
    public function getLastResponseSize() {
        !$this->trace && $this->throwTraceNotEnabled();
        return mb_strlen($this->getSoapClient()->__getLastResponse(), '8bit');
    }

    /**
     *
     * @return int
     */
    public function getLastRequestSize() {
        !$this->trace && $this->throwTraceNotEnabled();
        return mb_strlen($this->getSoapClient()->__getLastRequest(), '8bit');
    }

    /**
     *
     * @return float time in ms
     */
    public function getLastResponseTime() {
        !$this->trace && $this->throwTraceNotEnabled();
        return $this->getSoapClient()->__getLastResponseTime();
    }

    /**
     *
     * @throws ClientException
     */
    private function throwTraceNotEnabled() {
        throw new ClientException('Trace is not enabled! Set trace property to true.');
    }

    /**
     * @param ReceiptDto $receipt
     * @return array
     */
    public function getCheckCodes(ReceiptDto $receipt) {
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
        $objKey->loadKey($this->key, true);

        $arr = [
            $receipt->getDicPopl(),
            $receipt->getIdProvoz(),
            $receipt->getIdPokl(),
            $receipt->getPoradCis(),
            $receipt->getDatTrzby()->format('c'),
            Format::price($receipt->getCelkTrzba())
        ];
        $sign = $objKey->signData(join('|', $arr));

        return [
            'pkp' => [
                '_' => $sign,
                'digest' => 'SHA256',
                'cipher' => 'RSA2048',
                'encoding' => 'base64'
            ],
            'bkp' => [
                '_' => Format::BKB(sha1($sign)),
                'digest' => 'SHA1',
                'encoding' => 'base16'
            ]
        ];
    }

    /**
     * @param ReceiptDto $receiptDto
     * @param boolean $check
     * @return ResponseDto
     */
    public function send(ReceiptDto $receiptDto, $check = false) {
        $this->initSoapClient();

        $data = $this->prepareData($receiptDto, $check);

        $responseDto = (new ResponseDto())
            ->setReceipt($receiptDto)
            ->setPkp($data['pkp']['_'])
            ->setBkp($data['bkp']['_']);

        $response = $this->getSoapClient()->OdeslaniTrzby($data);

        if (
            (!isset($response->Chyba) || !$this->processError($responseDto, $response->Chyba)) &&
            !$receiptDto->isOvereni()
        ) {
            $responseDto->setFik($response->Potvrzeni->fik);
        }

        return $responseDto;
    }

    /**
     *
     * @throws RequirementsException
     * @return void
     */
    private function checkRequirements() {
        if (!class_exists('\SoapClient')) {
            throw new RequirementsException('Soap extension is not loaded');
        }
    }

    /**
     * Get (or if not exists: initialize and get) SOAP client.
     *
     * @return SoapClient
     */
    public function getSoapClient() {
        !isset($this->soapClient) && $this->initSoapClient();
        return $this->soapClient;
    }

    /**
     * Require to initialize a new SOAP client for a new request.
     *
     * @return void
     */
    private function initSoapClient() {
        if ($this->soapClient === NULL) {
            $this->soapClient = new SoapClient($this->service, $this->key, $this->cert, $this->trace);
        }
    }

    /**
     * @param ReceiptDto $receipt
     * @return array
     */
    public function prepareData(ReceiptDto $receipt) {
        $head = [
            'uuid_zpravy' => $receipt->getUuid(),
            'dat_odesl' => $receipt->getDatOdesl()->format('c'),
            'prvni_zaslani' => $receipt->isPrvniZaslani() ? 'true' : 'false',
            'overeni' => $receipt->isOvereni() ? 'true' : 'false',
        ];

        $body = [
            'dic_popl' => $receipt->getDicPopl(),
            'dic_poverujiciho' => $receipt->getDicPoverujiciho(),
            'id_provoz' => $receipt->getIdProvoz(),
            'id_pokl' => $receipt->getIdPokl(),
            'porad_cis' => $receipt->getPoradCis(),
            'dat_trzby' => $receipt->getDatTrzby()->format('c'),
            'celk_trzba' => Format::price($receipt->getCelkTrzba()),
            'zakl_nepodl_dph' => Format::price($receipt->getZaklNepodlDph()),
            'zakl_dan1' => Format::price($receipt->getZaklDan1()),
            'dan1' => Format::price($receipt->getDan1()),
            'zakl_dan2' => Format::price($receipt->getZaklDan2()),
            'dan2' => Format::price($receipt->getDan2()),
            'zakl_dan3' => Format::price($receipt->getZaklDan3()),
            'dan3' => Format::price($receipt->getDan3()),
            'rezim' => $receipt->getRezim()
        ];

        return [
            'Hlavicka' => $head,
            'Data' => $body,
            'KontrolniKody' => $this->getCheckCodes($receipt)
        ];
    }

    /**
     * @param ResponseDto $responseDto
     * @param \stdClass $error
     * @return bool
     */
    private function processError(ResponseDto $responseDto, $error) {
        if (!$error->kod) {
            return false;
        }

        $msgs = [
            -1 => 'Docasna technicka chyba zpracovani – odeslete prosim datovou zpravu pozdeji',
            2 => 'Kodovani XML neni platne',
            3 => 'XML zprava nevyhovela kontrole XML schematu',
            4 => 'Neplatny podpis SOAP zpravy',
            5 => 'Neplatny kontrolni bezpecnostni kod poplatnika (BKP)',
            6 => 'DIC poplatnika ma chybnou strukturu',
            7 => 'Datova zprava je prilis velka',
            8 => 'Datova zprava nebyla zpracovana kvuli technicke chybe nebo chybe dat',
        ];
        $responseDto->setErrorCode($error->kod);
        if (isset($msgs[$error->kod])) {
            $responseDto->setErrorMsg($msgs[$error->kod]);
        } else {
            $responseDto->setErrorMsg('Neznami error');
        }

        return true;
    }

}
