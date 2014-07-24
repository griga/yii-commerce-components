<?php

/** Created by griga at 18.05.2014 | 16:09.
 *
 */
class DbHelper
{

    public static function dbType()
    {
        list($type) = explode(':', Yii::app()->db->connectionString);
        return $type;
    }

    public static function isMysql()
    {
        return self::dbType() == 'mysql';
    }

    public static function currentDateFunction()
    {
        return self::isMysql() ? 'CURDATE()' : 'DATE()';
    }

    public static function timestampExpression()
    {
        return (self::isMysql() ? 'NOW()' : 'datetime(\'now\')');
    }

} 