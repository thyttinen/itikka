<?php


/**
 * Description of ModificationEventTest
 *
 */
class ModificationEventTest extends CTestCase {
    
    
    
    public function testModificationEvent() {
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        $type = Type::getByName("TestType1")[0];
        
        $item = new Item;
        $item->name = "TestItem1";
        $item->type_id = $type->id;
        $item->save();
        $item = Item::getByName("TestItem1")[0];
        
        
        $modificationEvent = new ModificationEvent;
        $modificationEvent->item_id = $item->id;
        $modificationEvent->modification_date = date("11/06/1991");
        $modificationEvent->save();
        
        
        $temp = ModificationEvent::getByItem($item->id)[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
        
        $item->delete();
        $type->delete();
    }
}

?>
