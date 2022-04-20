<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Subject;
use Illuminate\Http\Request;

/**
 *
 */
class EventController extends Controller
{
    /**
     * @param Request $request
     * @param $event
     * @return string
     */
    public function checkinInfo(Request $request, Event $event)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return \Response::json([ 'checkin-info' => [
            'event' => $event->name
        ] ]);
    }

    /**
     * @param Request $request
     * @param $event
     * @return string
     */
    public function checkin(Request $request, Event $event)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $content = $request->json();
        if (!$content) {
            abort(400, 'Invalid content received');
        }

        $uid = $request->json('uid');
        $name = $request->json('name');
        $email = $request->json('email');

        if (!$uid || !$name || !$email) {
            abort(400, 'Not all information provided. Please provide uid, name and email');
        }

        $subject = Subject::getFromIdentifier($uid);
        $subject->name = $name;
        $subject->email = $email;
        $subject->save();

        return \Response::json([ 'success' => true ]);
    }
}
