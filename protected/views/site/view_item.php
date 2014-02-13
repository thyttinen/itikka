<?php
/* @var $this SiteController */
/* @var $model Item */

$this->pageTitle = Yii::app()->name . ' - View Item';
$this->breadcrumbs = array(
    'Items' => array('items'),
    'View Item',
);
?>

<ul id="tabit" class="nav nav-tabs">
    <li class="active"><a href="#info" data-toggle="tab">Info</a></li>
    <li><a href="#history" data-toggle="tab">History</a></li>
    <li><a href="#graphics" data-toggle="tab">Graphics</a></li>
</ul>

<div class="tab-content">

    <!-- Item info -->
    <div class="tab-pane active" id="info">                 
        <div class="row">
            <div class="span6">
                <h2><?php echo $model->name ?></h2>	
                <?php echo CHtml::link('Edit', array('edititem&item_id=' . $model->id . '&blank=1'), array('class' => 'btn')); ?>

                <form class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="inputType">Type:</label>
                        <div class="controls">
                            <select id="inputType" disabled>
                                <option><?php echo $model->type->name ?></option>
                            </select>
                        </div>
                    </div>		
                    <div class="control-group">
                        <label class="control-label" for="inputName">Name:</label>
                        <div class="controls">
                            <input type="text" id="inputName" value="<?php echo $model->name ?>" disabled>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Edited by:</label>
                        <div class="controls">
                            <input type="text" placeholder="" disabled>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Time:</label>
                        <div class="controls">
                            <input type="text" placeholder="7.11.2013 21:30:00" disabled>
                        </div>
                    </div>
                    
                    
                    <!-- Item Properties -->
                    <?php $properties = Property::getByItem($model->id); ?>
                    <?php foreach($properties as $property): ?>
                    
                    <div class="control-group">
                        <label class="control-label"><?php echo $property->name; ?></label>
                        <div class="controls">
                            
                                   
                            <?php 
                            echo '<input type="text" value="';

                            if (!is_null($property->value_text)) {
                                echo $property->value_text;

                            } else if (!is_null($property->value_date)) {
                                echo date('d.m.Y', strtotime($property->value_date));

                            } else if (!is_null($property->value_int)) {
                                echo $property->value_int;

                            } else {
                                echo $property->value_double;
                            }

                            echo '" disabled>';
                            ?>
                            
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    
                    
                </form>
            </div>

            <!-- Item's relationships -->
            <div class="span6">
                <h2>Relationships</h2>
                <div class="row">
                    <div class="span6">
                        <form class="navbar-search pull-right">
                            <input type="text" class="search-query" placeholder="Search">
                        </form>
                    </div>
                </div>  

                <!-- List of current relationships -->
                <?php
                $dependency = new Dependency();
                $dependency->item_id = $model->id;
                
                // The items this one depends on
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dependency->search('item_id'),
                    'itemsCssClass' => 'table table-striped',
                    'cssFile' => false,
                    'summaryText' => '',
                    'columns' => array(
                        array(
                            'name' => 'Depends on',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->dependence->name,array("viewitem","item_id"=>$data->dependence->id))'
                        ),
                        array(
                            'name' => 'Id',
                            'value' => '$data->dependence->id'
                        ),
                        
                        array(
                            'name' => 'Type name',
                            'value' => '$data->dependence->type->name'
                        )
                    ),
                ));
                
                // The items this one is a dependency to
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dependency->search('depends_on'),
                    'itemsCssClass' => 'table table-striped',
                    'cssFile' => false,
                    'summaryText' => '',
                    'columns' => array(
                        array(
                            'name' => 'Dependency to',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->item->name,array("viewitem","item_id"=>$data->item->id))'
                        ),
                        array(
                            'name' => 'Id',
                            'value' => '$data->item->id'
                        ),
                        
                        array(
                            'name' => 'Type name',
                            'value' => '$data->item->type->name'
                        )
                    ),
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="history">
        <div class="row">
            <div class="span6">
                 <h3>History for item <?=$model->name;?></h3>
                 <table class="table">
                     <thead>
                         <tr>
                             <th>Time</th>
                             <th>Action</th>
                             <th>Comment</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php foreach ($events as $event): ?>
                         <tr>
                             <td><?php echo date("Y-d-m H:i",strtotime($event->modification_date));?></td>
                             <td><?php echo $event->created ? "Created" : "Edited";?></td>
                             <td><?php echo $event->description;?></td>
                         </tr>
                         <?php endforeach; ?>
                     </tbody>
                 </table>
             </div>
        </div>
    </div>
    <div class="tab-pane" id="graphics">
        <p>Here goes the graphics.</p>
    </div>
</div>