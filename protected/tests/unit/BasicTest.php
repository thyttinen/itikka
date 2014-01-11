<?php

/**
 * Description of BasicTest
 *
 * The most basic test possible for testing the unit testing process
 */
class BasicTest extends CTestCase {
   
 
    
    
    
    // All test functions are formatted testExample
    public function testType() {
        $type = new Type;
        $type->name = 'Test-type';
        $this->assertTrue($type->name == 'Test-type');
        
    }

    
}

?>
