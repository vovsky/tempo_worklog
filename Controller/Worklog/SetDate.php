<?php

namespace Atwix\Tempo\Controller\Worklog;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Atwix\Tempo\Service\ConfigService;

class SetDate implements HttpPostActionInterface
{

    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private ConfigService $configService;

    /**
     * SetAccountData constructor.
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ConfigService $configService
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory  $redirectFactory,
        ConfigService    $configService
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->configService = $configService;
    }

    public function execute()
    {
        $this->configService->setDate($this->request->getParam(ConfigService::DATE));

        return $this->redirectFactory->create()->setPath('*/worklog/');
    }
}
