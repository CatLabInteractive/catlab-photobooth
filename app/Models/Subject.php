<?php


namespace App\Models;

use CatLab\Eukles\Client\Interfaces\EuklesModel;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Subject extends Model implements EuklesModel
{
    /**
     * @param $identifier
     * @return Subject
     */
    public static function getFromIdentifier($identifier)
    {
        $subject = self::query()
            ->where('identifier', '=', $identifier)
            ->first();

        if (!$subject) {
            $subject = new Subject();
            $subject->identifier = $identifier;
            $subject->save();
        }

        return $subject;
    }

    public function updateSubjectName()
    {
        if (!config('services.subjectNameResolver')) {
            return;
        }

        $resolveUrl = str_replace('{subjectId}', $this->identifier, config('services.subjectNameResolver.url'));

        $client = new Client();

        $data = $client->get($resolveUrl);
        $data = json_decode($data->getBody()->getContents(), true);
        if ($data && isset($data['name'])) {
            $this->name = $data['name'];
            $this->save();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function getEuklesId()
    {
        return $this->id;
    }

    public function getEuklesAttributes()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function getEuklesType()
    {
        return 'subject';
    }
}
