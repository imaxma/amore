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
     * �û��˳�ϵͳ
     */
    function actionLogout(){
        //ɾ��session��Ϣ
        //Yii::app()->session->clear();  //ɾ���ڴ����sessiion������Ϣ
        //Yii::app()->session->destroy(); //ɾ����������session�ļ�

        //session��cookieһ��ɾ��
        Yii::app()->user->logout();

        $this->redirect(Yii::app()->user->returnUrl.'index');
    }
}