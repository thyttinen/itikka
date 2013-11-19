<?php

/* Method descriptions for all records:
 * 
 * (static)
 * add: creates and saves a record with the specified attributes into the database
 * getAll: returns all records in the database of this type
 * getByName: returns all records from the database by name, type, etc
 * remove: removes a record from the database that has the specified key
 * (not static)
 * updateName: updates this record's name, type, etc and saves it
 */

class PropertyTemplate extends CActiveRecord{
    
    /* 
     * Saves a record with these attributes into the database 
     * $value_type can be on of the property consts 
     * ValueTypeText, ValueTypeDate, ValueTypeInt or ValueTypeDouble
     * Property::ValueTypeText for example
     */
    public static function add($type_id, $name, $value_type, $value_required, $list_existing_values) {
        $propertyTemplate = new PropertyTemplate;
        
        $propertyTemplate->type_id = $type_id;
        $propertyTemplate->name = $name;
        $propertyTemplate->value_type = $value_type;
        $propertyTemplate->value_required = $value_required;
        $propertyTemplate->list_existing_values = $list_existing_values;
        
        
        $propertyTemplate->save();
        
        
    }
    
    public static function getAll() {
        $properties = PropertyTemplate::model()->findAll();
        return $properties;
    }
    
    public static function getByName($name) {
        $properties = PropertyTemplate::model()->findAllByAttributes(array('name'=>$name));
        return $properties;
    }
    
    public static function getByType($type_id) {
        $properties = PropertyTemplate::model()->findAllByAttributes(array('type_id'=>$type_id));
        return $properties;
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /* Defines relationships between database tables, so one can for example 
     * access an item's type's name directly by using $item->type->name
     */
    public function relations() {
        return array(
          'type'=>array(self::BELONGS_TO, 'type', 'type_id')  
            
        );
    }
    
    
    /* Removes the record with these attributes from the database */
    public static function remove($type_id, $name) {
        $propertyTemplate = PropertyTemplate::model()->find('type_id=:type_id AND name=:name', 
                array(':type_id'=>$type_id, ':name'=>$name));
        $propertyTemplate->delete();
    }
    
    public function tableName() {
        return 'itikka.property_template';
    }
    
    
}

?>
