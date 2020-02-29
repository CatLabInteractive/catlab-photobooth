<?php


namespace App\Http\Api\V1\ResourceDefinitions;

use CatLab\CentralStorage\Client\Models\Asset;
use CatLab\Charon\Models\ResourceDefinition;

/**
 * Class AssetResourceDefinition
 * @package App\Http\Api\V1\ResourceDefinitions
 */
class AssetResourceDefinition extends ResourceDefinition
{
    public function __construct()
    {
        parent::__construct(Asset::class);

        $this->identifier('id');

        $this->field('url')
            ->visible(true);
    }
}
