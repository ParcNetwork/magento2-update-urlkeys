<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Cron;

use Magento\Framework\Exception\FileSystemException;
use Magento\Store\Model\StoreManagerInterface;
use Parc\UpdateUrlKeys\Model\FindUrlKeys;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Parc\UpdateUrlKeys\Model\Import;
use Parc\UpdateUrlKeys\Model\CsvExporter;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;

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
     * @var CsvExporter
     */
    protected CsvExporter $csvExporter;

    /**
     * @param StoreManagerInterface $storeManager
     * @param FindUrlKeys           $findUrlKeys
     * @param CollectionFactory     $productCollectionFactory
     * @param Import                $import
     * @param CsvExporter           $csvExporter
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        FindUrlKeys $findUrlKeys,
        CollectionFactory $productCollectionFactory,
        Import $import,
        CsvExporter $csvExporter
    ) {
        $this->_storeManager = $storeManager;
        $this->_findUrlKeys = $findUrlKeys;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->import = $import;
        $this->csvExporter = $csvExporter;
    }

    /**
     * @param $setting
     *
     * @return array
     */
    public function getProductCollection($setting): array
    {
        $storeId = $setting['storeView'];
        $updateToday = $setting['lastModified'];
        $excludeDisabledProducts = $setting['enabledDisabled'];
        $excludeNotVisibleProducts = $setting['visibility'];

        $productCollection = $this->_productCollectionFactory->create();

        if ((int) $updateToday === 1) {

            $today = date('Y-m-d');
            $productCollection->addFieldToFilter('updated_at', ['from' => $today]);
        }

        if ((int) $excludeDisabledProducts === 1) {

            $productCollection->addFieldToFilter('status',
                ['eq' => Status::STATUS_ENABLED]);
        }

        if ((int) $excludeNotVisibleProducts === 1) {

            $productCollection->addFieldToFilter('visibility',
                ['gt' => Visibility::VISIBILITY_NOT_VISIBLE]);
        }

        return $productCollection->addStoreFilter($storeId)->getAllIds();
    }

    /**
     * @param $storeView
     * @return string
     */
    private function filenameCouldNotSave($storeView): string
    {

        $filename = 'couldnotsave_' . $storeView . '.csv';
        return str_replace(' ', '', $filename);
    }

    /**
     * @param $storeViewSettings
     * @return array
     */
    private function removeDuplicateStoreViews($storeViewSettings): array
    {
        $selectedStoreView = [];
        $uniqueSelections = [];

        foreach ($storeViewSettings as $item) {
            $storeView = $item['storeView'];

            if (!in_array($storeView, $selectedStoreView)) {
                $selectedStoreView[] = $storeView;
                $uniqueSelections[] = $item;
            }
        }

        return $uniqueSelections;
    }

    /**
     * @return array
     * @throws FileSystemException
     */
    public function execute(): array
    {
        $amountUpdatedPerStoreView = [];

        $settings = $this->import->run();

        $storeViewSettings = $this->removeDuplicateStoreViews($settings['storeViewsSettings']);

        foreach ($storeViewSettings as $setting) {

            $this->_storeManager->setCurrentStore($setting['storeView']);

            $productIdsArray = $this->getProductCollection($setting);

            $result = $this->_findUrlKeys->getProductsFilteredByUmlauts($productIdsArray, 'yes');

            $amountUpdatedPerStoreView[] = [
                'Storeview' => $setting['storeView'],
                'Updated'   => count($result['updatedUrlKeys'])
            ];

            if (count($result['couldNotSave']) >= 2) { // count's minimum is 1 due to headers

                $filename = $this->filenameCouldNotSave($setting['label']);
                $this->csvExporter->exportData($result['couldNotSave'], $filename, $dir='/log');
            }
        }

        return $amountUpdatedPerStoreView;
    }
}
