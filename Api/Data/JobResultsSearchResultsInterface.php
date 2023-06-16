<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Api\Data;

interface JobResultsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get JobResults list.
     * @return \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface[]
     */
    public function getItems();

    /**
     * Set show_job_results list.
     * @param \Parc\UpdateUrlKeys\Api\Data\JobResultsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

