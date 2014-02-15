<?php
/* @var $this SiteController */
/* @var $model Type */
/* @var $form CActiveForm  */

?>

<!-- Display a message for the user when the edit or delete item action succees -->
<?php if (Yii::app()->user->hasFlash('types')): ?>
    <div class="alert alert-info">
        <?php echo Yii::app()->user->getFlash('types'); ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>

<?php endif; ?>



<?php $form=$this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post'
)); ?>





<div class="row">
    <div class="span6">
        <!-- Buttons: Add, edit, delete -->
        <?php echo CHtml::link('Add type', array('addtype'), array('class' => 'btn')); ?>
        
        <?php echo CHtml::submitButton('Delete selected', array('class' => 'btn', 'name' => 'DeleteButton', 
        'confirm' => 'Are you sure you want to permanently delete these types? All the items of these types will be lost as well.')); ?>
        
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
                   'value' => '$data->name'
               )
            ),
        ));
        ?>
        
        

        <?php $this->endWidget(); ?>
    </div>
</div>

