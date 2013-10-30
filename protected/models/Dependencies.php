<?php


class Dependencies extends CActiveRecord {
    
     public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations() {
        return array(
            'item'=>array(self::BELONGS_TO, 'item', 'item_id'),
            'dependence'=>array(self::BELONGS_TO, 'item', 'depends_on')
        );
    }
   
    
    public function tableName() {
        return 'dependencies';
    }
}

?>
