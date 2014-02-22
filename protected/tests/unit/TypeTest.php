<?php


/**
 * Description of TypeTest
 *
 */
class TypeTest extends CTestCase {
    
    public function testType() {
        
        $type = new Type;
        $type->name = "TestType1";
        $type->save();
        
        $temp = Type::getByName("TestType1")[0];
        $this->assertFalse(is_null($temp));
        $temp->delete();
    }
}

?>
