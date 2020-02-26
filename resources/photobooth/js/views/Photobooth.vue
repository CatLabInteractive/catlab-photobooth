<!--
  - CatLab Photobooth - Online photobooth
  - Copyright (C) 2020 Thijs Van der Schaeghe
  - CatLab Interactive bvba, Gent, Belgium
  - http://www.catlab.eu/
  -
  - This program is free software; you can redistribute it and/or modify
  - it under the terms of the GNU General Public License as published by
  - the Free Software Foundation; either version 3 of the License, or
  - (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU General Public License for more details.
  -
  - You should have received a copy of the GNU General Public License along
  - with this program; if not, write to the Free Software Foundation, Inc.,
  - 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
  -->

<template>


    <div class="photobooth-compilation-container">
        <div class="photobooth-compilation">

            <div class="fullscreenImage" v-if="fullscreenImage">
                <img :src="fullscreenImage" />
            </div>

            <div class="message" v-if="message">
                <div>
                    <p v-html="message"></p>
                </div>
            </div>

            <div class="photobooth-compilation-content" v-if="showImages">

                <div class="image" v-for="(item, index) in images">
                    <img :src="item" />
                </div>

            </div>
        </div>
    </div>

</template>

<script>

    import {EventService} from "../services/EventService";

    export default {
        async mounted() {

            this.message = 'Scan drankkaart';
            this.eventId = this.$route.params.id;

            this.isTakingPictures = false;

            this.$nfcService.on('card:connect', async (data) => {

                if (this.isTakingPictures) {
                    return;
                }

                this.isTakingPictures = true;

                var uid = data.uid;
                await this.takePictures(uid);
                this.isTakingPictures = false;
            });

            document.body.className = 'fullscreen';

            // and take our first picture.
            this.fullscreenImage = await this.takePicture('bootup');
        },

        beforeDestroy() {
            document.body.className = '';
            this.$nfcService.off('card:connect');
        },

        watch: {
            '$route' (to, from) {
                // react to route changes...
                this.eventId = to.params.id;
                this.refresh();
            }
        },

        data() {
            return {
                fullscreenImage: null,
                images: [],
                showImages: true,
                eventId: null,
                message: 'Please wait...'
            }
        },

        methods: {

            wait: async function(duration) {
                return new Promise(
                    (resolve) => {
                        setTimeout(resolve, duration * 1000);
                    }
                );
            },

            countdown: async function(message) {

                this.message = message + '<br />3';
                await this.wait(1);

                this.message = message + '<br />2';
                await this.wait(1);

                this.message = message + '<br />1';
                await this.wait(1);
            },

            takePictureCommand: async function(commandText, actionText, filename) {
                await this.countdown(commandText);

                this.message = actionText;

                let result = await this.takePicture(filename);
                this.message = '';

                return result;
            },

            takePicture: async function(name) {
                let result = await this.$cameraService.takePicture(name);

                console.log(result);

                if (!result.url) {
                    this.message = 'Error: ' + result.error;
                    await this.wait(3);

                    return '';
                }
                return result.url;
            },

            takePictures: async function(name) {

                this.showImages = false;
                this.fullscreenImage = null;

                this.fullscreenImage = await this.takePictureCommand('Foto!', 'Cheese!', name);
                this.images.unshift(this.fullscreenImage);

                while (this.images.length > 4) {
                    this.images.pop();
                }

                await this.wait(5);
                this.message = 'Scan drankkaart';
                this.fullscreenImage = null;
                this.showImages = true;

            }

        }
    }
</script>
