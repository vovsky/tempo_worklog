<?php

namespace Atwix\Tempo\Service;

use Atwix\Tempo\Api\Data\WorklogItemDataInterface;
use GuzzleHttp\Client;
use Magento\Framework\Exception\LocalizedException;

class LogToTempoService
{
    private ConfigService $configService;
    private Client $guzzleHttpclient;
    private const TEMPO_API_URL = 'https://api.tempo.io/core/3/worklogs';

    /**
     * @param Client $guzzleHttpclient
     * @param ConfigService $configService
     */
    public function __construct(
        Client        $guzzleHttpclient,
        ConfigService $configService)
    {
        $this->configService = $configService;
        $this->guzzleHttpclient = $guzzleHttpclient;
    }

    public function execute()
    {
        $worklog = $this->configService->getWorklog();
        if (!$worklog) {
            throw new LocalizedException(__('Worklog is not set'));
        }

        $accessToken = $this->configService->getAccessToken();
        if (!$accessToken) {
            throw new LocalizedException(__('Access token is not set'));
        }

        $authorAccountId = $this->configService->getAuthorAccountId();
        if (!$authorAccountId) {
            throw new LocalizedException(__('Author Account ID is not set'));
        }
        try {
            foreach ($worklog->getItems() ?? [] as $item) {
                $payload = $this->getWorklogItemPayload($item, $authorAccountId);
                $this->guzzleHttpclient->post(self::TEMPO_API_URL, ['body' => $payload, 'headers' => $this->getHeaders($accessToken)]);
            }
        } catch (\Exception $exception) {
            throw new LocalizedException(__($exception->getMessage() . 'psyload: ' . $payload));
        }
    }

    private function getWorklogItemPayload(WorklogItemDataInterface $item, string $authorAccountId)
    {
        $worklogItemPayloadArray = [
            'issueKey'         => $item->getTicket(),
            'description'      => $item->getComment(),
            'startDate'        => $item->getDate(),
            'startTime'        => '10:00:00',
            'timeSpentSeconds' => $item->getTimeSpentSeconds(),
            'authorAccountId'  => $authorAccountId,
            'attributes'       => [
                [
                    'key'   => '_Worklogtype_',
                    'value' => $item->getWorklogType()
                ]
            ]
        ];

        return json_encode($worklogItemPayloadArray);
    }

    private function getHeaders(string $accessToken)
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ];
    }
}
