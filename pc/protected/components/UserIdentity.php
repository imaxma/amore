<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    public function authenticate()
    {
        //在这个地方来校验用户名和密码的真实性
        //首先来看看是否有此用户名存在
        //find() 如果没有查询出来数据，则会返回null
        //findAll()  空数据会返回空数组
        //根据用户名查询是否有一个用户信息
        $amoreadmin_model = AmoreAdmin::model()->find('phone=:phone',array(':phone'=>$this->username));

        //如果用户名不存在
        if($amoreadmin_model === null){
            $this -> errorCode = self::ERROR_USERNAME_INVALID;
            return false;
        } else if ($amoreadmin_model->Password !== md5($this -> password)){
            //密码判断
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            return false;
        } else {
            Yii::app()->session['amore_admin_id'] = $amoreadmin_model->Id;
            Yii::app()->session['name'] = $amoreadmin_model->Name;
            Yii::app()->session['lastLogintime'] = $amoreadmin_model->LastLoginTime;
            $this->errorCode=self::ERROR_NONE;
            return true;
        }
    }

    public function authenticate_update()
    {
        $user_model = AmoreAdmin::model()->find('Id=:name', array(':name' => $this->username));
        //如果用户名不存在
        if ($user_model === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            return false;
        } else if ($user_model->Password !== md5($this->password)) {
            //密码判断
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            return false;
        } else {
            return true;
        }
    }







}