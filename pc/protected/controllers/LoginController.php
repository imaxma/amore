<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/15
 * Time: 16:33
 */
class LoginController extends Controller
{
    /*
     * index action
     */
    public function actionIndex()
    {
        $model = new LoginForm();

        if (isset($_POST['ajax']) && $_POST['ajax']==='login')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];
            if($model->validate() && $model->login())
            {
                $this->redirect(Yii::app()->user->returnUrl.'index');
            }
        }
        //Yii::app()->session->clear();
        //Yii::app()->session->destroy();
        $this->renderPartial('index',array(
            'model' => $model
        ));
    }

    /*
     * 用户退出系统
     */
    function actionLogout(){
        //删除session信息
        //Yii::app()->session->clear();  //删除内存里边sessiion变量信息
        //Yii::app()->session->destroy(); //删除服务器的session文件

        //session和cookie一并删除
        Yii::app()->user->logout();

        $this->redirect(Yii::app()->user->returnUrl.'index');
    }
}