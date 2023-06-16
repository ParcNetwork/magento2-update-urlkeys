<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model;

use Magento\Framework\Model\AbstractModel;
use Parc\UpdateUrlKeys\Api\Data\JobResultsInterface;

class JobResults extends AbstractModel implements JobResultsInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Parc\UpdateUrlKeys\Model\ResourceModel\JobResults::class);
    }

    /**
     * @inheritDoc
     */
    public function getJobresultsId()
    {
        return $this->getData(self::JOBRESULTS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setJobresultsId($jobresultsId)
    {
        return $this->setData(self::JOBRESULTS_ID, $jobresultsId);
    }

    /**
     * @inheritDoc
     */
    public function getShowJobResults()
    {
        return $this->getData(self::SHOW_JOB_RESULTS);
    }

    /**
     * @inheritDoc
     */
    public function setShowJobResults($showJobResults)
    {
        return $this->setData(self::SHOW_JOB_RESULTS, $showJobResults);
    }
}
