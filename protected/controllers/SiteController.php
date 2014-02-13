<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * Displays item list page
     */
    public function actionItems() {
        $model = new Item('search');
        
        
        // Perform operations on the selected items
        if (isset($_POST['selected'])) {
                
            
            // Delete selected items
            if (isset($_POST['DeleteButton'])) {
                
                foreach ($_POST['selected'] as $item_id) {
                    
                    foreach (Property::getByItem($item_id) as $property) {
                        Property::remove($item_id, $property->name);
                    }
                    
                    foreach (Dependency::getByDependentItem($item_id) as $dependency) {
                        Dependency::remove($dependency->item_id, $dependency->depends_on);
                    }
                    
                    foreach (Dependency::getByDependenceItem($item_id) as $dependency) {
                        Dependency::remove($dependency->item_id, $dependency->depends_on);
                    }
                    
                    ModificationEvent::removeAllBy($item_id);
                    
                    Item::remove($item_id);
                    
                    
                    
                }
                
                // Send a message to the user
                Yii::app()->user->setFlash('items', 'Items deleted.');
                $this->refresh();
            }
            
            
            
            
        }
        
        
        
        $params = array(
            'model' => $model,
        );
        
        if (isset($_GET['ajax'])) {
            $this->renderPartial('items', $params);
        } else {
            $this->render('items', $params);
        }
    }
    
    

    /**
     * Displays the item adding page with edit_item.php and ItemForm / PropertyForm
     */
    public function actionAddItem() {
        
        $model = new ItemForm();
        $type_id = $model->type;
        
        // Don't use the stored form values for a fresh edit
        if (isset($_GET['blank'])) {
            Yii::app()->session['remember_form'] = false;
        }
        
        // Get the PropertyTemplates for the properties of this type
        $properties = PropertyForm::createPropertiesByType($type_id);
        
        // Get the relationships for this item if we're returning from adding relationships
        $relationships = array();
        if (Yii::app()->session['remember_form']) {
            $relationships = RelationshipForm::getRelationships();
        }

        
        // Handle the received form, saving item, properties and relationships to the database
        if (isset($_POST['ItemForm'])) { 
            $valid=true;
            
            

            // Save the received temporary item and properties to form state 
            // while handling relationships
            if (isset($_POST['RelationshipsButton'])) {
                $item = new ItemForm;
                $item->attributes = $_POST['ItemForm'];
                Yii::app()->session['editing_item_type'] = $item->type;
                Yii::app()->session['editing_item_name'] = $item->name;
                
                
                foreach($properties as $i=>$property)
                {
                    if(isset($_POST['PropertyForm'][$i])) {
                        $property->attributes=$_POST['PropertyForm'][$i];
                        Yii::app()->session['editing_property_' . $property->name] = $property->value;
                    }
                }
                
                
                $this->redirect('index.php?r=site/editrelationships');
            }
            
            
            
            
            // Save the item, properties and relationships into the database
            else {
                $valid=true;

                // Get and validate property values
                foreach($properties as $i=>$property)
                {
                    if(isset($_POST['PropertyForm'][$i])) {
                        $property->attributes=$_POST['PropertyForm'][$i];
                    }
                    $valid=$property->validate() && $valid;
                }
                
                
                 // Get and validate relationship values
                $relationships = array();
                if (Yii::app()->session['remember_form']) {
                    $relationships = RelationshipForm::getRelationships();

                    foreach ($relationships as $i => $relationship) {
                        $valid=$relationship->validate() && $valid;
                    }
                }
                

                // Get item values
                $model->attributes = $_POST['ItemForm'];

                
                
                // Save if valid 
                if ($model->validate() && $valid) {
                    
                    $item = $model->saveItem(null);
                    
                    ModificationEvent::add($item["id"], null, true);
                    
                    foreach ($properties as $property) {
                        $property->saveProperty($item);
                    }
                    
                    foreach ($relationships as $relationship) {
                        $relationship->saveRelationship($item);
                    }
                    

                    Yii::app()->user->setFlash('edit_item', 'Item saved.');
                    Yii::app()->session['remember_form'] = false;
                    $this->refresh();
                }
            }
        }

        $this->render('edit_item', array('model' => $model, 
            'properties' => $properties, 
            'relationships' => $relationships,
            'new_item' => true));
    }
   
    
    
    
    
    
    /**
     * Displays the Type adding page, add_type.php
     */
    public function actionAddType() {
        $type = new TypeForm;
                
        $propertytemplates = array();
        $propertyTemplate;
             
        //posting Type or Requesting a new row
        if (isset($_POST['TypeForm'])) {
            
            //adding a row, keep form data as is
            if (isset($_POST['add-row'])) {
                $type->attributes=$_POST['TypeForm'];
                foreach($_POST['PropertyTemplateForm'] as $i=>$propertyTemplateInput) {
                    $propertyTemplate = new PropertyTemplateForm;
                    $propertyTemplate->attributes=$propertyTemplateInput;
                    $propertytemplates[] = $propertyTemplate;
                }
                $propertytemplates[] = new PropertyTemplateForm;
            }
            //submitting Type
            else {
                $type->attributes=$_POST['TypeForm'];
                $type = $type->saveType();
                foreach($_POST['PropertyTemplateForm'] as $i=>$propertyTemplateInput) {
                    $propertyTemplate = new PropertyTemplateForm;
                    $propertyTemplate->attributes=$propertyTemplateInput;
                    $propertyTemplate->savePropertyTemplate($type);
                }
                Yii::app()->user->setFlash('add_type', 'Type saved.');
                $this->refresh();
            }
   
        //just entering the view, add 1 propertytemplate
        }else{
            $propertytemplates[] = new PropertyTemplateForm;
        }
        
        $this->render('add_type', array('type' => $type, 'propertytemplates' => $propertytemplates));
    }
    
    
    
    /**
     * Displays the item editing page with edit_item.php and ItemForm / PropertyForm
     */
    public function actionEditItem() {
        
        $model = new ItemForm();
        $item_id = $_GET['item_id'];
        $item = Item::model()->findByPk($item_id);
        $model->name = $item->name;
        $model->type = $item->type_id;
        
        // Don't use the stored form values for a fresh edit
        if (isset($_GET['blank'])) {
            Yii::app()->session['remember_form'] = false;
        }
        
        // Get the PropertyTemplates for the properties of the edited item
        $properties = PropertyForm::getPropertiesFromItem($item_id);
        
        // Get the relationships for this item if we're returning from adding relationships or from existing relationships
        $relationships = array();
        if (Yii::app()->session['remember_form']) {
            $relationships = RelationshipForm::getRelationships();
        } else {
            $relationships = RelationshipForm::getRelationshipsFromItem($item_id);
        }

        
        // Handle the received form, saving item, properties and relationships to the database
        if (isset($_POST['ItemForm'])) { 
            $valid=true;
            
            

            // Save the received temporary item and properties to form state 
            // while handling relationships
            if (isset($_POST['RelationshipsButton'])) {
                $item = new ItemForm;
                $item->attributes = $_POST['ItemForm'];
                Yii::app()->session['editing_item_type'] = $item->type;
                Yii::app()->session['editing_item_name'] = $item->name;
                
                
                foreach($properties as $i=>$property)
                {
                    if(isset($_POST['PropertyForm'][$i])) {
                        $property->attributes=$_POST['PropertyForm'][$i];
                        Yii::app()->session['editing_property_' . $property->name] = $property->value;
                    }
                }
                
                
                $this->redirect('index.php?r=site/editrelationships&item_id=' . $item_id);
            }
            
            
            
            
            // Save the changes to item, properties and relationships
            else {
                $valid=true;

                // Get and validate property values
                foreach($properties as $i=>$property)
                {
                    if(isset($_POST['PropertyForm'][$i])) {
                        $property->attributes=$_POST['PropertyForm'][$i];
                    }
                    $valid=$property->validate() && $valid;
                }
                
                
                 // Get and validate relationship values
                $relationships = array();
                if (Yii::app()->session['remember_form']) {
                    $relationships = RelationshipForm::getRelationships();
                    Yii::app()->session['remember_form'] = false;

                    foreach ($relationships as $i => $relationship) {
                        $valid=$relationship->validate() && $valid;
                    }
                } else {
                    $relationships = RelationshipForm::getRelationshipsFromItem($item_id);
                }
                

                // Get item values
                $model->attributes = $_POST['ItemForm'];

                
                
                // Save if valid, deleting old values
                if ($model->validate() && $valid) {
                    
                    $item = $model->saveItem($item_id);
                    
                    ModificationEvent::add($item_id, null, false);
                    
                    Property::model()->deleteAllByAttributes(array('item_id' => $item_id));
                    foreach ($properties as $property) {
                        $property->saveProperty($item);
                    }
                    
                    Dependency::model()->deleteAllByAttributes(array('item_id' => $item_id));
                    Dependency::model()->deleteAllByAttributes(array('depends_on' => $item_id));
                    foreach ($relationships as $relationship) {
                        $relationship->saveRelationship($item);
                    }
                    

                    Yii::app()->user->setFlash('edit_item', 'Item saved.');
                    Yii::app()->session['remember_form'] = false;
                    $this->refresh();
                }
            }
        }
        
        

        $this->render('edit_item', array('model' => $model, 
            'properties' => $properties, 
            'relationships' => $relationships,
            'new_item' => false));
    }
   
    
    
    
    
    
    /**
     * Displays the relationship adding page, accessed through Add Item and Edit Item
     * submitted relationship data is passed on through session variables to addItem and editItem to be saved together with the item
     */
    public function actionEditRelationships() {
        
        // Get relationship forms for all items
        $relationships = array();
            
        foreach (Item::getAll() as $item) {
            $temp = new RelationshipForm;
            $temp->item_id = $item->id;
            
            if (isset($_GET['item_id'])) {
                $item_id = $_GET['item_id'];
                
                $temp->depends_on = !is_null(Dependency::model()->findByPk(array('item_id' => $item_id, 'depends_on' => $item->id)));
                $temp->dependency_to = !is_null(Dependency::model()->findByPk(array('item_id' => $item->id, 'depends_on' => $item_id)));
                
            }
            
            $relationships[] = $temp;
        }
        
        
        // Preset some values if the user has been here before
        if (Yii::app()->session['remember_form']) {
            $saved_relationships = RelationshipForm::getRelationships();
            
            foreach ($saved_relationships as $i => $relationship) {
                
                for ($j = 0; $j < count($relationships); $j++) {
                    if ($relationships[$j]->item_id == $relationship->item_id) {
                        $relationships[$j] = $relationship;
                        break;
                    }
                }
            }
        }
            
        
       
        
        // Handle submitted relationships
        if (isset($_POST['RelationshipForm'])) {
            
            
        
            $valid=true;

            // Get and validate the relationships
            foreach($relationships as $i => $relationship)
            {
                if(isset($_POST['RelationshipForm'][$i])) {
                    $relationship->attributes=$_POST['RelationshipForm'][$i];
                }
                
                
                $valid = $relationship->validate() && $valid;
            }
            
            
            if ($valid) {
                
                // Reset the session variables and save the valid relationships to them
                if (Yii::app()->session['editing_relationship_count'] != null) {
                    for ($i = 0; $i < Yii::app()->session['editing_relationship_count']; $i = $i + 1) {
                         Yii::app()->session['editing_relationship_' . $i . 'item_id'] = null;
                         Yii::app()->session['editing_relationship_' . $i . 'depends_on'] = null;
                         Yii::app()->session['editing_relationship_' . $i . 'dependency_to'] = null;
                    }
                }
                
                $count = 0;
                foreach ($relationships as $i => $relationship) {
                    
                    if (strcmp($relationship->dependency_to, '1') == 0 
                            || strcmp($relationship->depends_on, '1') == 0) {
                        
                        Yii::app()->session['editing_relationship_' . $count . 'item_id'] = $relationship->item_id;
                        Yii::app()->session['editing_relationship_' . $count . 'depends_on'] = $relationship->depends_on;
                        Yii::app()->session['editing_relationship_' . $count . 'dependency_to'] = $relationship->dependency_to;
                        $count = $count + 1;
                    }
                }
                Yii::app()->session['editing_relationship_count'] = $count;
                
                // Set the session variable so we know to use the saved values in edit_item when returning to it
                Yii::app()->session['remember_form'] = true;
                
                
                
                // Return to item editing or adding view
                if (isset($_GET['item_id'])) {
                    $this->redirect('index.php?r=site/edititem&item_id=' . $_GET['item_id']);
                } else {
                    $this->redirect('index.php?r=site/additem&type=' . Yii::app()->session['editing_item_type']);
                }
            
            }
            
            
        }
        
   
        $this->render('edit_relationship', array('relationships' => $relationships));
        
    }
    
    
    
    
    /**
     * Displays the item view page with view_item.php
     * Fetches Item to display by it's id
     */
    public function actionViewItem() {
        $item = null;
        if (isset($_GET['item_id'])) {
            $item = Item::model()->findByPk($_GET['item_id']);
            $events = ModificationEvent::getByItem($_GET['item_id']);
        }
        
        $this->render('view_item', array('model' => $item, 'events' => $events));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        
        $latestCreated = ModificationEvent::getLatest(20, true);
        $latestEdited = ModificationEvent::getLatest(20, false);
        
        $this->render('index', array(
            'latestCreated' => $latestCreated,
            'latestEdited' => $latestEdited)
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {


        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
