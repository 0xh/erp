<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class ValueType extends BaseType
{
    protected $attributes = [
        'name' => 'ValueType',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the task'
            ],
            'task_id' => [
                'type' => Type::int(),
                'description' => 'The task_id of the task'
            ],
            'root_id' => [
                'type' => Type::int(),
                'description' => 'The root_id of the task'
            ],
            'attribute_id' => [
                'type' => Type::int(),
                'description' => 'The attribute_id of the task'
            ],
            'task_value' => [
                'type' => Type::string(),
                'description' => 'The task_value of the task'
            ]
        ];
    }
}
