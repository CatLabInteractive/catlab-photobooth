<?php

return [

    /**
     * Asset server domain
     */
    'server' => env('CENTRALSTORAGE_SERVER'),

    /**
     * Client key
     */
    'key' => env('CENTRALSTORAGE_KEY'),

    /**
     * Client secret
     */
    'secret' => env('CENTRALSTORAGE_SECRET'),

    /**
     * Front url (for CDN) that will be sent to the end users
     */
    'front' => env('CENTRALSTORAGE_FRONT', null),

    /**
     * Version (to break caching)
     */
    'version' => null,

    'model' => \App\Models\Asset::class

];
