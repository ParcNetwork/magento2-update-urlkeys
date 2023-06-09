<?php

declare(strict_types=1);

namespace Parc\CorrectUrlKeys\Cron;

use Magento\Store\Model\StoreManagerInterface;
use Parc\CorrectUrlKeys\Model\FindUrlKeys;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Parc\CorrectUrlKeys\Model\Import;

class UpdateUrlKeys
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var FindUrlKeys
     */
    protected FindUrlKeys $_findUrlKeys;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_productCollectionFactory;

    /**
     * @var Import
     */
    protected Import $import;

    /**
     * @param StoreManagerInterface $_storeManager
     * @param FindUrlKeys           $_findUrlKeys
     * @param CollectionFactory     $_productCollectionFactory
     * @param Import                $import
     */
    public function __construct(
        StoreManagerInterface $_storeManager,
        FindUrlKeys $_findUrlKeys,
        CollectionFactory $_productCollectionFactory,
        Import $import
    ) {
        $this->_storeManager = $_storeManager;
        $this->_findUrlKeys = $_findUrlKeys;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->import = $import;
    }

    /**
     * @param $storeId
     *
     * @return array
     */
    public function getProductCollection($setting): array
    {
        $storeId = $setting['storeview'];
        $updateToday = $setting['enabled'];

        $productCollection = $this->_productCollectionFactory->create();

        if ((int) $updateToday === 1) {

            $today = date('Y-m-d');
            $productCollection->addFieldToFilter('updated_at', ['from' => $today]);
        }

        return $productCollection->addStoreFilter($storeId)->getAllIds();
    }

    public function prepareCronSchedule($cronScheduleSetting) {
        list($minutes, $hours) = explode(' ', $cronScheduleSetting);
        //$cronString = $minutes . ' ' . $hours . ' * * *';
        return $minutes . ' ' . $hours . ' * * *';
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $settings = $this->import->run();

        $storeViewSettings = $settings['storeViewsSettings'];

        $cronSchedule = $settings['cronScheduleSetting'];

        $cronSchedule = $this->prepareCronSchedule($cronSchedule);

        foreach ($storeViewSettings as $setting) {

            $this->_storeManager->setCurrentStore($setting['storeview']);

            $productIdsArray = $this->getProductCollection($setting);

            $this->_findUrlKeys->getProductsFilteredByUmlauts($productIdsArray, 'yes');
        }
    }
}
