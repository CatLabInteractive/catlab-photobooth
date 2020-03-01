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

import * as io from 'socket.io-client';
import * as CryptoJS from 'crypto-js';

// @ts-ignore
import * as ndef from 'ndef';
import {Eventable} from "../utils/Eventable";

/**
 *
 */
export class Camera extends Eventable {

    private url: string = '';

    private socket: any;

    private password: string = '';

    private executeHandshake: boolean;

    private nfcReaderPassword: string;

    constructor(

    ) {
        super();
        this.executeHandshake = false;
        this.nfcReaderPassword = '';
    }

    connect(url: string, password: string, handleHandshake = true) {

        this.url = url;

        this.socket = io(url);
        this.executeHandshake = handleHandshake;

        this.nfcReaderPassword = password;

        this.socket.on('connect', async () => {
            await this.handshake();
        });

        this.socket.on('disconnect', () => {
            this.trigger('connection:change', false);
            this.reconnect();
        });

        this.socket.on('reconnect', async () => {
            await this.handshake();
        });
    }

    private async handshake()
    {
        this.socket.emit('hello', { password: this.nfcReaderPassword }, (response: any) => {
            console.log('Response from camera service hello: ', response);
            if (response.success) {
                this.trigger('connection:change', true);
            }
        });
    }

    /**
     * @param password
     */
    public setPassword(password: string) {
        this.password = password;
        return this;
    }

    /**
     * Take a picture.
     * @param name
     */
    public takePicture(name: string) {
        return new Promise(
            (resolve, reject) => {
                this.socket.emit(
                    'photo:takePicture',
                    {
                        name: name,
                        keep: true
                    },
                    (data: any) => {
                        if (typeof(data.file) !== 'undefined') {
                            data.url = this.url + data.file;
                        }
                        resolve(data);
                    }
                );
            }
        );
    }

    /**
     * Take a picture.
     * @param name
     */
    public listPictures() {
        return new Promise(
            (resolve, reject) => {
                this.socket.emit('photo:list', {}, (data: any) => {
                    resolve(data.files.map((item: any) => {
                        if (item.file) {
                            item.url = this.url + item.file;
                        }
                        return item;
                    }));
                });
            }
        );
    }

    /**
     * Take a picture.
     * @param name
     */
    public removePicture(name: string) {
        return new Promise(
            (resolve, reject) => {
                this.socket.emit('photo:remove', {
                    file: name
                }, (data: any) => {
                    if (typeof(data.error) === 'undefined') {
                        resolve(true);
                    } else {
                        reject(data.error);
                    }
                });
            }
        );
    }

    /**
     *
     */
    private reconnect() {

    }

}
