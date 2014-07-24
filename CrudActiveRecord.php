<?php
/** Created by griga at 06.06.2014 | 1:59.
 * 
 */

class CrudActiveRecord extends CActiveRecord {

    /**
     * @param mixed $conditions
     * @param string $keyField
     * @param string $valueField
     * @return array
     */
    public function listData($conditions = null, $keyField='id', $valueField='name'){
        $models = $conditions ? self::findAll($conditions) : self::findAll();
        return CHtml::listData($models, $keyField, $valueField);
    }


    public function getBehavior($behaviorClassName){
        foreach ($this->behaviors() as $behavior){
            $classChunks = explode('.',$behavior['class']);
            if(in_array($behaviorClassName, $classChunks)){
                return $behavior;
            }
        }
        return null;
    }

    /**
     * @param $behaviorClassName
     * @return bool
     */
    public function hasBehavior($behaviorClassName){
        return (bool)$this->getBehavior($behaviorClassName);
    }

    public function afterMultilang(){
        /** @var MultilingualBehavior $ml */
        $ml = $this->getBehavior('MultilingualBehavior');
        foreach($ml['localizedAttributes'] as $attribute){
            $localizedDefaultAttributeName = $attribute.'_'.Lang::getDefault();
            if($this->$localizedDefaultAttributeName)
                $this->$attribute = $this->$localizedDefaultAttributeName;
        }
    }

    public function enabled(){
        $alias=$this->getTableAlias();
        $this->getDbCriteria()->mergeWith([
            'condition'=>$alias.'.enabled=1',
        ]);
        return $this;
    }

    public function order($order){
        $alias=$this->getTableAlias();
        $this->getDbCriteria()->mergeWith([
            'order'=>$order,
        ]);
        return $this;
    }

    public function limit($limit){
        $alias=$this->getTableAlias();
        $this->getDbCriteria()->mergeWith([
            'limit'=>$limit,
        ]);
        return $this;
    }

    protected function beforeValidate()
    {
        if($this->hasBehavior('MultilingualBehavior')){
            $ml = $this->getBehavior('MultilingualBehavior');
            foreach($ml['localizedAttributes'] as $attribute){
                $localizedDefaultAttributeName = $attribute.'_'.Lang::getDefault();
                if($this->$localizedDefaultAttributeName)
                    $this->$attribute = $this->$localizedDefaultAttributeName;
            }
        }
        return parent::beforeValidate();
    }

    protected function afterFind()
    {
        parent::afterFind();
        if($this->hasBehavior('MultilingualBehavior')){
            $ml = $this->getBehavior('MultilingualBehavior');
            foreach($ml['localizedAttributes'] as $attribute){
                $localizedDefaultAttributeName = $attribute.'_'.Lang::getDefault();
                $localizedAttribute = $this->getLangAttribute($localizedDefaultAttributeName);
                if($localizedAttribute && $this->$attribute != $localizedAttribute)
                    $this->$attribute = $localizedAttribute;
            }
        }
    }

}