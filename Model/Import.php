<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Import
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    protected array $storeViews;

    protected mixed $cronSchedule;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->scopeConfig = $scopeConfig;

        $storeViewImports = $scopeConfig->getValue(
            'parc_urlkeys/storeviews/selection'
        );

        if (!is_array($storeViewImports) && isset($storeViewImports)) {

            $storeViewImports = json_decode($storeViewImports, true);
        }

        if (is_array($storeViewImports)) {

            foreach ($storeViewImports as $import) {

                $this->storeViews[] = $import;
            }
        }

        $this->cronSchedule = $scopeConfig->getValue(
            'parc_urlkeys/cronjob/configure'
        );
    }

    /**
     *
     * @return array
     */
    public function run(): array
    {
        return ['storeViewsSettings'    => $this->storeViews ?? [],
                'cronScheduleSetting'   => $this->cronSchedule
        ];
    }
}
