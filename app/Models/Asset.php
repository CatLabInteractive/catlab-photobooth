<?php


namespace App\Models;


use CatLab\Eukles\Client\Interfaces\EuklesModel;

class Asset extends \CatLab\CentralStorage\Client\Models\Asset implements EuklesModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getEuklesId()
    {
        return $this->id;
    }

    public function getEuklesAttributes()
    {
        return [
            'id' => $this->id,
            'url' => $this->getUrl()
        ];
    }

    public function getEuklesType()
    {
        return 'asset';
    }
}
