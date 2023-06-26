<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class Imports extends AbstractFieldArray
{
    private $storeViewRenderer;

    private $yesnoRenderer;

    private $enabledDisabledProductsRenderer;

    private $visibleProductsRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('storeView', [
            'label' => __('StoreView'),
            'renderer' => $this->getStoreviewsRenderer(),
            'class' => 'required-entry'
        ]);
        $this->addColumn('lastModified', [
            'label' => __('Update method'),
            'renderer' => $this->getYesNoRenderer()
        ]);
        $this->addColumn('enabledDisabled', [
            'label' => __('Exclude disabled products?'),
            'renderer' => $this->getEnabledDisabledProductsRenderer()
        ]);
        $this->addColumn('visibility', [
            'label' => __('Exclude not visible products?'),
            'renderer' => $this->getVisibleProductsRenderer()
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
    private function getStoreviewsRenderer(): StoreviewsColumn
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
     * @return YesnoColumn
     * @throws LocalizedException
     */
    private function getYesNoRenderer(): YesnoColumn
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

    /**
     * @return EnabledDisablebProductsColumn
     * @throws LocalizedException
     */
    private function getEnabledDisabledProductsRenderer(): EnabledDisablebProductsColumn
    {
        if (!$this->enabledDisabledProductsRenderer) {
            $this->enabledDisabledProductsRenderer = $this->getLayout()->createBlock(
                EnabledDisablebProductsColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->enabledDisabledProductsRenderer;
    }

    /**
     * @return VisibleProductsColumn
     * @throws LocalizedException
     */
    private function getVisibleProductsRenderer(): VisibleProductsColumn
    {
        if (!$this->visibleProductsRenderer) {
            $this->visibleProductsRenderer = $this->getLayout()->createBlock(
                VisibleProductsColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->visibleProductsRenderer;
    }
}
