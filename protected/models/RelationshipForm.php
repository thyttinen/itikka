<?php

/**
 * Description of RelationshipForm
 * Used to add new relationships to an item for ItemForm
 * Can't and doesn't save the relationships to the database!
 * 
 */
class RelationshipForm extends CFormModel {
    
    public $depends_on;
    public $dependency_to;
    public $item_id;
    
    
    /* Rules for form input 
     */
    public function rules() {
        return array(array('depends_on, dependency_to', 'boolean'));
    }
    
    
    /* Gets the relationships from the session variables */
    public static function getRelationships() {
    
        $relationships = array();
        
        for ($ri = 0; $ri < Yii::app()->session['editing_relationship_count']; $ri = $ri + 1) {
            $temp = new RelationshipForm;
            $temp->item_id = Yii::app()->session['editing_relationship_' . $ri . 'item_id'];
            $temp->depends_on = Yii::app()->session['editing_relationship_' . $ri . 'depends_on'];
            $temp->dependency_to = Yii::app()->session['editing_relationship_' . $ri . 'dependency_to'];


            $relationships[] = $temp;
        }
        
        
        return $relationships;
    }
    
    
    /* Saves this relationship in the database */
    public function saveRelationship($item) {
        
        // Depends on
        if ($this->depends_on == 1) {
            Dependency::add($item->id, $this->item_id);
        }
        
        // Dependency to
        if ($this->dependency_to == 1) {
            Dependency::add($this->item_id,  $item->id);
        }
    }
    
}

?>
