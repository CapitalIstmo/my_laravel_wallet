<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V1\UserResource;

class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;


    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'organization' => 'ManuelCanulDev E.I',
                'authors' => [
                    'Manuel Canul',
                    'ManuelCanulDev E.I.'
                ],
            ],
            'type' => 'collection'
        ];
    }
}
