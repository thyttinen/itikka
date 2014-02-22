<?php

/**
 * Description of CRUDTest
 *
 */
class CRUDTest extends CTestCase {
    
    public function testCRUD() {
        
        $type = Type::add("Type1");
        $item1 = Item::add("Item1", $type->id);
        $item2 = Item::add("Item2", $type->id);
        $dependency = Dependency::add($item1->id, $item2->id);
        $propertyTemplate = PropertyTemplate::add($type->id, 'Property1', 'int', false, false);
        $property = Property::add($item1->id, 'Property1', 'int', 1);
        $modificationEvent = ModificationEvent::add($item1->id, 'Created for testing', true);
        
        $property->updateValue('int', 2);
        $this->assertTrue($property->value_int == 2);
        
        $this->assertFalse(is_null($type));
        $this->assertFalse(is_null($item1));
        $this->assertFalse(is_null($item2));
        $this->assertFalse(is_null($dependency));
        $this->assertFalse(is_null($propertyTemplate));
        $this->assertFalse(is_null($property));
        $this->assertFalse(is_null($modificationEvent));
        
        $modificationEvent->delete();
        $property->delete();
        $propertyTemplate->delete();
        $dependency->delete();
        $item1->delete();
        $item2->delete();
        $type->delete();
        
        
        
    }
    
    
    
}

?>
