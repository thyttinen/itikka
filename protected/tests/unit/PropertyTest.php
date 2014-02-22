<?php


/**
 * Description of PropertyTest
 *
 */
class PropertyTest extends CTestCase {
    
    public function testProperty() {
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        $type = Type::getByName("TestType1")[0];
        
        $item1 = new Item;
        $item1->name = "TestItem1";
        $item1->type_id = $type->id;
        $item1->save();
        $item1 = Item::getByName("TestItem1")[0];
        
        
        $property = new Property;
        $property->name = "TestProperty1";
        $property->item_id = $item1->id;
        $property->save();
     
        
        $temp = Property::getByItem($item1->id)[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
        
        $item1->delete();
        $type->delete();
    }
}

?>
