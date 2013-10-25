<?php

class Item extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations() {
        return array(            
            'type'=>array(self::BELONGS_TO, 'type', 'type_id'),
            'properties'=>array(self::HAS_MANY, 'property', 'item_id')//,
            //'dependent_on'=>array(self::HAS_MANY, 'dependencies', 'id')
            );
    }
    

    /*get table name*/
    public function tableName() {
        return 'item';
    }

}

?>
