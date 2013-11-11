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
class Dependency extends CActiveRecord {
    
    /* Saves a record with these attributes into the database */
    public static function add($item_id, $depends_on) {
        $dependency = new Dependency;
        $dependency->item_id = $item_id;
        $dependency->depends_on = $depends_on;
        $dependency->save();
        
        return $dependency;
    }
    
    
    public static function getAll() {
        $dependencies = Dependency::model()->findAll();
        return $dependencies;
    }
    
    /* Returns all items dependent on $depends_on */
    public static function getByDependenceItem($depends_on) {
        $dependencies = Dependency::model()->findAllByAttributes(array('depends_on'=>$depends_on));
        return $dependencies;
    }
    
    /* Returns all items that $item_id depends on */
    public static function getByDependentItem($item_id) {
        $dependencies = Dependency::model()->findAllByAttributes(array('item_id'=>$item_id));
        return $dependencies;
    }
    
     public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /* Defines relationships between database tables, so one can for example 
     * access an item's type's name directly by using $item->type->name
     */
    public function relations() {
        return array(
            'item'=>array(self::BELONGS_TO, 'item', 'item_id'),
            'dependence'=>array(self::BELONGS_TO, 'item', 'depends_on')
        );
    }
   
    /* Removes the record with these attributes from the database */
    public static function remove($item_id, $depends_on) {
        $dependency = Dependency::model()->find('item_id=:item_id AND depends_on=:depends_on', 
                array(':item_id'=>$item_id, ':depends_on'=>$depends_on));
        $dependency->delete();
    }
    
    public function tableName() {
        return 'dependencies';
    }
}

?>
