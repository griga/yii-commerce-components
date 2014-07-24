<?php

/** Created by griga at 28.06.2014 | 23:01.
 *
 */
class FrontForm extends CActiveForm
{
    public $errorMessageCssClass = 'label label-danger';
    public $enableAjaxValidation = true;
    public $clientOptions = [
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false,
    ];
    public $htmlOptions = [
        'role' => 'form'
    ];
} 