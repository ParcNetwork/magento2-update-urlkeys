<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Parc\UpdateUrlKeys\Cron\UpdateUrlKeys;

class RunNow extends Field
{
    /**
     * @var UpdateUrlKeys
     */
    protected UpdateUrlKeys $updateUrlKeys;

    /**
     * @param Context $context
     * @param UpdateUrlKeys $updateUrlKeys
     * @param array $data
     */
    public function __construct(
        Context $context,
        UpdateUrlKeys $updateUrlKeys,
        array $data = []
    ) {
        $this->updateUrlKeys = $updateUrlKeys;
        parent::__construct($context, $data);
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

        $html .= '<script>
        require(["jquery", "Magento_Ui/js/modal/alert"], function ($, alert) {
            $(document).ready(function () {
                $("#product_run_now_button").on("click", function () {
                    $.ajax({
                        type: "GET",
                        url: "' . $url . '",
                        showLoader: true,
                        success: function (response) {
                            alert({
                                title: "Script started",
                                content: "The script has been started.",
                                actions: {
                                    always: function(){}
                                }
                            });
                        },
                        error: function () {
                            alert({
                                title: "Error",
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

