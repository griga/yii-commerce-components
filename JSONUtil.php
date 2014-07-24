<?php
/**
 * Description of JSONUtil
 *
 * @link http://www.yiiframework.com/forum/index.php/topic/41922-convert-model-with-relations-to-php-array-and-json/
 * @author
 */
class JSONUtil {
    /**
     * Converting a Yii model with all relations to a an array.
     * @param mixed $models A single model or an array of models for converting to array.
     * @param array $filterAttributes should be like array('table name'=>'column names','user'=>'id,firstname,lastname'
     * 'comment'=>'*') to filter attributes. Also can use alias for column names by using AS with the column name just
     * like in SQL.
    * @return array array of converted model with all related relations.
     */
    public static function convertModelToArray($models, array $filterAttributes = null) {
        if (is_array($models))
            $arrayMode = TRUE;
        else {
            $models = array($models);
            $arrayMode = FALSE;
        }

        $result = array();
        foreach ($models as $model) {
            $attributes = $model->getAttributes();

            if (isset($filterAttributes) && is_array($filterAttributes)) {
                foreach ($filterAttributes as $key => $value) {

                    if (strtolower($key) == strtolower($model->tableName())) {
                        $value = str_replace(' ', '', $value);
                        $arrColumn = explode(",", $value);

                        if (strpos($value, '*') === FALSE) {
                            $attributes = array();
                        }

                        foreach ($arrColumn as $column) {
                            if ($column != '*') {
                                $attributes[$column] = $model->$column;
                            }
                        }
                        //foreach ($attributes as $key => $value) {
                        //if (!in_array($key, $arrColumn))
                        //unset($attributes[$key]);
                        //}
                    }
                }
            }

            $relations = array();
            foreach ($model->relations() as $key => $related) {
                if ($model->hasRelated($key)) {
                    $relations[$key] = self::convertModelToArray($model->$key, $filterAttributes);
                }
            }
            $all = array_merge($attributes, $relations);

            if ($arrayMode)
                array_push($result, $all);
            else
                $result = $all;
        }
        return $result;
    }
}