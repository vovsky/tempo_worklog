<?php

namespace Atwix\Tempo\Model;

use Atwix\Tempo\Api\Data\WorklogItemDataInterface;
use Magento\Framework\Model\AbstractModel;

class WorklogItemDataModel extends AbstractModel implements WorklogItemDataInterface
{

    public function getTicket(): ?string
    {
        return $this->getData(self::TICKET);
    }

    public function setTicket(string $ticket): void
    {
        $this->setData(self::TICKET, $ticket);
    }

    public function getTimeSpentSeconds(): ?string
    {
        return $this->getData(self::TIME_SPENT_SECONDS);
    }

    public function setTimeSpentSeconds(string $timeSpentSeconds): void
    {
        $this->setData(self::TIME_SPENT_SECONDS, $timeSpentSeconds);
    }

    public function getDate(): ?string
    {
        return $this->getData(self::DATE);
    }

    public function setDate(string $date): void
    {
        $this->setData(self::DATE, $date);
    }

    public function getWorklogType(): ?string
    {
        return $this->getData(self::WORKLOG_TYPE);
    }

    public function setWorklogType(string $worklogType): void
    {
        $this->setData(self::WORKLOG_TYPE, $worklogType);
    }

    public function getComment(): ?string
    {
        return $this->getData(self::COMMENT);
    }

    public function setComment(string $comment): void
    {
        $this->setData(self::COMMENT, $comment);
    }
}
