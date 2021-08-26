<?php

namespace Atwix\Tempo\Controller\Worklog;

use Atwix\Tempo\Service\ParseWorklogInputDataService;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Atwix\Tempo\Service\ConfigService;
use Magento\Framework\Message\ManagerInterface;

class Preview implements HttpPostActionInterface
{

    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private ConfigService $configService;
    private ParseWorklogInputDataService $parseWorklogInputDataService;
    private ManagerInterface $messageManager;


    /**
     * SetAccountData constructor.
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ConfigService $configService
     * @param ParseWorklogInputDataService $parseWorklogInputDataService
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface             $request,
        RedirectFactory              $redirectFactory,
        ConfigService                $configService,
        ParseWorklogInputDataService $parseWorklogInputDataService,
        ManagerInterface             $messageManager
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->configService = $configService;
        $this->parseWorklogInputDataService = $parseWorklogInputDataService;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        try {
            $worklogData = $this->parseWorklogInputDataService->execute(
                $this->request->getParam(ConfigService::WORKLOG_DATA_KEY)
            );

            $this->configService->saveDataToSession([ConfigService::WORKLOG_DATA_KEY => $worklogData]);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }


        return $this->redirectFactory->create()->setPath('*/worklog/');
    }
}
