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

class Item extends CActiveRecord {
    /* Saves a record with these attributes into the database */
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
        $items = Item::model()->findAllByAttributes(array('name' => $name));
        return $items;
    }

    public static function getByType($type_id) {
        $items = Item::model()->findAllByAttributes(array('type_id' => $type_id));
        return $items;
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /* Defines relationships between database tables, so one can for example 
     * access an item's type's name directly by using $item->type->name
     */
    public function relations() {
        return array(
            'item_dependencies_on' => array(self::HAS_MANY, 'Dependency', 'item_id', 'joinType' => 'INNER JOIN'),
            'item_dependencies_to' => array(self::HAS_MANY, 'Dependency', 'depends_on', 'joinType' => 'INNER JOIN'),
            'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
            'properties' => array(self::HAS_MANY, 'Property', 'item_id'),
            'item_depends_on' => array(self::HAS_MANY, 'Item', array('depends_on' => 'id'),
                'through' => 'item_dependencies_on', 'joinType' => 'INNER JOIN'),
            'item_dependence_to' => array(self::HAS_MANY, 'Item', array('item_id' => 'id'),
                'through' => 'item_dependencies_to', 'joinType' => 'INNER JOIN')
        );
    }

    /* Removes the record with these attributes from the database */
    public static function remove($id) {
        $item = Item::model()->find('id=:id', array(':id' => $id));
        $item->delete();
    }

    /*
     * Return data provider to be used by the table in Item view (items.php). 
     * No criteria used for filtering yet.
     */
    public function search() {
        $criteria = new CDbCriteria;
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ),
            'pagination' => array(
                'pageSize' => 5
            ),
        ));
    }

    public function tableName() {
        return 'itikka.item';
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
