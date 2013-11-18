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

class Property extends CActiveRecord{
    
    /* 
     * Saves a record with these attributes into the database 
     * $value_type can be 'text', 'date', 'int' or 'double'
     * 
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
    
    
    /* $value_type can be 'text', 'date', 'int' or 'double'
     * 
     */
    public function updateValue($value_type, $value) {
        $this->textvalue = null;
        $this->datevalue = null;
        $this->intvalue = null;
        $this->doublevalue = null;
        
        $correct = true;
        switch ($value_type) {
            case 'text': $this->textvalue = $value; break;
            case 'date': $this->datevalue = $value; break;
            case 'int': $this->intvalue = $value; break;
            case 'double': $this->doublevalue = $value; break;
            default: $correct = false;
        }
        
        if ($correct) {
            $this->save();
        }
        
        return $correct;
    }
}

?>
