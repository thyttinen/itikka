<?php

/**
 * Description of PropertyForm
 * Works together with ItemForm in add_item.php
 * Stores the information for properties received from the actual html form in add_item.php
 * 
 */
class PropertyForm extends CFormModel {
    
    public $name;
    public $value;
    
    // PropertyTemplate for this property used for value_type, required, etc
    public $property_template;
    
    
    
    /* Rules for form input 
     */
    public function rules() {
        
        $rules = array();
        
        if ($this->property_template->value_required) {
            $rules[] = array('value', 'required');
        }
        
        return $rules;
    }
    
    
    /* Saves this property in the database */
    public function saveProperty($item) {
        
        if ($this->value != '') {
            return Property::add($item->id, $this->name, $this->property_template->value_type, $this->value);
        }
        
    }
    
}
?>
