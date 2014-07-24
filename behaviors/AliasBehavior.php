<?php
/** Created by griga at 29.06.2014 | 11:11.
 * 
 */

class AliasBehavior extends CActiveRecordBehavior {

    public $sourceAttribute;
    public $aliasAttribute;

    public function afterValidate($event)
    {

        if(!isset($_POST[get_class($this->owner)][$this->aliasAttribute]))
            $this->owner->{$this->aliasAttribute} = StringHelper::generateAlias($this->owner->{$this->sourceAttribute});
    }

} 