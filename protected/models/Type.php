<?php

class Type extends CActiveRecord {

    public static function add($type_name) {
        $type = new Type;
        $type->type_name = $type_name;
        $type->save();
        
        return $type;
    }
    
    
    public static function getAll() {
        $types = Type::model()->findAll();
        return $types;
    }
    
    public static function getByName($type_name) {
        $types = Type::model()->findAllByAttributes(array('type_name'=>$type_name));
        return $types;
    }
    
    
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function relations() {
        return array(
          'items'=>array(self::HAS_MANY, 'item', 'type_id')
        );
    }

    public static function remove($id) {
        $type = Type::model()->find('id=:id', array(':id'=>$id));
        $type->delete();
    }
    
    
    
    public function tableName() {
        return 'type';
    }
    
    
    public function updateName($name) {
        $this->name = $name;
        $this->save();
    }

}

?>