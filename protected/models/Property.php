<?php


class Property extends CActiveRecord{
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations() {
        return array(
          'item'=>array(self::BELONGS_TO, 'item', 'item_id')  
            
        );
    }
    
    public function tableName() {
        return 'property';
    }
}

?>
