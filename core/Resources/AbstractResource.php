<?php

namespace Core\Resource;

use Illuminate\Database\Eloquent\Collection;

abstract class AbstractResource
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return $this->data->$key;
    }

    public function toArray(): array
    {
        return (array) $this->data;
    }

    public static function collection($items): array
    {
        $itemsArray = $items->toArray();

        return array_map(function ($item) {
            return (new static($item))->toArray();
        }, $itemsArray);
    }
}
