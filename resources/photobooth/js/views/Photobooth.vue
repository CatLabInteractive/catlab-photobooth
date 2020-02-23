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

            <div class="photobooth-compilation-content">

                <div class="image">
                    <img :src="image1" v-if="image1" />
                </div>

                <div class="image">
                    <img :src="image2" v-if="image2" />
                </div>

                <div class="image">
                    <img :src="image3" v-if="image3" />
                </div>

                <div class="image">
                    <img :src="image4" v-if="image4" />
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
                image1: null,
                image2: null,
                image3: null,
                image4: null,
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

                this.message = message + '<br />6';
                await this.wait(1);

                this.message = message + '<br />5';
                await this.wait(1);

                this.message = message + '<br />4';
                await this.wait(1);

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


                this.image1 = null;
                this.image2 = null;
                this.image3 = null;
                this.image4 = null;
                this.fullscreenImage = null;

                this.image1 = await this.takePictureCommand('Groepsfoto!', 'Smile!', name + ' normaal');
                this.fullscreenImage = this.image1;
                await this.wait(2);

                this.image2 = await this.takePictureCommand('En nu met gekke bekken!', 'Gekke bek!', name + ' gek');
                this.fullscreenImage = this.image2;
                await this.wait(2);

                this.image3 = await this.takePictureCommand('En nu kei serieus', 'Serieuzer!', name + ' serieus');
                this.fullscreenImage = this.image3;
                await this.wait(2);

                this.image4 = await this.takePictureCommand('Avada Kedavra!', 'Speel dood', name + ' dood');
                this.fullscreenImage = this.image4;
                await this.wait(2);

                this.message = null;
                this.fullscreenImage = null;

                this.isTakingPictures = false;

                await this.wait(15);
                this.message = 'Scan drankkaart';

            }

        }
    }
</script>
