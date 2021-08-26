<?php

declare(strict_types=1);

namespace Atwix\Tempo\Service;

use Atwix\Tempo\Api\WorklogDataInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;

class ConfigService
{
    public const FIELDS = [
        self::ACCESS_TOKEN_KEY,
        self::AUTHOR_ACCOUNT_ID_KEY,
        self::WORKLOG_DATA_KEY,
        self::MULTIPLIER_KEY,
        self::DATE
    ];

    public const AUTHOR_ACCOUNT_ID_KEY = 'author_account_id';
    public const ACCESS_TOKEN_KEY      = 'access_token';
    public const WORKLOG_DATA_KEY      = 'worklog_data';
    public const MULTIPLIER_KEY        = 'multiplier';
    public const DATE                  = 'date';

    private Session $session;

    /**
     * ConfigService constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function saveDataToSession(array $data): void
    {
        foreach (ConfigService::FIELDS as $key) {
            if ($value = $data[$key] ?? null) {
                $this->session->setData($key, $value);
            }
        }
    }

    public function getAuthorAccountId(): ?string
    {
        return $this->session->getData(self::AUTHOR_ACCOUNT_ID_KEY);
    }

    public function getAccessToken(): ?string
    {
        return $this->session->getData(self::ACCESS_TOKEN_KEY);
    }

    public function getDate(): ?string
    {
        return $this->session->getData(self::DATE);
    }

    public function getMultiplier()
    {
        return $this->session->getData(self::MULTIPLIER_KEY);
    }

    public function getWorklog(): ?WorklogDataInterface
    {
        return $this->session->getData(self::WORKLOG_DATA_KEY);
    }

    public function setDate(?string $date): void
    {
        if ($date) {
            $worklog = $this->getWorklog();
            if (!$worklog) {
                throw new LocalizedException(__('Please add data first'));
            }

            foreach ($worklog->getItems() ?? [] as $item) {
                $item->setDate($date);
            }

            $this->saveDataToSession([self::WORKLOG_DATA_KEY => $worklog]);
        }
    }
}
