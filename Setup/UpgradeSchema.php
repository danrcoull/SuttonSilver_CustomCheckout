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
        $answersTable = $setup->getTable('suttonsilver_questionanswers');
        $valuesTable = $setup->getTable('suttonsilver_questionvalues');

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

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            if ($setup->getConnection()->isTableExists($answersTable) == true) {
                $columns = [
                    'value' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Question Answer Value',
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($answersTable, $name, $definition);
                }
            }
        }


        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            if ($setup->getConnection()->isTableExists($valuesTable) == true) {
                $columns = [
                    'question_saved_value' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Question Saved Value',
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($valuesTable, $name, $definition);
                }
            }
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'question_depends_on' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Question Depends On',
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $columns = [
                'home_address' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Home Address',
                ]
            ];

            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($setup->getTable('customer_entity'), $name, $definition);
            }

        }



        $setup->endSetup();
    }


}
