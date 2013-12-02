<?php

/**
 * Description of ItemForm
 * The class storing the information received from the actual html form in add_item.php
 *
 * $type is in serial id form, not as a string
 */
class TypeForm extends CFormModel {
    
    public $name;

    /* Form labels for attributes differing from the ones above, e.g. "Item Name" instead of "Name" */
    public function attributeLabels() {
        
    }
    
    /* Rules for form input 
     */
    public function rules() {
        
        $rules = array();
        $rules[] = array('name', 'required');
        
        return $rules;
    }
    
    /* Saves the type upon submitting the form */
    public function saveType() {
        return Type::add($this->name);
    }
    
    
}

?>
