<?php

namespace Atwix\Tempo\Api\Data;

interface WorklogItemDataInterface
{
    public const TICKET             = 'ticket';
    public const TIME_SPENT_SECONDS = 'time_spent_seconds';
    public const DATE               = 'date';
    public const WORKLOG_TYPE       = 'worklog_type';
    public const COMMENT            = 'comment';

    public function getTicket(): ?string;

    public function setTicket(string $ticket): void;

    public function getTimeSpentSeconds(): ?string;

    public function setTimeSpentSeconds(string $timeSpentSeconds): void;

    public function getDate(): ?string;

    public function setDate(string $date): void;

    public function getWorklogType(): ?string;

    public function setWorklogType(string $worklogType): void;

    public function getComment(): ?string;

    public function setComment(string $comment): void;

}
