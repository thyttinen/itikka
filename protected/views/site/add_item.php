<?php
/* @var $this SiteController */
/* @var $model ItemForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Add Item';
$this->breadcrumbs=array(
	'Add Item',
);
?>


<h1> Add Item </h1>

<!-- Display a message for the user when the add item function succeeds -->
<?php if(Yii::app()->user->hasFlash('add_item')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('add_item'); ?>
</div>

<?php endif; ?>


<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
    
    

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList($model,'type', $model->getAvailableTypes()); ?>
        <?php echo $form->error($model,'type'); ?>
        
    </div>
    
    <div class="row buttons">
	<?php echo CHtml::submitButton('Add'); ?>
	
    </div>

    
        
    
    
<?php $this->endWidget(); ?>
  
</div>




