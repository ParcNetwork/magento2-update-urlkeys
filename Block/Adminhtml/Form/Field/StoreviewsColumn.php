<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\Form\Field;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class StoreviewsColumn extends Select
{

    private StoreManagerInterface $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value): static
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return $this
     */
    public function setInputId($value): static
    {
        return $this->setId($value);
    }

    public function setOptions($options): Select
    {
        $newOptions = [];
        foreach ($options as $option) {
            $newOptions[] = [
                'value' => $option['value'],
                'label' => $option['label']
            ];
        }
        return parent::setOptions($newOptions);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * Show all available store views
     *
     * @return array
     */
    private function getSourceOptions(): array
    {
        $storeViews = [];

        // Get all store views
        $storeViewsCollection = $this->storeManager->getStores();

        foreach ($storeViewsCollection as $storeView) {

            $storeViews[] = [
                'value' => $storeView->getId(),
                'label' => $storeView->getName()
            ];
        }

        return $storeViews;
    }
}
