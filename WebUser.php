<?php
/** Created by griga at 08.04.14 | 1:41.
 * 
 */

class WebUser extends CWebUser{

    /**
     * @var User
     */
    private $_model;

    public function getRole(){
        $user = $this->loadUser(app()->user->id);
        return $user ? $user->role : 'guest';
    }

    public function isAdmin(){
        return $this->getRole() == 'admin';
    }

    protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null){
                $this->_model=User::model()->findByPk($id);
                $this->setDefaultLanguage();
            }
        }
        return $this->_model;
    }

    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        if($this->isAdmin()){
            return true;
        } else {
            return parent::checkAccess($operation, $params, $allowCaching);

        }
    }

    public function setDefaultLanguage(){
        if($this->_model->profile && $this->_model->profile->lang){
            Lang::update($this->_model->profile->lang);
        }
    }


} 