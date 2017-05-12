<?php


namespace SuttonSilver\CustomCheckout\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
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
            'qutions_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['auto_increment' => true,'unsigned' => true],
            'qutions_id'
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
            'question_answer',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'question_answer'
        );
        

        
        $table_suttonsilver_question->addColumn(
            'product_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'product_ids'
        );
        

        $setup->getConnection()->createTable($table_suttonsilver_question);

        $setup->endSetup();
    }
}
