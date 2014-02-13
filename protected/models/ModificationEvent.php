<?php
/* Columns for table 'modification_event'
 * @property integer item_id
 * @property boolean created
 * @property string description 
 * @property date modification_date
 * 
 * 
 * Method descriptions for all records:
 * 
 * (static)
 * add: creates and saves a record with the specified attributes into the database
 * getAll: returns all records in the database of this type
 */

class ModificationEvent extends CActiveRecord{
    
    /* 
     * Saves a record with these attributes into the database 
     */
    public static function add($item_id, $description, $created) {
        $event = new ModificationEvent;
        
        $event->item_id = $item_id;
        $event->created = $created;
        $event->description = $description;
        $event->modification_date = new CDbExpression('NOW()');
        
        $event->save();

        return $event;
    }
    
    public static function getAll() {
        $events = ModificationEvent::model()->findAll();
        return $events;
    }
    
    public static function getByItem($item_id) {
        $events = ModificationEvent::model()->findAllByAttributes(
                array('item_id'=>$item_id), 
                array('order' => 'modification_date desc'));
        return $events;
    }
    
    public static function getLatest($limit, $created) {
        $events = ModificationEvent::model()->findAll(array(
            'condition' => 'created=:b',
            'order' => 'modification_date desc',
            'limit' => $limit,
            'params' => array(':b' => $created),
        ));
        return $events;
    }
    
    public static function removeAllBy($item_id) {
        ModificationEvent::model()->deleteAll('item_id=:item_id', 
                array(':item_id'=>$item_id));
    }
    
    public function relations() {
        return array(
          'item'=>array(self::BELONGS_TO, 'Item', 'item_id')           
        );
    }
    
    public function tableName() {
        return 'itikka.modification_event';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
}

?>
