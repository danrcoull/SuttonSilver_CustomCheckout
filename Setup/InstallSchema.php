<?php


namespace SuttonSilver\CustomCheckout\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_suttonsilver_question = $setup->getConnection()->newTable($setup->getTable('suttonsilver_question'));
        $table_suttonsilver_question->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        $table_suttonsilver_question->addColumn(
            'question',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'question'
        );
        $table_suttonsilver_question->addColumn(
            'question_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'question_type'
        );
        $table_suttonsilver_question->addColumn(
            'product_skus',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'product_skus'
        );


        $table_suttonsilver_questionanswers = $setup->getConnection()->newTable($setup->getTable('suttonsilver_questionanswers'));
        $table_suttonsilver_questionanswers->addColumn(
            'questionanswers_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        $table_suttonsilver_questionanswers->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'customer_id'
        );
        $table_suttonsilver_questionanswers->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'unsigned' => true],
            'question_id'
        );
        

        $table_suttonsilver_questionvalues = $setup->getConnection()->newTable($setup->getTable('suttonsilver_questionvalues'));
        $table_suttonsilver_questionvalues->addColumn(
            'questionvalues_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        $table_suttonsilver_questionvalues->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true,'nullable' => False],
            'question_id'
        );
        $table_suttonsilver_questionvalues->addColumn(
            'question_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Value'
        );
        

        $setup->getConnection()->createTable($table_suttonsilver_questionvalues);
        $setup->getConnection()->createTable($table_suttonsilver_questionanswers);
        $setup->getConnection()->createTable($table_suttonsilver_question);

        $setup->endSetup();
    }
}
