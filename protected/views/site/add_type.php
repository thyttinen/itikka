<?php


$this->pageTitle = Yii::app()->name . ' - Add Type';
    $this->breadcrumbs = array(
        'Types' => array('types'),
        'Add Type',
    );
?>


<!-- Display a message for the user when the add type function succeeds -->
<?php if (Yii::app()->user->hasFlash('add_type')): ?>
    <div class="alert alert-info">
        <?php echo Yii::app()->user->getFlash('add_type'); ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>

<?php endif; ?>

<div class="row">
    <div class="span12">
        <h2>Add type</h2>
        <br/>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'type-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            )
        ));
        ?>
        <div class="form-inline">
            <?php echo $form->labelEx($type, 'name', array('class' => 'control-label')); ?>
            <?php echo $form->textField($type, 'name'); ?>
            <?php echo $form->error($type, 'name');  ?>
        </div>

        <hr>

        <h3>Properties:</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name:</th>
                    <th>Type:</th>
                    <th>Required:</th>
                    <th>List existing values:</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propertytemplates as $i=>$property): ?>
                    <tr>
                        <td>
                            <?php echo $form->textField($property, "[$i]name"); ?>
                            <?php echo $form->error($property, "[$i]name");  ?>
                        </td>
                        <td>
                            <?php echo $form->dropDownList($property, "[$i]value_type", $property->getPropertyTypes()); ?>
                            <?php echo $form->error($property, "[$i]value_type");  ?>
                        </td>
                        <td>
                            <?php echo $form->checkBox($property, "[$i]value_required"); ?>
                        </td>
                        <td>
                            <?php echo $form->checkBox($property, "[$i]list_existing_values"); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <input class="btn" type="submit" name="add-row" value="+"></input>

        <br/>
        <br/>
        <br/>

        <?php echo CHtml::submitButton('Add type', array('class' => 'btn')); ?>
        
        <?php $this->endWidget(); ?>

    </div>
</div>