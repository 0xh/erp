<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class TaskType extends BaseType
{
    protected $attributes = [
        'name' => 'TaskType',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            
        ];
    }
}
