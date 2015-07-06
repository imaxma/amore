<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/15
 * Time: 16:52
 */
class Customs extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{customs}}';
    }

}
?>