<?php

class Home_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    public function save($data) 
    {
    	//$mail = new App_HtmlMailer();
    	//$mail->newUserCeated($data);
        if(isset($data['id'])) {
            $this->update($data, "id = {$data['id']}");
        } else {
            $this->insert($data);
        }
    }
}

