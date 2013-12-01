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
        $type_id = $model->type_id;
        
        // Get the PropertyTemplates for the properties of this type
        $properties = PropertyForm::createPropertiesByType($type_id);
        

        // Handle received item form
        if (isset($_POST['ItemForm']) and isset($_POST['PropertyForm'])) {
            
            
            

            // Save the received temporary item and properties to form state 
            // while handling relationships
            // Doesn't work yet
            if (isset($_POST['yt1'])) {
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
            }
            
            
            
            
            // Save the item and properties into the database
            // and validate them
            else {
                $valid=true;

                // Get and validate property values
                foreach($properties as $i=>$property)
                {
                    if(isset($_POST['PropertyForm'][$i])) {
                        $property->attributes=$_POST['PropertyForm'][$i];
                        //$property->value=$_POST['Property'][$i]['value'];
                    }
                    $valid=$property->validate() && $valid;
                }

                $model->attributes = $_POST['ItemForm'];

                // Save the Item and Properties if valid
                if ($model->validate() && $valid) {

                    $item = $model->saveItem();

                    foreach ($properties as $property) {
                        $property->saveProperty($item);
                    }

                    Yii::app()->user->setFlash('add_item', 'Item saved.');
                    $this->refresh();
                }
            }
        }

        $this->render('add_item', array('model' => $model, 'properties' => $properties));
    }
    
    
    
    /**
     * Displays the relationship adding page, accessed through Add Item and Edit Item
     */
    public function actionAddRelationship() {
        
        $model = new RelationshipForm;
   
        
        $this->render('add_relationship', array('model' => $model));
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
