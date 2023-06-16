<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Api\Data;

interface JobResultsInterface
{

    const SHOW_JOB_RESULTS = 'show_job_results';
    const JOBRESULTS_ID = 'jobresults_id';

    /**
     * Get jobresults_id
     * @return string|null
     */
    public function getJobresultsId();

    /**
     * Set jobresults_id
     * @param string $jobresultsId
     * @return \Parc\UpdateUrlKeys\JobResults\Api\Data\JobResultsInterface
     */
    public function setJobresultsId($jobresultsId);

    /**
     * Get show_job_results
     * @return string|null
     */
    public function getShowJobResults();

    /**
     * Set show_job_results
     * @param string $showJobResults
     * @return \Parc\UpdateUrlKeys\JobResults\Api\Data\JobResultsInterface
     */
    public function setShowJobResults($showJobResults);
}

