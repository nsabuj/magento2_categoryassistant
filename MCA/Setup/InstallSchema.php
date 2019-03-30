<?php
/**
* Copyright © 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace CatalogAssistant\MCA\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
    * {@inheritdoc}
    * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
    */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
          /**
          * Create table 'greeting_message'
          */
//        $installer = $this;
//        $setup->startSetup();
   

//         $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
//    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
//    $connection = $resource->getConnection();






// $tableName = $installer->getTable('uts_category_discount_history');
// $columnName = 'id';

// if ($connection->tableColumnExists($tableName, $columnName) === false) {

//    $sql ="CREATE TABLE uts_category_discount_history (
// id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// category_id INT(12),
// action_type VARCHAR(30),
// percentage_amount INT(12),
// updated_by VARCHAR(50),
// updated_at VARCHAR(50)
// )";
// $connection->query($sql);

// }



//    $setup->endSetup();

  
  //////////////////////////////////////////////

 
$installer = $setup;
 $installer->startSetup(); // Get tutorial_simplenews table
 $tableName = $installer->getTable('uts_category_discount_history'); 
 // Check if the table already exists 
 if ($installer->getConnection()->isTableExists($tableName) != true) { 
// Create tutorial_simplenews table
 $table = $installer->getConnection() ->newTable($tableName) 
 ->addColumn( 'id', Table::TYPE_INTEGER, null, [ 'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true ], 'ID' ) 
 ->addColumn( 'category_id', Table::TYPE_INTEGER, null, ['nullable' => false, 'default' => 0], 'Category Id' ) 
 ->addColumn( 'action_type', Table::TYPE_TEXT, null, ['nullable' => false, 'default' => ''], 'Action Type' ) 
 ->addColumn( 'percentage_amount', Table::TYPE_INTEGER, null, ['nullable' => false, 'default' => 0], 'Percentage Amount' ) 
 ->addColumn( 'updated_by', Table::TYPE_TEXT, null, ['nullable' => false], 'Updated By') 
 ->addColumn( 'updated_at', Table::TYPE_TEXT, null, ['nullable' => false], 'Updated At' ) 
 
 ->setComment('Discount history table') ->setOption('type', 'InnoDB') ->setOption('charset', 'utf8'); 
 $installer->getConnection()->createTable($table); 

}


  $installer->endSetup();

      }
}