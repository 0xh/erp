<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class UserType extends BaseType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A User'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the user'
            ],
            'token' => [
                'type' => Type::string(),
                'description' => 'The token of the user',
            ],
        ];
    }

    protected function resolveEmailField($root, $args)
    {
        return strtolower($root->email);
    }
}
