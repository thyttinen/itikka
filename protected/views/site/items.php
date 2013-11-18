<div class="row">
    <div class="span6">
        <!-- Dropdown menu for different item types -->
        <select id="inputType">
            <option>All</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="span6">
        <!-- Buttons: Add, delete -->
        <?php echo CHtml::link('Add item ..', array('additem'), array('class' => 'btn')); ?>
        <button disabled="disabled" class="btn" type="button">Delete selected</button>
    </div>
    <!-- Searchbar -->
    <div class="span6">
        <form class="navbar-search pull-right">
            <input type="text" class="search-query" placeholder="Search">
        </form>
    </div>
</div>

<!-- List of items in the CMDB -->
<div class="row">
    <div class="span12">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $model->search(),
            'itemsCssClass' => 'table table-striped',
            'cssFile' => false,
            'columns' => array(
               array(
                   'name' => ' ',
                   'type'=>'raw',
                   'value' => 'CHtml::checkBox("checked",null,array("value"=>$data->id))'
               ),
               array(
                   'name' => 'id',
                   'value' => '$data->id'
               ),
               array(
                   'name' => 'name',
                   'value' => '$data->name'
               ),
                array(
                   'name' => 'type',
                   'value' => '$data->type->name'
               )
            ),
        ));
        ?>
    </div>
</div>

<hr/>

<!-- Tabs: info, relationships, graphics, history -->
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#info" data-toggle="tab">Info</a></li>
        <li><a href="#relationships" data-toggle="tab">Relationships</a></li>
        <li><a href="#graphics" data-toggle="tab">Graphics</a></li>
        <li><a href="#history" data-toggle="tab">History</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="info">
            <p>Here goes the info.</p>
        </div>
        <div class="tab-pane" id="relationships">
            <p>Here goes the relationships.</p>
        </div>
        <div class="tab-pane" id="graphics">
            <p>Here goes the graphics.</p>
        </div>
        <div class="tab-pane" id="history">
            <p>Here goes the history.</p>
        </div>
    </div>
</div>