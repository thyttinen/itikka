<?php

class Item extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*get table name*/
    public function tableName() {
        return 'item';
    }

}

?>
