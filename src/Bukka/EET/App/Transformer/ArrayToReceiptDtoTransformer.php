<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;
use Bukka\EET\App\Security\UuidGenerator;

class ArrayToReceiptDtoTransformer
{
    /**
     * @var UuidGenerator
     */
    private $uuidGenerator;

    /**
     * @var bool
     */
    private $rewriteSentDate;

    /**
     * @var bool
     */
    private $rewriteDealDate;

    /**
     * @var array
     */
    private $columns = [
        'id' => ['type' => 'str', 'property' => 'external_id'],
        'uuid_zpravy' => ['type' => 'string', 'property' => 'uuid'],
        'dat_odesl' => 'datetime',
        'prvni_zadani' => ['type' => 'bool', 'property' => 'prvni_zaslani'],
        'overeni' => 'bool',
        'dic_popl' => 'str',
        'id_provoz' => 'int',
        'id_pokl' => 'int',
        'porad_cis' => 'int',
        'dat_trzby' => 'date',
        'celk_trzba' => 'float',
        'zakl_dan1' => 'float',
        'dan1' => 'float',
        'zakl_dan2' => 'float',
        'dan2' => 'float',
        'zakl_dan3' => 'float',
        'dan3' => 'float',
        'rezim' => 0,
    ];

    /**
     * ArrayToReceiptDtoTransformer constructor
     *
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(
        UuidGenerator $uuidGenerator,
        $rewriteSentDate = true,
        $rewriteDealDate = true
    ) {
        $this->uuidGenerator = $uuidGenerator;
        $this->rewriteSentDate = $rewriteSentDate;
        $this->rewriteDealDate = $rewriteDealDate;
    }

    /**
     * @param ReceiptDto $dto
     * @param string $name
     * @param mixed $value
     */
    private function transformValue(ReceiptDto $dto, $name, $value)
    {
        $info = $this->columns[$name];
        if (is_array($info)) {
            $type = $info['type'];
            $property = $info['property'];
        } else {
            $type = $info;
            $property = $name;
        }
        $method = 'set' . implode(array_map('ucfirst', explode('_', $property)));

        switch ($type) {
            case 'int':
                $dto->$method((int) $value);
                break;
            case 'float':
                $dto->$method((float) $value);
                break;
            case 'bool':
                $dto->$method(in_array($value, ['yes', 'on', 'ano', 'true', 1]));
                break;
            case 'date':
                if (strpos($value, ':') === false) {
                    $value .= ' 0:00:00';
                }
                /* fallthrough */
            case 'datetime':
                $dto->$method(\DateTime::createFromFormat('j.n.Y G:i:s', $value) ?: null);
                break;
            default:
                $dto->$method($value);
        }
    }

    /**
     * @param mixed $data
     * @return ReceiptDto
     */
    public function transform($data)
    {
        $dto = new ReceiptDto();
        foreach ($data as $name => $value) {
            if (isset($this->columns[$name])) {
                $this->transformValue($dto, $name, $value);
            }
        }

        if ($dto->getUuid() === null) {
            $dto->setUuid($this->uuidGenerator->generate());
        }

        $currentDateTime = new \DateTime();
        if ($this->rewriteSentDate) {
            $dto->setDatOdesl($currentDateTime);
        }
        if ($this->rewriteDealDate) {
            $dto->setDatTrzby($currentDateTime);
        }

        return $dto;
    }
}
