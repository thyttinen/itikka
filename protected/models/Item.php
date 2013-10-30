<?php

class Item extends CActiveRecord {

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
    

    /*get table name*/
    public function tableName() {
        return 'item';
    }

}

?>
