<?php

namespace App\Http\Api\V1\ResourceDefinitions;

use App\Models\Subject;
use CatLab\Charon\Models\ResourceDefinition;

/**
 *
 */
class SubjectResourceDefinition extends ResourceDefinition
{
    public function __construct()
    {
        parent::__construct(Subject::class);

        $this->identifier('id');

        $this->field('name')
            ->string()
            ->writeable(true, true)
            ->visible(true, true);

        $this->field('email')
            ->string()
            ->writeable(true, true)
            ->visible(true, true);
    }
}
