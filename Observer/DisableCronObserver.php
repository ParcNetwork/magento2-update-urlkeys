<?php
namespace Parc\UpdateUrlKeys\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ValueInterface;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\ScopeInterface;

class DisableCronObserver implements ObserverInterface
{
    /**
     * @var WriterInterface
     */
    protected WriterInterface $configWriter;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * Constructor
     *
     * @param WriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $section = 'parc_urlkeys';
        $groupId = 'cronjob';
        $field = 'enabledisable';

        $value = $this->scopeConfig->getValue(
            $section . '/' . $groupId . '/' . $field,
            ScopeInterface::SCOPE_STORE
        );

        if ($value == '0') {

            $configPath = 'parc_urlkeys/cronjob/configure';
            $this->configWriter->save($configPath, '');
        }
    }
}
