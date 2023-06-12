<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Ranges
 */
class Imports extends AbstractFieldArray
{
    private $storeViewRenderer;

    private $yesnoRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('storeview', [
            'label' => __('Select a store view'),
            'renderer' => $this->getStoreviewsRenderer(),
            'class' => 'required-entry'
        ]);
        $this->addColumn('enabled', [
            'label' => __('Do you want to update all products or only those from today?'),
            'renderer' => $this->getYesNoRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $storeViews = $row->getData('storeViews');
        if (is_array($storeViews)) {

            foreach ($storeViews as $storeView) {

                $hash = 'option_' . $this->getStoreviewsRenderer()
                                         ->calcOptionHash($storeView);
                $options[$hash] = 'selected="selected"';
            }
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return StoreviewsColumn
     * @throws LocalizedException
     */
    private function getStoreviewsRenderer()
    {
        if (!$this->storeViewRenderer) {
            $this->storeViewRenderer = $this->getLayout()->createBlock(
                StoreviewsColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->storeViewRenderer;
    }

    /**
     * @throws LocalizedException
     */
    private function getYesNoRenderer()
    {
        if (!$this->yesnoRenderer) {
            $this->yesnoRenderer = $this->getLayout()->createBlock(
                YesnoColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->yesnoRenderer;
    }
}
