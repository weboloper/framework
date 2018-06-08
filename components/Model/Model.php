<?php

namespace Components\Model;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model as BaseModel;
use Phalcon\Tools\ZFunction;
use Components\Model\Behavior\Blameable as ModelBlameable;


class Model extends BaseModel
{   
    // public function initialize()
    // {           
    //     $this->addBehavior(new ModelBlameable());
    //     $this->keepSnapshots(true);
    // }

    /**
     * {@inheritdoc}
     */
    public function beforeValidationOnCreate()
    {
        $metadata = $this->getModelsMetaData();
        $defaults = $metadata->getDefaultValues($this);
        $attributes = $metadata->getNotNullAttributes($this);

        # set all not null fields to their default value.
        foreach ($attributes as $field) {
            if (! property_exists($this, $field)) {
                continue;
            }

            if (
                isset($this->{$field}) ||       # if value already set, continue
                ! is_null($this->{$field}) ||   # if not null, continue
                ! isset($defaults[$field])      # if not in the defaults, continue
            ) {
                continue;
            }

            $this->{$field} = new RawValue($defaults[$field]);
        }
    }

    public static function getBuilder()
    {

        return di()->get('modelsmanager')->createBuilder();
    }

    public static function prepareQueriesPosts($join, $where, $limit = 15)
    {
        $modelNamespace = __NAMESPACE__ . '\\' ;

        /**
         *
         * @var \Phalcon\Mvc\Model\Query\BuilderInterface $itemBuilder
         */
        $itemBuilder = self::getBuilder()
            ->from(['p' => Posts::class])
            ->orderBy('p.created_at DESC');


        if (isset($join) && is_array($join)) {
            $type = (string) $join['type'];
            $itemBuilder->$type($modelNamespace . $join['model'], $join['on'], $join['alias']);
        }
        if (isset($where)) {
            $itemBuilder->where($where);
        }

        $totalBuilder = clone $itemBuilder;

        $itemBuilder
            ->columns(array('p.*'))
            ->limit($limit);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        return array($itemBuilder, $totalBuilder);
    }
}
