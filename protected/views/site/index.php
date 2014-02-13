<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="hero-unit">
    <h1 class="text-center"><?php echo Yii::app()->name ?></h1>
</div>

<div class="row">
    <div class="span12">
        <h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
    </div>
</div>

<div class="row">
    <div class="span6">
        <h3>Latest items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Creation time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($latestCreated as $event): ?>
                <tr>
                    <td><?php echo CHtml::link($event->item->name,array("viewitem","item_id"=>$event->item->id));?></td>
                    <td><?php echo $event->item->type->name;?></td>
                    <td><?php echo date("Y-d-m H:i",strtotime($event->modification_date));?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span6">
        <h3>Latest edited</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Comment</th>
                    <th>Modification time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($latestEdited as $event): ?>
                <tr>
                    <td>
                        <?php echo CHtml::link($event->item->name,array("viewitem","item_id"=>$event->item->id));?>
                    </td>
                    <td><?php echo $event->item->type->name?></td>
                    <td><?php echo $event->description?></td>
                    <td><?php echo date("Y-d-m H:i",strtotime($event->modification_date));?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div

