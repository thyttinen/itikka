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




<div class="row">
    <div class="span6">
        <!-- Dropdown menu for different item types -->
        <select id="inputType">
            <option>All</option>
        </select>
    </div>
</div>




<?php $form=$this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post'
)); ?>

<div class="row">
    <div class="span6">
        <!-- Buttons: Add, edit, delete -->
        <?php echo CHtml::link('Add item', array('additem'), array('class' => 'btn')); ?>
        <button disabled="disabled" class="btn" type="button">Edit selected</button>
        
            
            <?php echo CHtml::submitButton('Delete selected', array('class' => 'btn', 'name' => 'DeleteButton', 
            'confirm' => 'Are you sure you want to permanently delete these items?')); ?>
        
    </div>
    <!-- Searchbar -->
    <div class="span6">
        <form class="navbar-search pull-right">
            <input type="text" class="search-query" placeholder="Search">
        </form>
    </div>
    
<!-- List of items in the CMDB -->
    <div class="span12">
        
        
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $model->search(),
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

        