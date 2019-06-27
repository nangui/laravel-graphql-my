<?php

namespace App\GraphQL\Mutations;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class RegisterUser
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $email = $args['input']['email'];
        $password = $args['input']['password'];
        $userx = User::where(['email' => $email])->get();

        if (count($userx))
        {
            return new \Exception('User exists!');
        }
        $us = new User;
        $us->email = $email;
        $us->name = "change to your name";
        $us->password = \Hash::make($password);
        $userSaved = $us->save();
        $auth_token = JWTAuth::fromUser($userSaved);
        $user = $us;
        return compact('auth_token', 'user');
    }
}
