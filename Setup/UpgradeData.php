<?php


namespace SuttonSilver\CustomCheckout\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    private $eavSetupFactory;
    private $eavConfig;

    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactor)
    {
        $this->eavSetupFactory = $customerSetupFactor;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), "1.0.2", "<")) {
        //Your upgrade script
        }
        $setup->endSetup();
    }
}
