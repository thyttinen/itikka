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
        <!-- Buttons: Add, edit, delete -->
        <?php echo CHtml::link('Add item', array('additem'), array('class' => 'btn')); ?>
        <button disabled="disabled" class="btn" type="button">Edit selected</button>
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
                   'type'=>'raw',
                   'value' => 'CHtml::link($data->name,array("viewitem","item_id"=>$data->id))'
               ),
               array(
                   'name' => 'type name',
                   'value' => '$data->type->name'
               )
            ),
        ));
        ?>
    </div>
</div>
