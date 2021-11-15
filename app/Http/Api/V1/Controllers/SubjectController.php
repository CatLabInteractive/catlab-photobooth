<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\V1\ResourceDefinitions\SubjectResourceDefinition;
use App\Models\Subject;
use CatLab\Charon\Collections\RouteCollection;
use Illuminate\Http\Request;

/**
 *
 */
class SubjectController extends Base\ResourceController
{
    const RESOURCE_DEFINITION = SubjectResourceDefinition::class;
    const RESOURCE_ID = 'id';

    use \CatLab\Charon\Laravel\Controllers\CrudController;

    /**
     * @throws \CatLab\Charon\Exceptions\InvalidResourceDefinition
     */
    public function __construct()
    {
        parent::__construct(SubjectResourceDefinition::class);
    }

    /**
     * @param \CatLab\Charon\Collections\RouteCollection $routes
     * @throws \CatLab\Charon\Exceptions\InvalidContextAction
     */
    public static function setRoutes(RouteCollection $routes)
    {
        $routes->group(function(RouteCollection $routes)
        {
            $routes->tag('subjects');

            $routes
                ->get('subjects/{uid}', 'SubjectController@viewFromUid')
                ->returns()->many(SubjectResourceDefinition::class)
                ->parameters()->query('uid')
                ->summary('Get a subject by card uid');

            $routes
                ->get('subjects/{id}', 'SubjectController@view')
                ->returns()->many(SubjectResourceDefinition::class)
                ->parameters()->query('id')
                ->summary('Get a subject by card id');

            $routes
                ->put('subjects/{id}', 'SubjectController@edit')
                ->returns()->many(SubjectResourceDefinition::class)
                ->parameters()->query('id')
                ->parameters()->resource(SubjectResourceDefinition::class)
                ->summary('Update subject');
        });
    }

    public function viewFromUid(Request $request, $uid)
    {
        $subject = Subject::getFromIdentifier($uid);

        return $this->output($subject);
    }
}
