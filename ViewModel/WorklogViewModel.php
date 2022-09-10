<?php

namespace Atwix\Tempo\ViewModel;

use Atwix\Tempo\Api\Data\WorklogItemDataInterface;
use Atwix\Tempo\Api\WorklogDataInterface;
use Atwix\Tempo\Service\ConfigService;
use Atwix\Tempo\Service\WorklogStatisticsService;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class WorklogViewModel implements ArgumentInterface
{

    private ConfigService $configService;
    private WorklogStatisticsService $worklogStatisticsService;

    /**
     * WorklogViewModel constructor.
     * @param ConfigService $configService
     * @param WorklogStatisticsService $worklogStatisticsService
     */
    public function __construct(
        ConfigService            $configService,
        WorklogStatisticsService $worklogStatisticsService
    ) {
        $this->configService = $configService;
        $this->worklogStatisticsService = $worklogStatisticsService;
    }

    public function getWorklogTable()
    {
        $worklog = $this->getWorklog();
        if (!$worklog) {
            return [];
        }
        $worklogTable = [];
        $multiplier = $this->getMultiplier();
        foreach ($worklog->getItems() ?? [] as $item) {
            $worklogTable[] = [
                $item->getDate(),
                $item->getTicket(),
                gmdate("H:i", $item->getTimeSpentSeconds() * $multiplier),
                gmdate("H:i", $item->getTimeSpentSeconds()),
                str_replace("\n", " | ", $item->getComment()),
                gmdate("H:i", (int)($item->getTimeSpentSeconds() / 0.875)),//unic time with coef
                $item->getWorklogType()
            ];
        }

        usort($worklogTable, function ($a, $b) {
            return $a[1] <=> $b[1];
        });

        array_unshift($worklogTable, [
            WorklogItemDataInterface::DATE, WorklogItemDataInterface::TICKET, 'real', 'multiplier', WorklogItemDataInterface::COMMENT, 'unic_time', WorklogItemDataInterface::WORKLOG_TYPE
        ]);

        $totalSpentSeconds = $this->worklogStatisticsService->getTotalSpentSeconds();
        $worklogTable[] = ['Totals:', '', gmdate("H:i", $totalSpentSeconds), gmdate("H:i", $totalSpentSeconds / $multiplier)];

        return $worklogTable;
    }

    public function getAuthorAccountId()
    {
        return $this->configService->getAuthorAccountId();
    }

    public function getAccessToken()
    {
        return $this->configService->getAccessToken();
    }

    public function getMultiplier()
    {
        return $this->configService->getMultiplier();
    }

    public function getWorklog(): ?WorklogDataInterface
    {
        return $this->configService->getWorklog();
    }
}
