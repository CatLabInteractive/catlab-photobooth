<?php
/**
 * CatLab Photobooth - Online photobooth
 * Copyright (C) 2020 Thijs Van der Schaeghe
 * CatLab Interactive bvba, Gent, Belgium
 * http://www.catlab.eu/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

/**
 * Class EventPolicy
 * @package App\Policies
 */
class EventPolicy extends BasePolicy
{
    /**
     * @param User|null $user
     * @return bool
     */
    public function index(?User $user)
    {
        return true;
    }

    /**
     * @param $user
     * @return bool
     */
    public function create(?User $user)
    {
        return true;
    }

    public function view(?User $user, Event $event)
    {
        return $this->isMyEvent($user, $event);
    }

    public function edit(?User $user, Event $event)
    {
        return $this->isMyEvent($user, $event);
    }

    public function destroy(?User $user, Event $event)
    {
        return $this->isMyEvent($user, $event);
    }

    /**
     * @param User|null $user
     * @param Event $event
     * @return bool
     */
    public function orderSummary(?User $user, Event $event)
    {
        return $this->isMyEvent($user, $event);
    }
}
