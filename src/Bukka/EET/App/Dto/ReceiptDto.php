<?php

namespace Bukka\EET\App\Dto;

class ReceiptDto
{
    /** @var string */
    private $uuid;

    /** @var \DateTime */
    private $datOdesl;

    /** @var boolean */
    private $prvniZaslani = true;

    /** @var boolean */
    private $overeni;

    /** @var string */
    private $dicPopl;

    /** @var string */
    private $dicPoverujiciho;

    /** @var string */
    private $idProvoz;

    /** @var string */
    private $idPokl;

    /** @var string */
    private $poradCis;

    /** @var \DateTime */
    private $datTrzby;

    /** @var float */
    private $celkTrzba = 0;

    /** @var float */
    private $zaklNepodlDph = 0;

    /** @var float */
    private $zaklDan1 = 0;

    /** @var float */
    private $dan1 = 0;

    /** @var float */
    private $zaklDan2 = 0;

    /** @var float */
    private $dan2 = 0;

    /** @var float */
    private $zaklDan3 = 0;

    /** @var float */
    private $dan3 = 0;

    /** @var int */
    private $rezim = 0;

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     * @return ReceiptDto
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatOdesl()
    {
        return $this->datOdesl;
    }

    /**
     * @param mixed $datOdesl
     * @return ReceiptDto
     */
    public function setDatOdesl($datOdesl)
    {
        $this->datOdesl = $datOdesl;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrvniZaslani()
    {
        return $this->prvniZaslani;
    }

    /**
     * @param boolean $prvniZaslani
     * @return ReceiptDto
     */
    public function setPrvniZaslani($prvniZaslani)
    {
        $this->prvniZaslani = $prvniZaslani;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isOvereni()
    {
        return $this->overeni;
    }

    /**
     * @param boolean $overeni
     * @return ReceiptDto
     */
    public function setOvereni($overeni)
    {
        $this->overeni = $overeni;
        return $this;
    }

    /**
     * @return string
     */
    public function getDicPopl()
    {
        return $this->dicPopl;
    }

    /**
     * @param string $dicPopl
     * @return ReceiptDto
     */
    public function setDicPopl($dicPopl)
    {
        $this->dicPopl = $dicPopl;
        return $this;
    }

    /**
     * @return string
     */
    public function getDicPoverujiciho()
    {
        return $this->dicPoverujiciho;
    }

    /**
     * @param string $dicPoverujiciho
     * @return ReceiptDto
     */
    public function setDicPoverujiciho($dicPoverujiciho)
    {
        $this->dicPoverujiciho = $dicPoverujiciho;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdProvoz()
    {
        return $this->idProvoz;
    }

    /**
     * @param string $idProvoz
     * @return ReceiptDto
     */
    public function setIdProvoz($idProvoz)
    {
        $this->idProvoz = $idProvoz;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdPokl()
    {
        return $this->idPokl;
    }

    /**
     * @param string $idPokl
     * @return ReceiptDto
     */
    public function setIdPokl($idPokl)
    {
        $this->idPokl = $idPokl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPoradCis()
    {
        return $this->poradCis;
    }

    /**
     * @param string $poradCis
     * @return ReceiptDto
     */
    public function setPoradCis($poradCis)
    {
        $this->poradCis = $poradCis;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatTrzby()
    {
        return $this->datTrzby;
    }

    /**
     * @param \DateTime $datTrzby
     * @return ReceiptDto
     */
    public function setDatTrzby($datTrzby)
    {
        $this->datTrzby = $datTrzby;
        return $this;
    }

    /**
     * @return float
     */
    public function getCelkTrzba()
    {
        return $this->celkTrzba;
    }

    /**
     * @param float $celkTrzba
     * @return ReceiptDto
     */
    public function setCelkTrzba($celkTrzba)
    {
        $this->celkTrzba = $celkTrzba;
        return $this;
    }

    /**
     * @return float
     */
    public function getZaklNepodlDph()
    {
        return $this->zaklNepodlDph;
    }

    /**
     * @param float $zaklNepodlDph
     * @return ReceiptDto
     */
    public function setZaklNepodlDph($zaklNepodlDph)
    {
        $this->zaklNepodlDph = $zaklNepodlDph;
        return $this;
    }

    /**
     * @return float
     */
    public function getZaklDan1()
    {
        return $this->zaklDan1;
    }

    /**
     * @param float $zaklDan1
     * @return ReceiptDto
     */
    public function setZaklDan1($zaklDan1)
    {
        $this->zaklDan1 = $zaklDan1;
        return $this;
    }

    /**
     * @return float
     */
    public function getDan1()
    {
        return $this->dan1;
    }

    /**
     * @param float $dan1
     * @return ReceiptDto
     */
    public function setDan1($dan1)
    {
        $this->dan1 = $dan1;
        return $this;
    }

    /**
     * @return float
     */
    public function getZaklDan2()
    {
        return $this->zaklDan2;
    }

    /**
     * @param float $zaklDan2
     * @return ReceiptDto
     */
    public function setZaklDan2($zaklDan2)
    {
        $this->zaklDan2 = $zaklDan2;
        return $this;
    }

    /**
     * @return float
     */
    public function getDan2()
    {
        return $this->dan2;
    }

    /**
     * @param float $dan2
     * @return ReceiptDto
     */
    public function setDan2($dan2)
    {
        $this->dan2 = $dan2;
        return $this;
    }

    /**
     * @return float
     */
    public function getZaklDan3()
    {
        return $this->zaklDan3;
    }

    /**
     * @param float $zaklDan3
     * @return ReceiptDto
     */
    public function setZaklDan3($zaklDan3)
    {
        $this->zaklDan3 = $zaklDan3;
        return $this;
    }

    /**
     * @return float
     */
    public function getDan3()
    {
        return $this->dan3;
    }

    /**
     * @param float $dan3
     * @return ReceiptDto
     */
    public function setDan3($dan3)
    {
        $this->dan3 = $dan3;
        return $this;
    }

    /**
     * @return int
     */
    public function getRezim()
    {
        return $this->rezim;
    }

    /**
     * @param int $rezim
     * @return ReceiptDto
     */
    public function setRezim($rezim)
    {
        $this->rezim = $rezim;
        return $this;
    }
}