<?php


/**
 * Description of PropertyTemplateTest
 *
 */
class PropertyTemplateTest {
    
    
     public function testPropertyTemplate() {
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        $type = Type::getByName("TestType1")[0];
        
        
        $propertyTemplate = new PropertyTemplate;
        $propertyTemplate->type_id = $type->id;
        $propertyTemplate->name = "TestPropertyTemplate1";
        $propertyTemplate->value_type = "text";
        $propertyTemplate->save();
        
      
        $temp = PropertyTemplate::getByType($type->id)[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
        
        $type->delete();
    }
    
}

?>
