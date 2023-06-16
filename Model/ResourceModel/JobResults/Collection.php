<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model\ResourceModel\JobResults;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Parc\UpdateUrlKeys\Model\JobResults;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'jobresults_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            JobResults::class,
            \Parc\UpdateUrlKeys\Model\ResourceModel\JobResults::class
        );
    }
}
