<?php

/**
 * Description of BasicTest
 *
 * The most basic test possible for testing the unit testing process
 */
class BasicTest extends CTestCase {
   
 
    
    
    
    // All test functions are formatted testExample
    // These types of test require a test database to run correctly
    public function testType() {
        $type = new Type;
        $type->name = 'Test-type';
        $this->assertTrue($type->name == 'Test-type');
        
    }

    // Basic unit test that does not use the database
    public function testItemClass() {
        $item = new Item;
        $this->assertTrue(is_a($item, 'Item'));
    }
    
}

?>
