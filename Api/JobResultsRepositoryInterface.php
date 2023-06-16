<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface JobResultsRepositoryInterface
{

    /**
     * Save JobResults
     * @param \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface $jobResults
     * @return \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface $jobResults
    );

    /**
     * Retrieve JobResults
     * @param string $jobresultsId
     * @return \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($jobresultsId);

    /**
     * Retrieve JobResults matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Parc\UpdateUrlKeys\Api\Data\JobResultsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete JobResults
     * @param \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface $jobResults
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface $jobResults
    );

    /**
     * Delete JobResults by ID
     * @param string $jobresultsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($jobresultsId);
}

