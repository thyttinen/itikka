<?php

/**
 * Description of BasicTest
 *
 * The most basic test possible for testing the unit testing process
 */
class BasicTest extends CDbTestCase {
   
    // Contains the active records used for this test. The fixtures are reset each time the test is run
    public $fixtures = array(
        'items' => 'Item',
        'types' => 'Type'
        );
    
    
    
    // All test functions are formatted testExample
    // This adds a type and then finds it, testing the saving and retrieving of types
    public function testType() {
        $type = new Type;
        $type->name = 'Test-type';
        $this->assertTrue($type->save(false));
        
        $type = Type::model()->findByPk($type->id);
        $this->assertTrue($type instanceof Type);
    }

    
}

?>
