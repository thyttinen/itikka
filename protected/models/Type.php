<?php
/* Columns for table 'type'
 * @property integer id
 * @property string name
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
class Type extends CActiveRecord {

    
    /* Saves a record with these attributes into the database */
    public static function add($name) {
        $type = new Type;
        $type->name = $name;
        $type->save();
        
        return $type;
    }
    
    
    public static function getAll() {
        $types = Type::model()->findAll(array('order' => 'name'));
        return $types;
    }
    
    public static function getByName($name) {
        $types = Type::model()->findAllByAttributes(array('name'=>$name));
        return $types;
    }
    
    
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /* Defines relationships between database tables, so one can for example 
     * access an item's type's name directly by using $item->type->name
     */
    public function relations() {
        return array(
          'items'=>array(self::HAS_MANY, 'Item', 'type_id')
        );
    }

    /* Removes the record with these attributes from the database */
    public static function remove($id) {
        $type = Type::model()->find('id=:id', array(':id'=>$id));
        $type->delete();
    }
    
    
    
    public function tableName() {
        return 'itikka.type';
    }
    
    
    public function updateName($name) {
        $this->name = $name;
        $this->save();
    }

}

?>