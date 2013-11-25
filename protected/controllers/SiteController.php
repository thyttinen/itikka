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
     * Displays the item adding page with add_item.php and ItemForm
     */
    public function actionAddItem() {
        
        
        
        $model = new ItemForm();
        $type_id = $model->type_id;
        
        // Get the PropertyTemplates for the properties of this type
        // then create valueless Properties based on them
        $templates = PropertyTemplate::getByType($type_id);
        
        $properties = array();
        
        foreach ($templates as $template) {
            $temp = new Property;
            $temp->name = $template->name;
            $properties[] = $temp;
        }
        

        // Handle received form
        if (isset($_POST['ItemForm']) and isset($_POST['Property'])) {
            $valid=true;
            
            // Check property values; uses text_value as a temporary holder of all types of values until saving for simplicity
            foreach($properties as $i=>$property)
            {
                if(isset($_POST['Property'][$i])) {
                    $property->attributes=$_POST['Property'][$i];
                    $property->value_text=$_POST['Property'][$i]['value_text'];
                }
                $valid=$property->validate() && $valid;
            }
            
            $model->attributes = $_POST['ItemForm'];
            
            // Save the Item and Properties; 
            // implementation must use $model->saveProperties
            if ($model->validate() && $valid) {
                
                $model->saveItem();
                $model->saveProperties($properties, $templates);

                Yii::app()->user->setFlash('add_item', 'Item saved.');
                $this->refresh();
            }
        }
        

        $this->render('add_item', array('model' => $model, 'properties' => $properties, 'templates' => $templates));
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
