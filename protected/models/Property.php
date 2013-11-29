<?php
/* Columns for table 'property'
 * @property integer item_id
 * @property string name 
 * @property string value_text
 * @property date value_date
 * @property integer value_int
 * @property real value_double
 * 
 * 
 * Method descriptions for all records:
 * 
 * (static)
 * add: creates and saves a record with the specified attributes into the database
 * getAll: returns all records in the database of this type
 * getByName: returns all records from the database by name, type, etc
 * remove: removes a record from the database that has the specified key
 * (not static)
 * updateName: updates this record's name, type, etc and saves it
 */

class Property extends CActiveRecord{
    
    /* Constants for value type */
    const ValueTypeText = 'text';
    const ValueTypeDate = 'date';
    const ValueTypeInt = 'int';
    const ValueTypeDouble = 'double';
    
    
    
    /* 
     * Saves a record with these attributes into the database 
     * $value_type can be on of the property consts 
     * ValueTypeText, ValueTypeDate, ValueTypeInt or ValueTypeDouble
     * Property::ValueTypeText for example
     */
    public static function add($item_id, $name, $value_type, $value) {
        $property = new Property;
        
        $property->item_id = $item_id;
        $property->name = $name;
        
        if ($property->updateValue($value_type, $value)) {
            return $property;
        }
    }
    
    public static function getAll() {
        $properties = Property::model()->findAll();
        return $properties;
    }
    
    
    public static function getByItem($item_id) {
        $properties = Property::model()->findAllByAttributes(array('item_id'=>$item_id));
        return $properties;
    }
    
    public static function getByName($name) {
        $properties = Property::model()->findAllByAttributes(array('name'=>$name));
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
          'item'=>array(self::BELONGS_TO, 'item', 'item_id')  
            
        );
    }
    
    
    /* Removes the record with these attributes from the database */
    public static function remove($item_id, $name) {
        $property = Property::model()->find('item_id=:item_id AND name=:name', 
                array(':item_id'=>$item_id, ':name'=>$name));
        $property->delete();
    }
    
    public function tableName() {
        return 'itikka.property';
    }
    
    
    /* $value_type can be on of the property consts 
     * ValueTypeText, ValueTypeDate, ValueTypeInt or ValueTypeDouble
     * Property::ValueTypeText for example
     */
    public function updateValue($value_type, $value) {
        $this->value_text = null;
        $this->value_date = null;
        $this->value_int = null;
        $this->value_double = null;
        
        
        $correct = true;
        switch ($value_type) {
            case Property::ValueTypeText: $this->value_text = $value; break;
            case Property::ValueTypeDate: $this->value_date = $value; break;
            case Property::ValueTypeInt: $this->value_int = $value; break;
            case Property::ValueTypeDouble: $this->value_double = $value; break;
            default: $correct = false;
        }
        
        
        if ($correct) {
            $this->save();
        }
        
        return $correct;
    }
}

?>
