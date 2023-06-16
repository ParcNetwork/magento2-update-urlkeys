<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Controller\Adminhtml\Runnow;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Parc\UpdateUrlKeys\Cron\UpdateUrlKeys;

class Runnow implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;
    /**
     * @var Json
     */
    protected Json $serializer;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var Http
     */
    protected Http $http;

    protected UpdateUrlKeys $updateUrlKeys;

    /**
     * Constructor
     *
     * @param PageFactory       $resultPageFactory
     * @param Json              $json
     * @param LoggerInterface   $logger
     * @param Http              $http
     * @param UpdateUrlKeys     $updateUrlKeys
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Json $json,
        LoggerInterface $logger,
        Http $http,
        UpdateUrlKeys $updateUrlKeys
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->serializer = $json;
        $this->logger = $logger;
        $this->http = $http;
        $this->updateUrlKeys = $updateUrlKeys;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface|HttpInterface|Http
     */
    public function execute(): ResultInterface|HttpInterface|Http
    {
        try {
            return $this->jsonResponse(
                $this->updateUrlKeys->execute()
            );
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @param array|string $response
     * @return ResultInterface|HttpInterface|Http
     */
    public function jsonResponse(array|string $response): ResultInterface|HttpInterface|Http
    {
        $this->http->getHeaders()->clearHeaders();
        $this->http->setHeader('Content-Type', 'application/json');
        return $this->http->setBody(
            $this->serializer->serialize($response));
    }
}

