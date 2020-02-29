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

import {Eventable} from "../utils/Eventable";
import {Camera} from "./Camera";

/**
 *
 */
export class CameraService extends Eventable {

    private axios: any = null;

    private camera: Camera;

    /**
     *
     */
    constructor(
        axios: any
    ) {
        super();

        this.axios = axios;
        this.camera = new Camera();
    }

    /**
     * @param nfcService
     * @param nfcPassword
     */
    public connect(nfcService: string, nfcPassword: string) {
        this.camera.connect(nfcService, nfcPassword);


    }

    public takePicture(name: string) {
        return this.camera.takePicture(name);
    }

    public listPictures() {
        return this.camera.listPictures();
    }

}
