<?php

namespace App\GraphQL\Query;

use Encore\Admin\Models\Task\Task;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class TaskQuery extends Query
{
    protected $attributes = [
        'name' => 'tasks',
        'description' => 'A query'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Task'));
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::string()],
            'title' => ['name' => 'title', 'type' => Type::string()]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        if (isset($args['id'])) {
            return Task::where('id', $args['id'])->get();
        } elseif (isset($args['email'])) {
            return Task::where('title', $args['title'])->get();
        } else {
            return Task::with('value')->get();
        }
    }
}
