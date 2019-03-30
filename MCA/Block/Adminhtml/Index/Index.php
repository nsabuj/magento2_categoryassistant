<?php

namespace CatalogAssistant\MCA\Block\Adminhtml\Index;


class Index extends \Magento\Framework\View\Element\Template
{

    protected $_categoryCollectionFactory;
    protected $_categoryHelper;



    public function __construct(\Magento\Backend\Block\Widget\Context $context,      
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []


    )
    {
        

               $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }




public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');        
        
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
                
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter();
        }
        
        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
        
        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }    
        
        return $collection;
    }

    public function getFormAction()
    {
        return $this->getUrl('mca/save/index', ['_secure' => true]);
    }


        public function getDiscountbyCategory($cat_id){

             $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
   $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
   $connection = $resource->getConnection();

             $tableName = $resource->getTableName('category_discount_history');
             $sql = "Select percentage_amount FROM " . $tableName." where category_id =".$cat_id;
             $result = $connection->fetchOne($sql);
             if($result){
              
              return $result;

             }else{
                return 0;
             }
             
    }


   

}
