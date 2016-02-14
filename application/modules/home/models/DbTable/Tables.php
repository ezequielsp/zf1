<?php

class Home_Model_DbTable_Tables extends Zend_Db_Table_Abstract
{
    public function getRowsMapper()
    {
        $select = $this->select();
        return $select;
    }
}
