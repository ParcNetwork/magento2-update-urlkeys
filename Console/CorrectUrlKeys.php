<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Console;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Parc\UpdateUrlKeys\Cron\UpdateUrlKeys;
use Parc\UpdateUrlKeys\Model\FindUrlKeys;
use Parc\UpdateUrlKeys\Model\CsvExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CorrectUrlKeys extends Command
{
    protected const KEY_STOREVIEW = 'storeview';

    protected const KEY_CSV = 'csv';

    protected const KEY_UPDATE = 'update';

    protected const KEY_SKU = 'sku';

    protected const KEY_ALL = 'all';

    protected const KEY_TODAY = 'today';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $_storeManager;

    /**
     * @var FindUrlKeys
     */
    private FindUrlKeys $_findUrlKeys;

    /**
     * @var CsvExporter
     */
    private CsvExporter $_csvExporter;

    /**
     * @var OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var State
     */
    protected State $state;

    /**
     * @var UpdateUrlKeys
     */
    protected UpdateUrlKeys $updateUrlKeys;

    /**
     * @param StoreManagerInterface $_storeManager
     * @param FindUrlKeys     $_findUrlKeys
     * @param CsvExporter     $_csvExporter
     * @param State           $state
     * @param UpdateUrlKeys   $updateUrlKeys
     */
    public function __construct(
        StoreManagerInterface $_storeManager,
        FindUrlKeys $_findUrlKeys,
        CsvExporter $_csvExporter,
        State $state,
        UpdateUrlKeys $updateUrlKeys
    ) {
        $this->_storeManager = $_storeManager;
        $this->_findUrlKeys = $_findUrlKeys;
        $this->_csvExporter = $_csvExporter;
        $this->state = $state;
        $this->updateUrlKeys = $updateUrlKeys;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('parc:updateurlkeys')
             ->setDescription('Generates csv-file containing
                                        products old to new url-key comparison')
             ->setDefinition($this->getOptionsList());

        parent::configure();
    }

    public function getOptionsList(): array
    {
        return [
            new InputOption(self::KEY_STOREVIEW, 's', InputOption::VALUE_OPTIONAL, 'store-view name'),
            new InputOption(self::KEY_CSV, 'c', InputOption::VALUE_OPTIONAL, 'generate csv'),
            new InputOption(self::KEY_SKU, null, InputOption::VALUE_OPTIONAL, 'add sku to collection'),
            new InputOption(self::KEY_UPDATE, 'u', InputOption::VALUE_OPTIONAL, 'update url keys'),
            new InputOption(self::KEY_ALL, 'a', InputOption::VALUE_OPTIONAL, 'update all storeviews'),
            new InputOption(self::KEY_TODAY, 't', InputOption::VALUE_OPTIONAL, 'products lastmodified == today')
        ];
    }

    /**
     * @param string|null $storeView
     *
     * @return array|string
     */
    private function getStoreData(string $storeView = null): array|string
    {
        $storeDataArray = [];
        $storeManagerDataList = $this->_storeManager->getStores();

        if ($storeView !== null) {
            foreach ($storeManagerDataList as $key => $value) {
                if (str_contains($value['code'], $storeView)) {
                    return [[
                        'id' => $key,
                        'name' => $value['code']
                    ]];
                }
            }
            return "Store View '$storeView' was not found.";
        } else {
            foreach ($storeManagerDataList as $key => $value) {
                $storeDataArray[] = [
                    'id' => $key,
                    'name' => $value['code']
                ];
            }
            return $storeDataArray;
        }
    }

    /**
     * @param $storeView
     *
     * @return string
     */
    private function filenameCsv($storeView): string
    {

        $filename = 'url_keys_' . $storeView . '.csv';
        return str_replace(' ', '', $filename);
    }

    private function filenameCouldNotSave($storeView): string
    {

        $filename = 'couldnotsave_' . $storeView . '.csv';
        return str_replace(' ', '', $filename);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int 0 or 1 exit code
     * @throws FileSystemException
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        $storeView = $input->getOption(self::KEY_STOREVIEW);

        $updateCommand = $input->getOption(self::KEY_UPDATE);

        $addSkuToCollection = $input->getOption(self::KEY_SKU);

        $generateCsv = $input->getOption(self::KEY_CSV);

        $updateAllStoreViews = $input->getOption(self::KEY_ALL);

        $today = $input->getOption(self::KEY_TODAY);

        if ($updateAllStoreViews !== null) {

            $storeViewId = $this->getStoreData();
        } else {

            $storeViewId = $this->getStoreData($storeView);
        }

        foreach ($storeViewId as $item) {

            $this->_storeManager->setCurrentStore($item['id']);

            $productIdsArray = $this->_findUrlKeys->getProductCollection(
                $item['id'],
                $addSkuToCollection,
                $today
            );

            $result = $this->_findUrlKeys->getProductsFilteredByUmlauts(
                $productIdsArray,
                $updateCommand
            );

            $dataToCsv = $result['dataToCsv'];
            $couldNotSave = $result['couldNotSave'];

            if (count($couldNotSave) >= 2) { // count's minimum is 1 due to headers
                $filename = $this->filenameCouldNotSave($item['name']);
                $this->_csvExporter->exportData($couldNotSave, $filename, $dir='/log');
            }

            if ($generateCsv !== null) {
                $filename = $this->filenameCsv($item['name']);
                $this->_csvExporter->exportData($dataToCsv, $filename);
            }
        }

        return 0;
    }
}
