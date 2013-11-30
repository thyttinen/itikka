<?php
/* @var $this SiteController */
/* @var $model ItemForm */
/* @var $properties PropertyForm */
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
        
        
        <?php $type_id = $model->type_id; // ItemForm saves the type_id upon construction ?>
        
        <h2>Add item</h2>
        <div class="control-group">
            <?php echo $form->labelEx($model, 'type', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, 'type', $model->getAvailableTypes(), array('options' => array($type_id => array('selected'=>'selected'))) ); ?>
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
        
        <!-- Properties  -->
        
        <?php 
        /* $properties come from SiteController
         * the properties are collected through tabular input aka batch mode
         * 
         */
        
        
        foreach ($properties as $i=>$property) {
            echo '<div class="control-group">';

            echo $form->labelEx($property, "[$i]value", array('class' => 'control-label'));
            echo '<div class="controls">';
            
            
            
            // Input for a property that lists existing values of its type
            // Uses the combobox extension
            if ($property->property_template->list_existing_values) {
                
                
                // Get the existing values as an array
                $existing_values = array();
                $existing_properties = Property::getByName($property->name);
                
                foreach ($existing_properties as $ex_prop) {
                    if ($ex_prop->item->type_id == $type_id) {
                        switch ($property->property_template->value_type) {
                            case Property::ValueTypeText: $existing_values[] = $ex_prop->value_text; break;
                            case Property::ValueTypeDate: $existing_values[] = $ex_prop->value_date; break;
                            case Property::ValueTypeInt: $existing_values[] = $ex_prop->value_int; break;
                            case Property::ValueTypeDouble: $existing_values[] = $ex_prop->value_double; break;
                        }
                    }
                }
                
                // Combobox widget
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'model' => $property, 'attribute' => "[$i]value",
                    'data' => $existing_values,
                    'options' => array('allowText' => true), // Allows editing the text
                    'htmlOptions' => array('size' => 20) // Limits array size
                ));
            }
            
            
            // Input for other types
            else {
                switch ($property->property_template->value_type) {
                    case Property::ValueTypeText: echo $form->textField($property, "[$i]value"); break;
                    case Property::ValueTypeDate: echo $form->dateField($property, "[$i]value"); break;
                    case Property::ValueTypeInt: echo $form->textField($property, "[$i]value"); break;
                    case Property::ValueTypeDouble: echo $form->textField($property, "[$i]value"); break;
                }
            }
            
            
            echo $form->error($property, "[$i]value"); 

            echo '</div></div>';
        }
        
        ?>
        
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


<script type="text/javascript">
    //this is jQuery's equivalent to document.ready -function
    //it's called when the page is loaded
    $(function() {
        
        //Store initial value from type select
        //when the page loads.
        
        //Select the type select element using jQuery.
        //Type select is assumed to have id "ItemForm_type".
        //Change it to match the actual id if necessary
        var typeSelect = $("#ItemForm_type");
        
        //Get the currently selected value
        var originalValue = typeSelect.val();
       
        //Register a change listener to the element.
        //The function given as parameter will be called when selection changes
        $("#ItemForm_type").change(function(e) {
            //run the function only if user approves
            if(confirm("Change Item's Type ? All unsaved changes will be lost")) {
                //"template" for the url to load, generated by Yii
                var urlTemplate = "<?php echo $this->createUrl("addItem", array("type" => "_value_")) ?>"
                //Replace the "_value_" -placeholder with the currently selected id
                //"this" refers to the element whose event was fired (= our select)
                var url = urlTemplate.replace("_value_", $(this).val());
                
                //Go to the url
                window.location.href = url;
            }else {
               //.. or revert to the original value
               typeSelect.val(originalValue);
            }
        });
       
    });
</script>