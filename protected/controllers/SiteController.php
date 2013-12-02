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
        $model = new Item();

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
     * Displays the item adding page with add_item.php and ItemForm / PropertyForm
     */
    public function actionAddItem() {
        
        $model = new ItemForm();
        $type_id = $model->type;
        
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
            if (isset($_POST['yt1'])) {
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
                
                
                $this->redirect('index.php?r=site/addrelationship');
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
                $relationships = RelationshipForm::getRelationships();
                
                foreach ($relationships as $i => $relationship) {
                    $valid=$relationship->validate() && $valid;
                }
                

                // Get item values
                $model->attributes = $_POST['ItemForm'];

                
                
                // Save if valid 
                if ($model->validate() && $valid) {
                    
                    $item = $model->saveItem();
                    foreach ($properties as $property) {
                        $property->saveProperty($item);
                    }
                    foreach ($relationships as $relationship) {
                        $relationship->saveRelationship($item);
                    }
                    

                    Yii::app()->user->setFlash('add_item', 'Item saved.');
                    Yii::app()->session['remember_form'] = false;
                    $this->refresh();
                }
            }
        }

        $this->render('add_item', array('model' => $model, 
            'properties' => $properties, 
            'relationships' => $relationships));
    }
   
    
    
    
    
    
    /**
     * Displays the relationship adding page, accessed through Add Item and Edit Item
     * submitted relationship data is passed on through session variables to addItem to be saved together with the item
     */
    public function actionAddRelationship() {
        
        // Get relationship forms for all items
        $relationships = array();
            
        foreach (Item::getAll() as $item) {
            $temp = new RelationshipForm;
            $temp->item_id = $item->id;
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
                
                // Set the session variable so we know to use the saved values in add_item when returning to it
                Yii::app()->session['remember_form'] = true;
                
                
                
                // Return to item adding view
                $this->redirect('index.php?r=site/additem&type=' . Yii::app()->session['editing_item_type']);
            
            }
            
            
        }
        
   
        $this->render('add_relationship', array('relationships' => $relationships));
        
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
     * Displays the item view page with add_item.php
     * Fetches Item to display by it's id
     */
    public function actionViewItem() {
        $item = null;
        if (isset($_GET['item_id'])) {
            $item = Item::model()->findByPk($_GET['item_id']);
        }
        $this->render('view_item', array('model' => $item));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
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
