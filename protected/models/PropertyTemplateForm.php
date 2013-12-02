<?php

/* Saves this property in the database */

class PropertyTemplateForm extends CFormModel {
    
    public $name;
    public $value_type;
    public $value_required = false;
    public $list_existing_values = false;
    
    public function rules() {
                
        // Required property
        $rules = array();
        $rules[] = array('name', 'required');
        $rules[] = array('value_type', 'required');
        $rules[] = array('value_required', 'required');
        $rules[] = array('list_existing_values', 'required');
        
        return $rules;
    }

    /**
     * Save PropertyTemplate
     * 
     * @param type $type Type to save the PropertyTemplate for
     * @return type
     */
    function savePropertyTemplate($type) {
        return PropertyTemplate::add(
                $type->id, 
                $this->name, 
                $this->value_type, 
                $this->value_required, 
                $this->list_existing_values);
    }
    
    function getPropertyTypes() {
        return array(
            Property::ValueTypeText => 'Text',
            Property::ValueTypeDate => 'Date',
            Property::ValueTypeInt => 'Integer',
            Property::ValueTypeDouble => 'Double',
        );
    }

}

?>