<?php

/**
 * Description of AddItemForm
 * The class storing the information received from the actual html form in add_item.php
 *
 * $type is in serial id form, not as a string
 */
class ItemForm extends CFormModel {
    
    public $name;
    public $type;
    
    
    /* Form labels for attributes differing from the ones above, e.g. "Item Name" instead of "Name" */
    public function attributeLabels() {
        
    }
    
    /* Rules for form input */
    public function rules() {
        return array(
            
			array('name, type', 'required')
		);
    }
    
    /* Returns all available types in the database as an array with id and name pairings */
    public function getAvailableTypes() {
        $types = Type::getAll();
        $data = array();
        
        foreach ($types as $type) {
            $data[$type->id] = $type->name;
        }
        
        return $data;
    }
    
    /* Saves the item upon submitting the form */
    public function saveItem() {
        Item::add($this->name, $this->type);
        
    }
    
    
    /* Saves the received properties for this item */
    public function saveProperties($properties) {
        foreach($properties as $i => $property) {
            $item = Item::getByName($this->name);
            Property::add($item[0]->id, $property->name, Property::ValueTypeText, $property->value_text);
        }
    }
    
}

?>
