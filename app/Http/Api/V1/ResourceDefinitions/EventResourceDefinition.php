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

namespace App\Http\Api\V1\ResourceDefinitions;

use App\Models\Event;
use CatLab\Charon\Models\ResourceDefinition;
use CatLab\Charon\Transformers\ScalarTransformer;
use CatLab\Requirements\Enums\PropertyType;

/**
 * Class EventResourceDefinition
 * @package App\Http\Api\V1\ResourceDefinitions
 */
class EventResourceDefinition extends ResourceDefinition
{
    /**
     * EventResourceDefinition constructor.
     * @throws \CatLab\Charon\Exceptions\InvalidScalarException
     */
    public function __construct()
    {
        parent::__construct(Event::class);

        $this
            ->identifier('id')
            ->int();

        $this->field('name')
            ->string()
            ->required()
            ->visible(true)
            ->writeable(true, true)
        ;

        $this->field('checkinUrl')
            ->display('checkin_url')
            ->visible(true, true);

        $this->relationship('organisation', OrganisationResourceDefinition::class)
            ->one()
            ->expandable()
            ->visible();
    }
}
