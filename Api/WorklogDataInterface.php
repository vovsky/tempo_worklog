<?php

namespace Atwix\Tempo\Api;

use Atwix\Tempo\Api\Data\WorklogItemDataInterface;

interface WorklogDataInterface
{
    public const ITEMS = 'items';

    /**
     * @return WorklogItemDataInterface[]|null
     */
    public function getItems(): ?array;
    public function setItems(array $items): void;
    public function addItem(WorklogItemDataInterface $item): void;
}
