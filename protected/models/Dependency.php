<?php


class Dependency extends CActiveRecord {
    
    public static function add($item_id, $depends_on) {
        $dependency = new Dependency;
        $dependency->item_id = $item_id;
        $dependency->depends_on = $depends_on;
        $dependency->save();
        
        return $dependency;
    }
    
    public static function getAll() {
        $dependencies = Dependency::model()->findAll();
        return $dependencies;
    }
    
    public static function getByDependenceItem($depends_on) {
        $dependencies = Dependency::model()->findAllByAttributes(array('depends_on'=>$depends_on));
        return $dependencies;
    }
    
    public static function getByDependentItem($item_id) {
        $dependencies = Dependency::model()->findAllByAttributes(array('item_id'=>$item_id));
        return $dependencies;
    }
    
     public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations() {
        return array(
            'item'=>array(self::BELONGS_TO, 'item', 'item_id'),
            'dependence'=>array(self::BELONGS_TO, 'item', 'depends_on')
        );
    }
   
    public static function remove($item_id, $depends_on) {
        $dependency = Dependency::model()->find('item_id=:item_id AND depends_on=:depends_on', 
                array(':item_id'=>$item_id, ':depends_on'=>$depends_on));
        $dependency->delete();
    }
    
    public function tableName() {
        return 'dependencies';
    }
}

?>
