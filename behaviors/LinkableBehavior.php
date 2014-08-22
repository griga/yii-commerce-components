<?php
/** Created by griga at 30.01.14 | 20:25.
 * 
 */

class LinkableBehavior extends CActiveRecordBehavior{

    public $titleAttribute = 'name';
    public $urlAttribute;
    public $urlPath;
    public $urlRule;

    public function getUrl(){
        if(is_string($this->urlRule)){
            $model = $this->owner;
            return Yii::app()->createUrl(@eval($this->urlRule));
        } else if(is_callable($this->urlRule)){
            return Yii::app()->createUrl(call_user_func_array($this->urlRule, [$this->owner]));
        } else {
            return Yii::app()->createUrl($this->urlPath . ($this->owner->{$this->urlAttribute} ?: $this->owner->primaryKey)) ;
        }

    }

    public function getLink($htmlOptions = []){
        return CHtml::link($this->owner->{$this->titleAttribute}, $this->getUrl(), $htmlOptions);
    }

    public function getImageLink($img, $htmlOptions = []){
        return CHtml::link(CHtml::image($img, $this->owner->{$this->titleAttribute}), $this->getUrl(), $htmlOptions);
    }

    public function getDefaultImageLink($width, $height, $htmlOptions = [], $imageHtmlOptions = []){
        return CHtml::link(CHtml::image($this->owner->defaultPicture->thumbUrl($width, $height), $this->owner->{$this->titleAttribute}, $imageHtmlOptions), $this->getUrl(), $htmlOptions);
    }

    /*------------------------------------*\
      global links
    \*------------------------------------*/


    public function getGlobalUrl(){
        return Yii::app()->request->hostInfo . Yii::app()->baseUrl . $this->getUrl();
    }

    public function getGlobalLink($htmlOptions = []){
        return CHtml::link($this->owner->{$this->titleAttribute}, $this->getGlobalUrl(), $htmlOptions);
    }

    public function getGlobalImageLink($img, $htmlOptions = []){
        return CHtml::link(CHtml::image($img, $this->owner->{$this->titleAttribute}), $this->getGlobalUrl(), $htmlOptions);
    }

    public function getGlobalDefaultImageLink($width, $height, $htmlOptions = []){
        return CHtml::link(CHtml::image($this->owner->defaultPicture->globalThumbUrl($width, $height), $this->owner->{$this->titleAttribute}), $this->getGlobalUrl(), $htmlOptions);
    }
} 