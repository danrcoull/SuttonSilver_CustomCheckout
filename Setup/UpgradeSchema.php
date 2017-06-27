<?php


namespace SuttonSilver\CustomCheckout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        //handle all possible upgrade versions

        if(!$context->getVersion()) {
            //no previous version found, installation, InstallSchema was just executed
            //be careful, since everything below is true for installation !
        }

        $tableName = $setup->getTable('suttonsilver_question');

        if (version_compare($context->getVersion(), '1.0.1') < 0) {


            if ($setup->getConnection()->isTableExists($tableName) == true) {

                $columns = [
                    'question_is_active' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        'nullable' => true,
                        'comment' => 'Is Active',
                    ],
                    'question_name' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Question Name',
                    ],
                    'question_is_required' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        'nullable' => true,
                        'comment' => 'Is Required',
                    ],
                    'question_placeholder' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Placeholder',
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }

        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            if ($setup->getConnection()->isTableExists($tableName) == true) {

                $columns = [
                    'question_tooltip' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'ToolTip',
                    ],
                    'question_position' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Position',
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }

        $setup->endSetup();
    }
}
