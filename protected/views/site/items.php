<?php
/* @var $this SiteController */
/* @var $model Item */
/* @var $form CActiveForm  */

?>

<!-- Display a message for the user when the edit or delete item action succees -->
<?php if (Yii::app()->user->hasFlash('items')): ?>
    <div class="alert alert-info">
        <?php echo Yii::app()->user->getFlash('items'); ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>

<?php endif; ?>



<?php $form=$this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post'
)); ?>


<?php 
// Get a list of types and the current type for type selection
$types = Type::getAll();
$type_id = $types[0]->id;
if (isset($_GET['type_id'])) {
    $type_id = $_GET['type_id']; 
} 

$data = array();
foreach ($types as $type) {
    $data[$type->id] = $type->name;
}
?>

<div class="control-group">
    <?php echo $form->labelEx($model, 'type', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model, 'type', $data, array('options' => array($type_id => array('selected'=>'selected'), 'disabled' => 'disabled')) ); ?>
        <?php echo $form->error($model, 'type');  ?>
    </div>
</div>



<div class="row">
    <div class="span6">
        <!-- Buttons: Add, edit, delete -->
        <?php echo CHtml::link('Add item', array('additem&blank=1'), array('class' => 'btn')); ?>
        <button disabled="disabled" class="btn" type="button">Edit selected</button>
        
            
            <?php echo CHtml::submitButton('Delete selected', array('class' => 'btn', 'name' => 'DeleteButton', 
            'confirm' => 'Are you sure you want to permanently delete these items?')); ?>
        
    </div>
    
    
<!-- List of items in the CMDB -->
    <div class="span12">
        
        
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $model->search($type_id),
            'itemsCssClass' => 'table table-striped',
            'id' => 'item-grid',
            'ajaxUpdate' => false,
            'cssFile' => false,
            
            'columns' => array(
                
               array(
                   'name' => ' ',
                   'id' => 'selected',
                   'class' => 'CCheckBoxColumn',
                   'value' => '$data->id',
                   'selectableRows' => 2
               ),
               array(
                   'name' => 'id',
                   'value' => '$data->id'
               ),
               array(
                   'name' => 'name',
                   'type'=>'raw',
                   'value' => 'CHtml::link($data->name,array("viewitem","item_id"=>$data->id))'
               ),
               array(
                   'name' => 'type name',
                   'value' => '$data->type->name'
               )
            ),
        ));
        ?>
        
        

        <?php $this->endWidget(); ?>
    </div>
</div>


<script type="text/javascript">
    //this is jQuery's equivalent to document.ready -function
    //it's called when the page is loaded
    $(function() {
        
        //Store initial value from type select
        //when the page loads.
        
        //Select the type select element using jQuery.
        //Change it to match the actual id if necessary
        var typeSelect = $("#Item_type");
        
        //Get the currently selected value
        var originalValue = typeSelect.val();
       
        //Register a change listener to the element.
        //The function given as parameter will be called when selection changes
        $("#Item_type").change(function(e) {
            //"template" for the url to load, generated by Yii
            var urlTemplate = "<?php echo $this->createUrl("items", array("type_id" => "_value_")) ?>"
            //Replace the "_value_" -placeholder with the currently selected id
            //"this" refers to the element whose event was fired (= our select)
            var url = urlTemplate.replace("_value_", $(this).val());

            //Go to the url
            window.location.href = url;
            
        });
       
    });
</script>