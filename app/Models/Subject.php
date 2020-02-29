<?php


namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


/**
 *
 */
class Subject extends Model
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
}
