<?php

namespace Parc\CorrectUrlKeys\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Import
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    protected array $storeViews;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->scopeConfig = $scopeConfig;

        $imports = $scopeConfig->getValue(
            'parc_urlkeys/storeviews/selection'
        );

        if (!is_array($imports) && isset($imports)) {

            $imports = json_decode($imports, true);
        }

        if (is_array($imports)) {

            foreach ($imports as $import) {

                $this->storeViews[] = $import;
            }
        }
    }

    /**
     *
     * @return array
     */
    public function run(): array
    {
        return $this->storeViews ?? [];
    }
}
