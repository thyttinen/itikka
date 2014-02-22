<?php

/**
 * Description of DependencyTest
 *
 */
class DependencyTest extends CTestCase {
    
    
    public function testDependency() {
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        $type = Type::getByName("TestType1")[0];
        
        $item1 = new Item;
        $item1->name = "TestItem1";
        $item1->type_id = $type->id;
        $item1->save();
        $item1 = Item::getByName("TestItem1")[0];
        
        $item2 = new Item;
        $item2->name = "TestItem2";
        $item2->type_id = $type->id;
        $item2->save();
        $item2 = Item::getByName("TestItem2")[0];
     
        $dependency = new Dependency;
        $dependency->item_id = $item1->id;
        $dependency->depends_on = $item2->id;
        $dependency->save();
        
        $temp = Dependency::getByDependentItem($item1->id)[0];
        $this->assertFalse(is_null($temp));
        $temp = Dependency::getByDependenceItem($item2->id)[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
        
        $item1->delete();
        $item2->delete();
        $type->delete();
    }
    
    
    
}

?>
