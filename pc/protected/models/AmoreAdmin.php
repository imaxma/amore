<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/15
 * Time: 16:52
 */
class AmoreAdmin extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{amore_admin}}';
    }


    public $old_password;
    private $_identity;
    public $password_again;

    public function rules()
    {
        return array(
            array('old_password', 'required','message'=>'<span style="color:#C66">请输入旧密码</span>','on'=>'update'),
            array('Password', 'required','message'=>'<span style="color:#C66">新密码必填</span>','on'=>'update'),
            array('Password', 'length', 'max'=>20, 'min'=>6, 'tooLong'=>'<span style="color:#C66">密码太长</span>', 'tooShort'=>'<span style="color:#C66">密码太短</span>','on'=>'update'),
            array('old_password','authenticate','on'=>'update'),
            array('password_again', 'compare', 'compareAttribute' => 'Password', 'message' => '<span style="color:#C66">两次密码输入不一致</span>','on'=>'update'),
            array('Id','required','message'=>'<span style="color:#C66">帐户出错,请重新登录后再修改</span>','on'=>'update'),
        );
    }
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $this->_identity=new UserIdentity($this->Id,$this->old_password);
            if(!$this->_identity->authenticate_update())
                $this->addError('old_password','<span style="color:#C66">旧密码不正确，请重新输入</span>');
        }
    }

}
?>