<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class EnableDisableCron implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['label' => 'Disabled', 'value' => '0'],
            ['label' => 'Enabled', 'value' => '1'],
        ];
    }
}
