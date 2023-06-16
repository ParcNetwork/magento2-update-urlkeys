<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Parc\UpdateUrlKeys\Api\Data\JobResultsInterface;
use Parc\UpdateUrlKeys\Api\Data\JobResultsInterfaceFactory;
use Parc\UpdateUrlKeys\Api\Data\JobResultsSearchResultsInterfaceFactory;
use Parc\UpdateUrlKeys\Api\JobResultsRepositoryInterface;
use Parc\UpdateUrlKeys\Model\ResourceModel\JobResults as ResourceJobResults;
use Parc\UpdateUrlKeys\Model\ResourceModel\JobResults\CollectionFactory as JobResultsCollectionFactory;

class JobResultsRepository implements JobResultsRepositoryInterface
{

    /**
     * @var ResourceJobResults
     */
    protected $resource;

    /**
     * @var JobResultsInterfaceFactory
     */
    protected $jobResultsFactory;

    /**
     * @var JobResultsCollectionFactory
     */
    protected $jobResultsCollectionFactory;

    /**
     * @var JobResults
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceJobResults $resource
     * @param JobResultsInterfaceFactory $jobResultsFactory
     * @param JobResultsCollectionFactory $jobResultsCollectionFactory
     * @param JobResultsSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceJobResults $resource,
        JobResultsInterfaceFactory $jobResultsFactory,
        JobResultsCollectionFactory $jobResultsCollectionFactory,
        JobResultsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->jobResultsFactory = $jobResultsFactory;
        $this->jobResultsCollectionFactory = $jobResultsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(JobResultsInterface $jobResults)
    {
        try {
            $this->resource->save($jobResults);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the jobResults: %1',
                $exception->getMessage()
            ));
        }
        return $jobResults;
    }

    /**
     * @inheritDoc
     */
    public function get($jobResultsId)
    {
        $jobResults = $this->jobResultsFactory->create();
        $this->resource->load($jobResults, $jobResultsId);
        if (!$jobResults->getId()) {
            throw new NoSuchEntityException(__('JobResults with id "%1" does not exist.', $jobResultsId));
        }
        return $jobResults;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->jobResultsCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(JobResultsInterface $jobResults)
    {
        try {
            $jobResultsModel = $this->jobResultsFactory->create();
            $this->resource->load($jobResultsModel, $jobResults->getJobresultsId());
            $this->resource->delete($jobResultsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the JobResults: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($jobResultsId)
    {
        return $this->delete($this->get($jobResultsId));
    }
}
