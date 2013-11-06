<?php


class Property extends CActiveRecord{
    
    /* $value_type can be 'text', 'date', 'int' or 'double'
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
    
    
    public function relations() {
        return array(
          'item'=>array(self::BELONGS_TO, 'item', 'item_id')  
            
        );
    }
    
    
    public static function remove($item_id, $name) {
        $property = Property::model()->find('item_id=:item_id AND name=:name', 
                array(':item_id'=>$item_id, ':name'=>$name));
        $property->delete();
    }
    
    public function tableName() {
        return 'property';
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
