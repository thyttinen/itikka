<?php
/* @var $this SiteController */
/* @var $model RelationshipForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Add Relationship';
$this->breadcrumbs = array(
    'Items' => array('items'),
    'Add Relationship',
);

?>
<div class="container">

    <!-- Adding relationships -->
    <div class="row">
        <div class="span12">
            <h2>Add relationships to: <?php echo Yii::app()->session['editing_item_name']; ?></h2>
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
            
    <!--
            
            <select id="inputType">
                <option>Kaikki</option>
                <option>Käyttöjärjestelmä</option>
                <option>Palvelin</option>
                <option>Sovellus</option>
                <option>Työasema</option>
                <option>Tulostin</option>
            </select>
    -->
        </div>
        <div class="span6">
            
            
            
            
            <form class="navbar-search pull-right">
                <input type="text" class="search-query" placeholder="Search">
            </form>
            
            
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
                    
                    
                    
                    <?php 
                    
                    
                    foreach ($relationships as $i => $relationship) {

                        echo '<tr>';
                        
                        echo '<td>';
                        echo $form->checkBox($relationship, "[$i]depends_on");
                        echo '</td>';

                        echo '<td>';
                        echo $form->checkBox($relationship, "[$i]dependency_to");
                        echo '</td>';
                        
                        $item = Item::model()->findByPk($relationship->item_id);
                        $type_name = $item->type->name;
                        
                        echo "<td> $item->id </td>";
                        echo "<td> $item->name </td>";
                        echo "<td> $type_name </td>";
                        
                        
                        echo '</tr>';
                    }
                    
                    ?>
                    
                    
                    
                    
                    
                    
                    
                    
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