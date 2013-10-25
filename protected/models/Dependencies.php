<?php


class Dependencies extends CActiveRecord {
    
     public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /*
    public function relations() {
        return array(
            'item_a'=>array(self::BELONGS_TO, 'item', 'item_id'),
            'item_b'=>array(self::BELONGS_TO, 'item', 'depends_on')
        );
    }
   */
    
    public function tableName() {
        return 'dependencies';
    }
}

?>
