<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Block\Adminhtml\Form\Field;

use Magento\Eav\Model\Config;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class VisibleProductsColumn extends Select
{
    /**
     * @var Config
     */
    private $eavConfig;

    public function __construct(
        Config $eavConfig,
        Context $context,
        array $data = []
    ) {
        $this->eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
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

    private function getSourceOptions(): array
    {
        return [
            ['label' => 'Yes', 'value' => '1'],
            ['label' => 'No', 'value' => '0'],
        ];
    }
}
