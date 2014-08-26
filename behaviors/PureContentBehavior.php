<?php

/** Created by griga at 22.08.14 | 18:28.
 *
 */
class PureContentBehavior extends CActiveRecordBehavior
{

    public $fields = [];

    public function pureContent()
    {
        $content = '';
        foreach ($this->fields as $key => $value) {
            if(is_array($value)){
                if(isset($value['list']))
                    foreach($this->owner[$key] as $child)
                        foreach($value['fields'] as $fieldKey=>$fieldValue)
                            $content .= $this->_process($fieldKey, $fieldValue, $child);
            } else {
                $content .= $this->_process($key, $value, $this->owner);
            }

        }
        return $content;

    }



    private function _process($key, $value, $context){
        if (is_callable($value)) {
            return call_user_func_array($value, [$context]);
        } else if ($value === 'image') {
            return CHtml::image($context->getDefaultPicture()->filename, $context->name);
        } else if ($value === 'alias') {
            return $context->getGlobalLink();
        } else if($value === 'price'){
            return CHtml::tag('div', [],Config::get('site_currency') . $context->getAttribute($key));
        } else {
            return CHtml::tag($value, [], $context->getAttribute($key));
        }
    }
} 