<?php 
namespace CatalogAssistant\MCA\Controller\Adminhtml\Save;
 
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
 
class Index extends \Magento\Backend\App\Action
{







public function execute()
{


            $cat_id = $this->getRequest()->getPostValue("cat_id");

            $cat_action = $this->getRequest()->getPostValue("price_action");

            $cat_amount = $this->getRequest()->getPostValue("update_amount");

             if(empty($cat_id)){

              $cat_id= $this->getRequest()->getPostValue("category_id");
             }








$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        

$categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
$categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
$categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

$categoryId = $cat_id; // YOUR CATEGORY ID
$category = $categoryFactory->create()->load($categoryId);

$categoryProducts = $category->getProductCollection()
                             ->addAttributeToSelect('*');


$old_discount=$this->getDiscountbyCategory($cat_id);
$discount_amount=$cat_amount;


     $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
   $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
   $connection = $resource->getConnection();
           $table1 = $resource->getTableName('catalog_product_entity_decimal');
           $table2 = $resource->getTableName('eav_attribute');

           $entity_type_id=4;
           $attribute_code='special_price';
          $sql=      $sql = "Select attribute_id FROM " .$table2." Where entity_type_id = '$entity_type_id' And attribute_code= '$attribute_code'";
$attribute_id = $connection->fetchOne($sql);



foreach ($categoryProducts as $product) 
{


$product_id=$product->getId();



//var_dump($product->getStoreIds());


// $sql= "Update ".$table1." val Set  val.value = (val.value + $priceToAdd Where  val.attribute_id = (
//      Select attribute_id From ".$table2." eav WHERE eav.entity_type_id = 4 AND eav.attribute_code = 'special_price')";

    $product_price=$product->getPrice();
 
    $error='';

    $updated_price=0;

    // if($cat_action==1){
    //     $updated_price=$product_price+($product_price*($cat_amount/100));
    //     $product->setPrice($updated_price);
            
           
    // }else
     if($cat_action==0){

        $discount_to_apply=$discount_amount;
       // die($total_discount);
        if($discount_to_apply<100){
        $updated_price=$product_price-($product_price*($discount_to_apply/100));
        // $product->setSpecialPrice($updated_price);
        
        $sql = "Select value_id FROM " . $table1." Where entity_id='{$product_id}' And attribute_id=".$attribute_id;

        $exist = $connection->fetchOne($sql);



        if($exist){
           
                   $sql= "Update ".$table1." Set value='{$updated_price}' Where entity_id='{$product_id}' And attribute_id=".$attribute_id;


                    $connection->query($sql);
        

                  }else{

                 

                    $sql = "Insert Into " . $table1. " (value_id, attribute_id,store_id, entity_id, value) Values ('',$attribute_id,'0',$product_id,$updated_price)";

                    $connection->query($sql);




                  }



        


         
         }

    }else if($cat_action==2){

        

            $discount_amount=0;
         //   $product->setSpecialPrice(null);


      //   $updated_price=null;

                 $sql= "Delete From ".$table1." Where entity_id='{$product_id}' And attribute_id=".$attribute_id;


        $connection->query($sql);

        

    }

//$product->save();

  


}






















if($cat_action==0){

$this->setDiscounttoCategory($cat_action,$cat_id,$discount_amount);

}else if($cat_action==2){


$this->setDiscounttoCategory($cat_action,$cat_id,0);

}









            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
           $redirect->setUrl($this->_redirect->getRefererUrl());

            $this->messageManager->addSuccess(__('Discount Price updated successfully'));
        //   }
           
            return $redirect;
	}


    protected function _isAllowed(){
        return true;
    }



     public function setDiscounttoCategory($cat_action,$cat_id, $amount){
     
     $user_id='admin@gmail.com';




     $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
   $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
   $connection = $resource->getConnection();
   $datetime= date("Y-m-d h:i:sa");

   
   if($cat_action==0 || $cat_action==2){

 





    $action_type='discount';

         $tableName = $resource->getTableName('category_discount_history');

     $sql = "Select id FROM " . $tableName." Where category_id =".$cat_id;
$result = $connection->fetchOne($sql); 
 
//die($result);

if(isset($result) && !empty($result)){


$sql = "Update " . $tableName . " Set percentage_amount = '{$amount}', updated_at= '{$datetime}' where category_id =".$cat_id;

$connection->query($sql);


}else{



     $sql = "Insert Into " . $tableName . " (id, category_id,action_type, percentage_amount, updated_by, updated_at ) Values ('',$cat_id,'$action_type',$amount,'$user_id','$datetime')";
          $connection->query($sql);



}

//Delete Data from table
//$sql = "Delete FROM " . $tableName." Where emp_id = 10";









   }else{

  $action_type=$cat_action;

      $tableName = $resource->getTableName('category_discount_history');
     $sql = "Insert Into " . $tableName . " (id, category_id,action_type, percentage_amount, updated_by, updated_at ) Values ('',$cat_id,'$action_type',$amount,'$user_id','$datetime')";
          $connection->query($sql);

   }




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


    // public function removeDiscounttoCategory($cat_id){

    // }


}