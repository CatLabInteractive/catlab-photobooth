<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class AssetController
 * @package App\Http\Controllers\Admin
 */
class AssetController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        if ($file) {

            if (!$file->isValid()) {
                abort(400, 'File not valid: ' . $file->getErrorMessage());
            }

            $asset = \CentralStorage::store($file);
            $asset->save();
        }

        return redirect(action('Admin\AssetController@index'));
    }
}
