<?php


/**
 * Description of ItemTest
 *
 */
class ItemTest extends CTestCase {
    
    
    
    
    public function testItem() {
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        $type = Type::getByName("TestType1")[0];
        
        $item = new Item;
        $item->name = "TestItem1";
        $item->type_id = $type->id;
        $item->save();
      
        $temp = Item::getByName("TestItem1")[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
        
        $type->delete();
    }
}
?>
