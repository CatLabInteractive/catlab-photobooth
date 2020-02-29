<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\V1\Controllers\Base\ResourceController;
use App\Http\Api\V1\ResourceDefinitions\AssetResourceDefinition;
use App\Models\Asset;
use App\Models\Subject;
use CatLab\Charon\Collections\RouteCollection;
use Illuminate\Http\Request;

/**
 * Class AssetController
 * @package App\Http\Api\V1\Controllers
 */
class PhotosController extends ResourceController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(AssetResourceDefinition::class);
    }

    /**
     * @param RouteCollection $routes
     * @throws \CatLab\Charon\Exceptions\InvalidContextAction
     */
    public static function setRoutes(RouteCollection $routes)
    {
        $routes->group(function(RouteCollection $routes)
        {
            $routes->tag('photos');

            $routes
                ->get('photos/by-name', 'PhotosController@getByName')
                ->returns()->many(AssetResourceDefinition::class)
                ->parameters()->query('name')
                ->summary('Get photos from name');
        });
    }

    /**
     * @param Request $request
     * @return array
     * @throws \CatLab\Charon\Exceptions\InvalidContextAction
     * @throws \CatLab\Charon\Exceptions\InvalidEntityException
     * @throws \CatLab\Charon\Exceptions\InvalidPropertyException
     * @throws \CatLab\Charon\Exceptions\InvalidTransformer
     * @throws \CatLab\Charon\Exceptions\IterableExpected
     * @throws \CatLab\Charon\Exceptions\VariableNotFoundInContext
     */
    public function getByName(Request $request)
    {
        $name = $request->query('name');
        $subject = Subject::where('name', '=', $name)->first();
        if (!$subject) {
            return $this->getErrorMessage('Could not find subject with name "' . $name . '"');
        }

        $photos = $subject->assets()->get();
        return $this->output($photos);
    }
}
