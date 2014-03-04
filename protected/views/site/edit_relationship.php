<?php
/* @var $this SiteController */
/* @var $model RelationshipForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Edit Relationships';
$this->breadcrumbs = array(
    'Items' => array('items'),
    'Edit Relationships',
);

?>
<div class="container">

    <!-- Adding relationships -->
    <div class="row">
        <div class="span12">
            <h2>Edit relationships of: <?php echo Yii::app()->session['editing_item_name']; ?></h2>
            <br/>
            <h3>Item <?php echo Yii::app()->session['editing_item_name']; ?>'s relationships: </h3>
        </div>
    </div>
    
    
    <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'relationship-form',
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
                'htmlOptions' => array('class' => 'form-horizontal')
            ));
            ?>
    
    
             
    
    <!-- Dropdown menu for different item types -->
    <div class="row">
        <div class="span6">
            
    
 
            <!-- javascript for changing the visibility of items by their type -->
            <select id="inputType">
                
                <option>All types</option>
                
                <?php
                $types = Type::getAll();
                foreach ($types as $type):
                ?>
                <option><?php echo $type->name; ?></option>
                <?php endforeach; ?>
                
            </select>
    
        </div>
        
         
        
        
 
    <!-- List of items in the CMDB -->
    
        
        <div class="span12">
          
            
                <table class="table table-striped">


                    <thead>
                        <tr>
                            <th>Depends on</th>
                            <th>Dependency to</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Type</th>
                        </tr>
                    </thead>


                    <tbody>
                        
                        <?php foreach ($types as $type): ?>
            

                            <?php foreach ($relationships as $i => $relationship): 

                            $relationship_item = Item::model()->findByPk($relationship->item_id);
                            if ($relationship_item->type_id == $type->id):
                            ?>

                            <tr class="<?php echo str_replace(' ', '_', $type->name); ?>">
                                <td>
                                <?php echo $form->checkBox($relationship, "[$i]depends_on"); ?>
                                </td>
                                <td>
                                <?php echo $form->checkBox($relationship, "[$i]dependency_to"); ?>
                                </td>
                                <?php
                                $item = Item::model()->findByPk($relationship->item_id);
                                $type_name = $item->type->name;
                                ?>
                                <td> <?php echo $item->id; ?> </td>
                                <td> <?php echo $item->name; ?> </td>
                                <td> <?php echo $type_name; ?> </td>
                           </tr>

                           <?php endif; ?>
                           <?php endforeach; ?>

                       <?php endforeach; ?>

                    </tbody>


                </table>
            
            
            <br/>
            <div class="controls">
                <div class="controls">
                    <?php echo CHtml::submitButton('Return to item', array('class' => 'btn')); ?>
                </div>
            </div>
            
    

        </div>
        
    </div>
    
    
    <?php $this->endWidget(); ?>
</div>



<script type="text/javascript">
    //this is jQuery's equivalent to document.ready -function
    //it's called when the page is loaded
    $(function() {
    
    // Sets the default visible relationship div
    var currentViewedType = "." + $("#inputType").val();
    currentViewedType = currentViewedType.replace(/ /g, '_');
    $(currentViewedType).css({"display":"table-row"});
    
    //Changes the visibility of the form <div>:s so those selected from the type list are shown
    $("#inputType").change(function(e) {
        
        // Hide all items
        $("tr").each(function(index, element) {
           $(this).css({"display":"none"}); 
        });
        
        // Change to listing all types
        if ($(this).val() === "All types") {
            $("tr").each(function(index, element) {
               $(this).css({"display":"table-row"}); 
            });
        }
        
        // Change to listing items of a single type
        else {
            currentViewedType = "." + $(this).val();
            currentViewedType = currentViewedType.replace(/ /g, '_');
            $(currentViewedType).each(function(index, element) {
                        $(this).css({"display":"table-row"});
            });
        } 
            
            
         
    });
    
    
    });
    
</script>