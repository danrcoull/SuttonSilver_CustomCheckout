<?php


namespace SuttonSilver\CustomCheckout\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;
    private $eavConfig;

    public function __construct(\Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactor)
    {
        $this->eavSetupFactory = $customerSetupFactor;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        
         $setup->startSetup();
        
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'membership_number',
            [
                'type' => 'varchar',
                'label' => 'CILEx Membership Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 100
            ]
        )->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'studied_with_us_before',
            [
                'type' => 'int',
                'label' => 'Studied With Us Before?',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => true,
                'default' => '0',
                'sort_order' => 101,
                'system' => false,
                'position' => 100
            ]
        )->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'previous_surname',
            [
                'type' => 'varchar',
                'label' => 'CILEx Membership Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 100
            ]
        )->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'previous_postcode',
            [
                'type' => 'varchar',
                'label' => 'CILEx Membership Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 100
            ]
        )->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'daytime_phone_number',
            [
                'type' => 'varchar',
                'label' => 'Daytime Phone Number Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 105
            ]
        )->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'mobile_number',
            [
                'type' => 'varchar',
                'label' => 'Mobile Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 106
            ]
        );

        foreach(['membership_number','studied_with_us_before','previous_surname','previous_postcode','daytime_phone_number','mobile_number'] as $key) {
            $sampleAttribute = $eavSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, $key);
            $sampleAttribute->setData(
                'used_in_forms',
                ['adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register']
            );
            $sampleAttribute->save();
        }

        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'dx_number',
            [
                'type' => 'varchar',
                'label' => 'DX Number',
                'input' => 'input',
                'required' => true,
                'default' => '0',
                'sort_order' => 100,
                'system' => false,
                'position' => 100
            ]
        );
        $sampleAttribute = $eavSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'dx_number');
        $sampleAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );
        $sampleAttribute->save();
        
         $setup->endSetup();

    }
}
