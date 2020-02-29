<?php


namespace App\Models;


class Asset extends \CatLab\CentralStorage\Client\Models\Asset
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
