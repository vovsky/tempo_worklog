<?php

namespace Atwix\Tempo\Service;

use Atwix\Tempo\Api\WorklogDataInterfaceFactory;
use Atwix\Tempo\Api\Data\WorklogItemDataInterface;
use Atwix\Tempo\Api\Data\WorklogItemDataInterfaceFactory;
use Atwix\Tempo\Api\WorklogDataInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;

class ParseWorklogInputDataService
{
    private ConfigService $configService;
    private const HEADER_FIELD_TIME = 'time';
    private WorklogDataInterfaceFactory $worklogDataInterfaceFactory;
    private WorklogItemDataInterfaceFactory $worklogItemDataInterfaceFactory;
    private DataObjectHelper $dataObjectHelper;

    /**
     * ParseWorklogInputDataService constructor.
     * @param \Atwix\Tempo\Service\ConfigService $configService
     */
    public function __construct(
        ConfigService $configService,
        WorklogDataInterfaceFactory $worklogDataInterfaceFactory,
        WorklogItemDataInterfaceFactory $worklogItemDataInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->configService = $configService;
        $this->worklogDataInterfaceFactory = $worklogDataInterfaceFactory;
        $this->worklogItemDataInterfaceFactory = $worklogItemDataInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    public function execute($worklogInputData): WorklogDataInterface
    {
        $multiplier = $this->configService->getMultiplier();
        $worklog = $this->worklogDataInterfaceFactory->create();
        $worklogDataArray = [];
        if (!is_numeric($multiplier)) {
            throw new LocalizedException(__('Multiplier is wrong or not set'));
        }
        if ($worklogInputData = trim($worklogInputData)) {
            //$date = $this->configService->getDate() ?? date("Y-m-d");
            $date = date("Y-m-d");
            $lines = explode(PHP_EOL, $worklogInputData);
            $index = 0;
            $headerFields = str_getcsv(array_shift($lines), "\t");
            foreach ($lines as $lineNum => $line) {
                try {
                    $data = array_combine($headerFields, array_map('trim', str_getcsv($line, "\t")));
                } catch (\Exception $exception) {
                    throw new LocalizedException(__('Please check data near line ' . $lineNum));
                }

                if ($data[self::HEADER_FIELD_TIME]) {
                    $timeSpentSeconds = (int)(strtotime("1970-01-01 {$data[self::HEADER_FIELD_TIME]} UTC")
                        / $multiplier);
                    $timeSpentSeconds = ($timeSpentSeconds % 60) == 0 ?
                        $timeSpentSeconds :
                        ((int)($timeSpentSeconds / 60) * 60) + 60;

                    if ($data[WorklogItemDataInterface::TICKET]) {
                        $index++;
                        $worklogDataArray[$index] = $data;
                        $worklogDataArray[$index][WorklogItemDataInterface::TIME_SPENT_SECONDS] = $timeSpentSeconds;
                        $worklogDataArray[$index][WorklogItemDataInterface::DATE] = $date;
                    } else {
                        $worklogDataArray[$index][WorklogItemDataInterface::TIME_SPENT_SECONDS] += $timeSpentSeconds;
                        $worklogDataArray[$index][WorklogItemDataInterface::COMMENT] .=
                            $data[WorklogItemDataInterface::COMMENT] ?
                                PHP_EOL . $data[WorklogItemDataInterface::COMMENT] : '';
                    }
                }
            }

            foreach ($worklogDataArray ?? [] as $data) {
                $item = $this->worklogItemDataInterfaceFactory->create();
                $this->dataObjectHelper->populateWithArray($item, $data, WorklogItemDataInterface::class);
                $item->setDate($date);
                $worklog->addItem($item);
            }
        }

        return $worklog;
    }
}
