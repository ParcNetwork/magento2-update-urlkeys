<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Parc\UpdateUrlKeys\Cron\UpdateUrlKeys;
use Magento\Store\Model\StoreManagerInterface;

class RunNow extends Field
{
    /**
     * @var UpdateUrlKeys
     */
    protected UpdateUrlKeys $updateUrlKeys;

    protected StoreManagerInterface $storeManager;

    /**
     * @param Context $context
     * @param UpdateUrlKeys $updateUrlKeys
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        UpdateUrlKeys $updateUrlKeys,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->updateUrlKeys = $updateUrlKeys;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    private function getAllStoreViews(): array
    {
        $storeViews = [];

        $storeViewsCollection = $this->storeManager->getStores();

        foreach ($storeViewsCollection as $storeView) {

            $storeViews[] = [
                'value' => $storeView->getId(),
                'label' => $storeView->getName()
            ];
        }

        return $storeViews;
    }

    /**
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $label = $element->getLabel();

        $html = '<button id="product_run_now_button" type="button" class="action-default">';
        $html .= '<span>' . $label . '</span>';
        $html .= '</button>';

        $url = $this->getUrl('updateurlkeys/runnow/runnow');

        $mappingArray = json_encode(
            $this->getAllStoreViews()
        );

        $html .= '<script>
        let mappingArray = " . json_encode($mappingArray) . ";
        require(["jquery", "Magento_Ui/js/modal/alert"], function ($, alert) {
            $(document).ready(function () {
                $("#product_run_now_button").on("click", function () {
                    $.ajax({
                        type: "GET",
                        url: "' . $url . '",
                        showLoader: true,
                        success: function (response) {
                            let htmlContent = "";
                            let mappingArray = ' . $mappingArray . ';
                            let alertTitle = "Script has been executed";
                            if (response.length >= 1) {
                                for (let i = 0; i < response.length; i++) {
                                    let entry = response[i];
                                    let storeview = entry.Storeview;
                                    let updated = entry.Updated;
                                    let label = "";
                                    for (let j = 0; j<mappingArray.length; j++) {
                                        if (mappingArray[j].value === storeview) {
                                            label = mappingArray[j].label;
                                            break;
                                        }
                                    }
                                    htmlContent += label + " - items updated: " + updated + "<br>";
                                }
                            } else {
                                alertTitle = "Error"
                                htmlContent = "Please select at least one Storeview and save the configuration.";
                            }
                            alert({
                                title: alertTitle,
                                content: htmlContent,
                                actions: {
                                    always: function(){}
                                }
                            });
                        },
                        error: function () {
                            let alertTitle = "Error"
                            alert({
                                title: alertTitle,
                                content: "An error occurred while starting the script.",
                                actions: {
                                    always: function(){}
                                }
                            });
                        }
                    });
                });
            });
        });

    </script>';

        return $html;
    }

}

