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
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $orderTable = 'sales_order';

            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($orderTable),
                    'export_processed',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        'comment' =>'Export Processed'
                    ]
                );
        }

	    if (version_compare($context->getVersion(), '1.0.5') < 0) {}
	    if (version_compare($context->getVersion(), '1.0.6') < 0) {}
	    if (version_compare($context->getVersion(), '1.0.7') < 0) {}

        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $this->homeAddress($setup);
        }
	    if (version_compare($context->getVersion(), '1.0.9') < 0) { }

        $setup->endSetup();
    }


    public function homeAddress($setup) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $customer = 1;
        $customerAddress = 2;

        $entities = $this->getDefaultEntities();
        $attributes = $entities['customer']['attributes'];
        foreach ($attributes as $attributeCode => $attribute) {
            $eavSetup->addAttribute(
                $customer,
                $attributeCode,
                $attribute
            );

        }


        $data = [];
        $attributeIds = [];
        $select = $setup->getConnection()->select()->from(
            ['ea' => $setup->getTable('eav_attribute')],
            ['entity_type_id', 'attribute_code', 'attribute_id']
        )->where(
            'ea.entity_type_id IN(?)',
            [$customer, $customerAddress]
        );

        foreach ($setup->getConnection()->fetchAll($select) as $row) {
            $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
        }

        foreach ($attributes as $attributeCode => $attribute) {
            $attributeId = $attributeIds[$customer][$attributeCode];
            $attribute['system'] = isset($attribute['system']) ? $attribute['system'] : true;
            $attribute['visible'] = isset($attribute['visible']) ? $attribute['visible'] : true;
            if ($attribute['system'] != true || $attribute['visible'] != false) {
                $usedInForms = ['customer_account_create', 'customer_account_edit', 'checkout_register'];
                if (!empty($attribute['adminhtml_only'])) {
                    $usedInForms = ['adminhtml_customer'];
                } else {
                    $usedInForms[] = 'adminhtml_customer';
                }
                if (!empty($attribute['admin_checkout'])) {
                    $usedInForms[] = 'adminhtml_checkout';
                }
                foreach ($usedInForms as $formCode) {
                    $data[] = ['form_code' => $formCode, 'attribute_id' => $attributeId];
                }
            }
        }


        if ($data) {
            $setup->getConnection()
                ->insertMultiple($setup->getTable('customer_form_attribute'), $data);
        }
    }

    public function getDefaultEntities()
    {
        $entities = [
            'customer' => [
                'entity_type_id' => \Magento\Customer\Api\CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                'entity_model' => 'Magento\Customer\Model\ResourceModel\Customer',
                'attribute_model' => 'Magento\Customer\Model\Attribute',
                'table' => 'customer_entity',
                'increment_model' => 'Magento\Eav\Model\Entity\Increment\NumericValue',
                'additional_attribute_table' => 'customer_eav_attribute',
                'entity_attribute_collection' => 'Magento\Customer\Model\ResourceModel\Attribute\Collection',
                'attributes' => [
                    'home_address' => [
                        'type' => 'static',
                        'label' => 'Home Address',
                        'input' => 'text',
                        'backend' => 'SuttonSilver\CustomCheckout\Model\Attribute\Customer\HomeAddress',
                        'required' => false,
                        'sort_order' => 83,
                        'visible' => false,
                    ],
                ],
            ],
        ];
        return $entities;
    }
}
