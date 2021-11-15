<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\V1\ResourceDefinitions\AssetResourceDefinition;
use App\Models\Subject;
use CatLab\CentralStorage\Client\Models\Asset;
use CatLab\Charon\Collections\RouteCollection;
use CatLab\Charon\Enums\Action;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Class UploadController
 * @package App\Http\Api\V1\Controllers
 */
class UploadController extends Base\ResourceController
{
    /**
     * Set all routes for this controller
     * @param RouteCollection $routes
     */
    public static function setRoutes(RouteCollection $routes)
    {
        $routes->group(function(RouteCollection $routes)
        {
            $routes->tag('upload');

            $routes
                ->post('assets', 'UploadController@upload')
                ->returns()->one(AssetResourceDefinition::class)
                ->summary('Upload a new file');
        });
    }

    /**
     * UserController constructor.
     * @throws \CatLab\Charon\Exceptions\InvalidResourceDefinition
     */
    public function __construct()
    {
        parent::__construct(AssetResourceDefinition::class);
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     * @throws \CatLab\Charon\Exceptions\InvalidContextAction
     * @throws \CatLab\Charon\Exceptions\InvalidEntityException
     * @throws \CatLab\Charon\Exceptions\InvalidPropertyException
     * @throws \CatLab\Charon\Exceptions\InvalidTransformer
     * @throws \CatLab\Charon\Exceptions\IterableExpected
     * @throws \CatLab\Charon\Exceptions\VariableNotFoundInContext
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \CatLab\Charon\Exceptions\InvalidResourceDefinition
     */
    public function upload(Request $request)
    {
        $this->authorize('upload', Asset::class);

        $file = $this->getUploadedFile($request);
        if ($file) {

            // remove file
            $content = $request->json();
            if ($content) {
                $subjectId = $content->get('subjectIdentifier');
                $originalUrl = $content->get('originalUrl');
                if ($originalUrl) {
                    // check if we have already uploaded this.
                    $existingAsset = Asset::where('original_url', '=', $originalUrl)->first();
                    if ($existingAsset) {

                        unlink($file->getRealPath());

                        $context = $this->getContext(Action::VIEW);
                        $resource = $this->toResource($existingAsset, $context);
                        return $this->toResponse($resource);
                    }
                }

                /** @var \App\Models\Asset $asset */
                $asset = \CentralStorage::store($file);
                $asset->original_url = $originalUrl;
                $asset->save();

                unlink($file->getRealPath());

                if ($subjectId) {
                    $subject = Subject::getFromIdentifier($subjectId);
                    if ($subject) {
                        $asset->subject()->associate($subject);
                        $asset->save();
                    }

                    $this->sendEuklesEvent($subject, $asset);
                }
            } else {
                unlink($file->getRealPath());
            }

            $context = $this->getContext(Action::VIEW);
            $resource = $this->toResource($asset, $context);

            return $this->toResponse($resource);
        }

        return $this->getErrorMessage('No valid file provided.');
    }

    /**
     * @param Subject $subject
     * @param \App\Models\Asset $asset
     */
    public function sendEuklesEvent(Subject $subject, \App\Models\Asset $asset)
    {
        // no email set? dont send an event.
        if (!$subject->email) {
            return;
        }

        $euklesProperties = [
            'subject' => $subject,
            'asset' => $asset
        ];

        $euklesEvent = \Eukles::createEvent('photo.upload', $euklesProperties);

        // Track on ze eukles.
        \Eukles::trackEvent($euklesEvent);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function getUploadedFile(Request $request)
    {
        $file = $request->file('file');
        if ($file) {

            if (!$file->isValid()) {
                abort(400, 'File not valid: ' . $file->getErrorMessage());
            }

            return $file;
        }

        $content = $request->json();
        if ($content && $content->has('base64')) {

            $base64 = $content->get('base64');

            $tmpName = tempnam(storage_path(), 'image') . '.jpg';
            $this->base64_to_jpeg($base64, $tmpName);

            $name = date('YmdHis') . '.jpg';
            $file = new UploadedFile($tmpName, $name);

            return $file;
        }

        return null;
    }

    /**
     * @param $base64_string
     * @param $output_file
     * @return mixed
     */
    private function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' );

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }
}
