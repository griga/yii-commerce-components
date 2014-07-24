<?php

/** Created by griga at 30.06.2014 | 15:44.
 *
 */
class ManyToManySaveBehavior extends CActiveRecordBehavior
{

    public function afterSave($event)
    {
        foreach ($this->owner->relations() as $relationName => $relation) {
            if ($relation[0] == CActiveRecord::MANY_MANY) {
                preg_match('/(.+)\((.+), ?(.+)\)/', $relation[2], $matches);
                list($fullRelation, $tableName, $ownerField, $childField) = $matches;
                $command = db()->createCommand()
                    ->select($childField)
                    ->from($tableName)
                    ->where($ownerField . '=:oid', [':oid' => $this->owner->primaryKey]);
                if(isset($relation['condition']))
                    $command->andWhere($relation['condition']);
                $oldRelatedChildren = $command->queryColumn();
                if (isset($_POST[get_class($this->owner)][$relationName]) && is_array($_POST[get_class($this->owner)][$relationName])) {
                    $builder = db()->schema->commandBuilder;
                    $data = [];
                    foreach ($_POST[get_class($this->owner)][$relationName] as $childId) {
                        if (in_array($childId, $oldRelatedChildren)) {
                            unset($oldRelatedChildren[array_search($childId, $oldRelatedChildren)]);
                        } else {
                            $related = [$childField => $childId, $ownerField => $this->owner->primaryKey,];
                            if(isset($relation['condition'])){
                                list($conditionField, $conditionValue) = explode('=', $relation['condition']);
                                $related[$conditionField] = trim($conditionValue,'" ');
                            }
                            $data[] = $related;
                        }
                    }
                    if (count($data) > 0) {
                        $command = $builder->createMultipleInsertCommand($tableName, $data);
                        $command->execute();
                    }
                }
                if (count($oldRelatedChildren) > 0) {
                    db()->createCommand()->delete($tableName,
                        ( isset($relation['condition']) ? $relation['condition'] . ' AND ' : '') .
                        $childField . ' IN(' . implode(',', $oldRelatedChildren) . ') AND ' . $ownerField . ' = :oid',
                        [':oid' => $this->owner->primaryKey]);
                }
            }

        }

    }


} 