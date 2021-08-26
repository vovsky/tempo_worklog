<?php

declare(strict_types=1);

namespace Atwix\Tempo\Controller\Worklog;

use Atwix\Tempo\Service\LogToTempoService;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;

class Log implements HttpGetActionInterface
{
    private ManagerInterface $messageManager;
    private RedirectFactory $redirectFactory;
    private LogToTempoService $logToTempoService;

    /**
     * Index constructor.
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ManagerInterface $messageManager,
        RedirectFactory $redirectFactory,
        LogToTempoService $logToTempoService
    ) {
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->logToTempoService = $logToTempoService;
    }


    /**
     * @return ResponseInterface|ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        try {
            $this->logToTempoService->execute();
            $this->messageManager->addSuccessMessage('Done');
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->redirectFactory->create()->setPath('*/worklog/');
    }
}
