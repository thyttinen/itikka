<?php

/**
 * Description of AddItemForm
 *
 * $type is in serial id form
 */
class ItemForm extends CFormModel {
    
    public $name;
    public $type;
    
    
    
    public function attributeLabels() {
        
    }
    
    public function rules() {
        return array(
			// username and password are required
			array('name, type', 'required')
		);
    }
    
    public function getAvailableTypes() {
        $types = Type::getAll();
        $data = array();
        
        foreach ($types as $type) {
            $data[$type->id] = $type->type_name;
        }
        
        
        return $data;
    }
    
    public function saveItem() {
        
        Item::add($this->name, $this->type);
        
    }
    
}

?>
