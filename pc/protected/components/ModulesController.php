<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ModulesController extends Controller
{
    public $layout = "main";
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */

    public function __construct($id,$model=null)
    {
        $url = Yii::app()->user->returnUrl;
        if(isset(Yii::app()->session['amore_admin_id']) && !empty(Yii::app()->session['amore_admin_id']))
        {
            $amore_model = AmoreAdmin::model();
            $amore_model = $amore_model->findByPk(Yii::app()->session['amore_admin_id']);
            if ($amore_model->Phone == '')
            {
                $this->redirect($url.'login');
            }
        }
        else
        {
            $this->redirect($url.'login');
        }
        parent::__construct($id,$model);
    }

}