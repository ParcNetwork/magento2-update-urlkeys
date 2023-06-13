<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Parc\UpdateUrlKeys\Cron\UpdateUrlKeys;

class RunNow extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var UpdateUrlKeys
     */
    protected $updateUrlKeys;

    /**
     * @param Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param UpdateUrlKeys $updateUrlKeys
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        UpdateUrlKeys $updateUrlKeys
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->updateUrlKeys = $updateUrlKeys;
    }

    /**
     * Execute the update data action
     *
     * @return Json
     */
    public function execute()
    {
        try {

            $this->updateUrlKeys->execute();

            $result = ['success' => true, 'message' => 'Data updated successfully'];
        } catch (\Exception $e) {
            $result = ['success' => false, 'message' => $e->getMessage()];
        }

        $response = $this->resultJsonFactory->create();
        return $response->setData($result);
    }
}

