<?php
/** Created by griga at 04.04.14 | 2:37.
 * 
 */

class DbMigration extends CDbMigration{
    public function createTable($table, $columns, $options = null)
    {

        if($this->isMySql())
            parent::createTable($table, $columns,  'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci');
        else
            parent::createTable($table, $columns, $options);

    }

    public function dbType()
    {
        return DbHelper::dbType();

    }

    public function isMySql(){
        return DbHelper::isMysql();
    }


} 