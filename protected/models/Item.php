<?php

class Item extends CActiveRecord {

    
    public static function add($name, $type_id) {
        $item = new Item;
        $item->name = $name;
        $item->type_id = $type_id;
        $item->save();
        
        return $item;
    }
    
    
    public static function getAll() {
        $items = Item::model()->findAll();
        return $items;
    }
    
    public static function getByName($name) {
        $items = Item::model()->findAllByAttributes(array('name'=>$name));
        return $items;
    }
    
    public static function getByType($type_id) {
        $items = Item::model()->findAllByAttributes(array('type_id'=>$type_id));
        return $items;
    }
    
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations() {
        return array(            
            'item_dependencies_on'=>array(self::HAS_MANY, 'dependencies', 'item_id', 'joinType'=>'INNER JOIN'),
            'item_dependencies_to'=>array(self::HAS_MANY, 'dependencies', 'depends_on', 'joinType'=>'INNER JOIN'),
            
            'type'=>array(self::BELONGS_TO, 'type', 'type_id'),
            'properties'=>array(self::HAS_MANY, 'property', 'item_id'),
            'item_depends_on'=>array(self::HAS_MANY, 'item', array('depends_on'=>'id'), 
                'through'=>'item_dependencies_on', 'joinType'=>'INNER JOIN'),
            'item_dependence_to'=>array(self::HAS_MANY, 'item', array('item_id'=>'id'), 
                'through'=>'item_dependencies_to', 'joinType'=>'INNER JOIN')
            );
    }
    
    public static function remove($id) {
        $item = Item::model()->find('id=:id', array(':id'=>$id));
        $item->delete();
    }

    /*get table name*/
    public function tableName() {
        return 'item';
    }
    
    
    public function updateName($name) {
        $this->name = $name;
        $this->save();
    }
    
    public function updateType($type_id) {
        $this->type_id = $type_id;
        $this->save();
    }

}

?>
