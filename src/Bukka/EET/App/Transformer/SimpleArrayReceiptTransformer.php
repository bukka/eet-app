<?php

namespace Bukka\EET\App\Transformer;

use Bukka\EET\App\Dto\ReceiptDto;

class SimpleArrayReceiptTransformer
{
    private $columns = [
        'uuid_zpravy' => ['type' => 'int', 'property' => 'uuid'],
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
        'rezim' => 0,
    ];

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
                $value .= ' 00:00:00';
                /* fallthrough */
            case 'datetime':
                $dto->$method(\DateTime::createFromFormat('j.n.Y H:i:s', $value));
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

        return $dto;
    }
}