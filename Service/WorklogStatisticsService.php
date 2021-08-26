<?php

namespace Atwix\Tempo\Service;

class WorklogStatisticsService
{
    private ConfigService $configService;

    /**
     * WorklogStatisticsService constructor.
     * @param ConfigService $configService
     */
    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function getTotalSpentSeconds()
    {
        $result = 0;
        $worklog = $this->getWorklog();
        if ($worklog) {
            foreach ($worklog->getItems() ?? [] as $item) {
                $result += $item->getTimeSpentSeconds();
            }
        }
        $multiplier = $this->configService->getMultiplier() ?? 0;

        return $result * $multiplier;
    }

    public function getTotalSpentHours()
    {
        return $this->getTotalSpentSeconds() / 60 / 60;
    }

    /**
     * @return \Atwix\Tempo\Api\WorklogDataInterface|null
     */
    private function getWorklog()
    {
        return $this->configService->getWorklog();
    }
}
