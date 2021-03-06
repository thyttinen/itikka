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
        
        // Required property
        if ($this->property_template->value_required) {
            $rules[] = array('value', 'required');
        }
        
        // Property format depending on value_type
        switch ($this->property_template->value_type) {
            case Property::ValueTypeDate: 
                $rules[] = array('value', 'date', 'format'=>array('yyyy-M-d', 'yyyy-MM-dd', 'd.M.yyyy', 'dd.MM.yyyy'));
                break;
            case Property::ValueTypeInt:
                $rules[] = array('value','numerical', 'allowEmpty' => true, 'integerOnly'=>true);
                break;
            case Property::ValueTypeDouble:
                $rules[] = array('value','numerical', 'allowEmpty' => true, 'integerOnly'=>false);
                break;
            case Property::ValueTypeText:
                $rules[] = array('value', 'type', 'type'=>'string');
                break;
        }
        
        
        return $rules;
    }
    
    
    
   /* Changes the label for this property from 'value' to the name of the property with the first letter capitalized */
    public function attributeLabels() {
        return array(
            'value' => ucfirst($this->name)
            
        );
    }
    
    
    
    /* Creates a list of PropertyForms according to type_id */
    public static function createPropertiesByType($type_id) {
        
        $templates = PropertyTemplate::getByType($type_id);
        
        $properties = array();
        
        foreach ($templates as $template) {
            $temp = new PropertyForm;
            $temp->name = $template->name;
            $temp->property_template = $template;
            $properties[] = $temp;
        }
        
        return $properties;
    }
    
     
    
    /* Creates a list of PropertyForms according to type_id */
    public static function getPropertiesFromItem($item_id) {
        
        $item = Item::model()->findByPk($item_id);
        
        $properties = PropertyForm::createPropertiesByType($item->type_id);
        
        foreach ($properties as $property) {
            $item_property = Property::model()->findByPk(array('item_id' => $item_id, 'name' => $property->name));
            
            if (!is_null($item_property)) {
                switch ($property->property_template->value_type) {
                    case Property::ValueTypeText: $property->value = $item_property->value_text; break;
                    case Property::ValueTypeDate: $property->value =  date('Y-m-d', strtotime($item_property->value_date)); break;
                    case Property::ValueTypeInt: $property->value = $item_property->value_int; break;
                    case Property::ValueTypeDouble: $property->value = $item_property->value_double; break;
                }
            }
        }
        
        return $properties;
    }
    
    
    /* Saves this property in the database */
    public function saveProperty($item) {
        
        if ($this->value != '') {
            return Property::add($item->id, $this->name, $this->property_template->value_type, $this->value);
        }
        
    }
    
}
?>
