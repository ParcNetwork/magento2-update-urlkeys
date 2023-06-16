<?php
/**
 * Copyright © Louis Templeton All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class JobResults extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('parc_updateurlkeys_jobresults', 'jobresults_id');
    }
}
