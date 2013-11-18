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
                <a href="#" class="btn">Edit</a> 

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
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dependency->search(),
                    'itemsCssClass' => 'table table-striped',
                    'cssFile' => false,
                    'summaryText' => '',
                    'columns' => array(
                        array(
                            'name' => 'Relationship',
                            'value' => function($data, $row) {
                        return 'Depends on';
                    }
                        ),
                        array(
                            'name' => 'Id',
                            'value' => '$data->dependence->id'
                        ),
                        array(
                            'name' => 'Name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->dependence->name,array("viewitem","item_id"=>$data->dependence->id))'
                        ),
                        array(
                            'name' => 'Type name',
                            'value' => '$data->dependence->type->name'
                        )
                    ),
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="history">
        <p>Here goes the history.</p>
    </div>
    <div class="tab-pane" id="graphics">
        <p>Here goes the graphics.</p>
    </div>
</div>