<?php
/* @var $this SiteController */
/* @var $model ItemForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Add Item';
$this->breadcrumbs = array(
    'Items' => array('items'),
    'Add Item',
);
?>

<!-- Display a message for the user when the add item function succeeds -->
<?php if (Yii::app()->user->hasFlash('add_item')): ?>
    <div class="alert alert-info">
        <?php echo Yii::app()->user->getFlash('add_item'); ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>

<?php endif; ?>

<div class="row">
    <div class="span6">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'item-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array('class' => 'form-horizontal')
        ));
        ?>
        <h2>Add item</h2>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'type', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'type', $model->getAvailableTypes()); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
        </div>
        <hr/>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'name'); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <?php echo CHtml::submitButton('Add', array('class' => 'btn')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>

    <!-- Relationships -->
    <div class="span6">
        <h2>Relationships</h2>
        <div class="row">
            <div class="span6">
                <button class="btn"disabled="disabled">Add relationship</button>
                <form class="navbar-search pull-right">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
            </div>
        </div>  

        <!-- List of current relationships -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Relationship</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>