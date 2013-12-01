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
            <h2>Add relationship to: testipalvelin</h2>
            <br/>
            <h3>Item testipalvelin depends on: </h3>
        </div>
    </div>
    
    <pre>
    <?php //var_dump($_POST); ?>
    </pre>
    
    
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
            
            
            <select id="inputType">
                <option>Kaikki</option>
                <option>Käyttöjärjestelmä</option>
                <option>Palvelin</option>
                <option>Sovellus</option>
                <option>Työasema</option>
                <option>Tulostin</option>
            </select>
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
                        <th> </th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Type</th>
                    </tr>
                </thead>
                
                
                <tbody>
                    <tr>
                        <td>
                            <?php echo $form->checkBox($model, 'testbox'); ?>
                        </td>
                        <td>1</td>
                        <td>Palvelin1</td>
                        <td>Palvelin</td>
                    </tr>
                </tbody>
                
                
            </table>
            
            <br/>
            <div class="controls">
                <div class="controls">
                    <?php echo CHtml::submitButton('Add relationships', array('submit' => 'index.php?r=site/additem', 'class' => 'btn')); ?>
                </div>
            </div>
            
    

        </div>
        
    </div>
    
    <?php $this->endWidget(); ?>
</div>