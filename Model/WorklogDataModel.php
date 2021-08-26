<?php

namespace Atwix\Tempo\Model;

use Atwix\Tempo\Api\Data\WorklogItemDataInterface;
use Atwix\Tempo\Api\WorklogDataInterface;
use Magento\Framework\Model\AbstractModel;

class WorklogDataModel extends AbstractModel implements WorklogDataInterface
{
    /**
     * @return WorklogItemDataInterface[]|NULL
     */
    public function getItems(): ?array
    {
        return $this->getData(self::ITEMS);
    }

    public function setItems(array $items): void
    {
        $this->setData(self::ITEMS, $items);
    }

    public function addItem(WorklogItemDataInterface $item): void
    {
        $items = $this->_getData(self::ITEMS);
        $items[] = $item;
        $this->setData(self::ITEMS, $items);
    }
}
