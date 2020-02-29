<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
