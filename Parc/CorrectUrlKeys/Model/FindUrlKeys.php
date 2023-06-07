<?php

declare(strict_types=1);

namespace Parc\CorrectUrlKeys\Model;

use Exception;
use IntegerNet\GermanUmlautUrls\Filter\TranslitUrl;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\AlreadyExistsException;

class FindUrlKeys
{

    /**
     * @var TranslitUrl
     */
    private TranslitUrl $_urlKeyFilter;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $_productCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $_productRepository;

    /**
     * @param TranslitUrl       $_urlKeyFilter
     * @param CollectionFactory $_productCollectionFactory
     * @param ProductRepositoryInterface $_productRepository
     */
    public function __construct(
        TranslitUrl                $_urlKeyFilter,
        CollectionFactory          $_productCollectionFactory,
        ProductRepositoryInterface $_productRepository
    ) {
        $this->_urlKeyFilter = $_urlKeyFilter;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->_productRepository = $_productRepository;
    }

    /**
     * @param array       $productIdsArray
     * @param null|string $updateCommand
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getProductsFilteredByUmlauts(
        array $productIdsArray,
        string|null $updateCommand
    ): array {
        $couldNotSave = [['productId', 'productSku', 'urlKey', 'newUrlKey']];

        $result = $this->getUrlKeys($productIdsArray);

        $urlKeys = $result['urlKeys'];

        $updateUrlKeys = $result['updateUrlKeys'];

        if ($updateCommand === 'yes' && !empty($updateUrlKeys)) {

            foreach ($updateUrlKeys as $item) {

                $product = $this->_productRepository->getById($item['id']);

                $productSku = $product->getSku();

                $urlKey = $product->getUrlKey();

                if (!empty($item['newUrlKey'])) {

                    if (in_array($item['newUrlKey'], $urlKeys)) {

                        $product->setUrlKey($item['newUrlKey'] . '-' . $productSku);

                    } else {

                        $product->setUrlKey('');
                    }
                    try {

                        $this->_productRepository->save($product);

                    } catch (AlreadyExistsException $e) {

                        $couldNotSave[] = [$product->getId(), $productSku,
                                           $urlKey, $item['newUrlKey']
                        ];

                        continue; // with following iterations
                    }

                    $urlKeys[] = $item['newUrlKey'];
                }
            }

        }

        return ['dataToCsv'    => $result['dataToCsv'],
                'couldNotSave' => $couldNotSave];
    }

    /**
     * @return array[]
     */
    private function getUrlKeys(array $productIdsArray): array
    {
        $tmpArray = [["SKU", "Name", "UrlKey", "NewUrlKey"]];

        $urlKeys = [];

        $updateUrlKeys = [];

        foreach ($productIdsArray as $item) {

            $product = $this->_productRepository->getById($item);
            $productSku = $product->getSku();
            $name = $product->getName();
            $urlKey = $product->getUrlKey();
            $newUrlKey = $this->_urlKeyFilter->filter($name);

            $tmpArray[] = [$productSku, $name, $urlKey, $newUrlKey];

            if (!empty($urlKey)) {

                $urlKeys[] = $urlKey;
            }

            if ($urlKey !== $newUrlKey) {

                $updateUrlKeys[] = [
                    'id'        => $item,
                    'newUrlKey' => $newUrlKey
                ];
            }
        }

        return ['urlKeys'       => $urlKeys,
                'updateUrlKeys' => $updateUrlKeys,
                'dataToCsv'     => $tmpArray];
    }

    /**
     * @param $storeId
     *
     * @return array
     */
    public function getProductCollection($storeId, $addSkuToCollection, $today): array
    {
        $productCollection = $this->_productCollectionFactory->create();

        if ($addSkuToCollection !== null) {

            $productCollection->addFieldToFilter('sku', $addSkuToCollection);
        }

        if ($today !== null) {

            $today = date('Y-m-d');
            $productCollection->addFieldToFilter('updated_at', ['from' => $today]);
        }

        return $productCollection->addStoreFilter($storeId)->getAllIds();
    }
}
