<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Cron;

use Magento\Framework\Exception\FileSystemException;
use Magento\Store\Model\StoreManagerInterface;
use Parc\UpdateUrlKeys\Model\FindUrlKeys;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Parc\UpdateUrlKeys\Model\Import;
use Parc\UpdateUrlKeys\Model\CsvExporter;

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
        $storeId = $setting['storeview'];
        $updateToday = $setting['enabled'];

        $productCollection = $this->_productCollectionFactory->create();

        if ((int) $updateToday === 1) {

            $today = date('Y-m-d');
            $productCollection->addFieldToFilter('updated_at', ['from' => $today]);
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
     * @return void
     * @throws FileSystemException
     */
    public function execute(): void
    {
        $settings = $this->import->run();
        $test = [['test']];
        $this->csvExporter->exportData($test, 'test');

        $storeViewSettings = $settings['storeViewsSettings'];

        foreach ($storeViewSettings as $setting) {

            $this->_storeManager->setCurrentStore($setting['storeview']);

            $productIdsArray = $this->getProductCollection($setting);

            $result = $this->_findUrlKeys->getProductsFilteredByUmlauts($productIdsArray, 'yes');

            if (count($result['couldNotSave']) >= 2) { // count's minimum is 1 due to headers

                $filename = $this->filenameCouldNotSave($setting['label']);
                $this->csvExporter->exportData($result['couldNotSave'], $filename, $dir='/log');
            }
        }
    }
}
