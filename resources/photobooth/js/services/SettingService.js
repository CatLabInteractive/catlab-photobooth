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
import localForage from "localforage";

export class SettingService {

    constructor() {



    }

    load() {
        return new Promise(
            function(resolve, reject) {

                localForage.getItem('settings', function(err, settings) {

                    if (!settings) {
                        settings = {};
                    }

                    this.terminalName = settings.terminalName || 'bar';
                    this.nfcServer = settings.nfcServer || null;
                    this.nfcPassword = settings.nfcPassword || null;

                    this.gphoto2Server = settings.gphoto2Server || null;
                    this.gphoto2Password = settings.gphoto2Password || null;

                    resolve();

                }.bind(this));

            }.bind(this)
        );
    }

    save() {
        return localForage.setItem('settings', {
            terminalName : this.terminalName,
            nfcServer: this.nfcServer,
            nfcPassword: this.nfcPassword,
            gphoto2Server: this.gphoto2Server,
            gphoto2Password: this.gphoto2Password
        });
    }

}
