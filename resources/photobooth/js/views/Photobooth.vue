<!--
  - CatLab Photobooth - Online photobooth
  - Copyright (C) 2019 Thijs Van der Schaeghe
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

    <b-container fluid>

        <div style="width: 58vw; height: 40vw; background: black; padding: 1vw; margin: auto; box-sizing: border-box;">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" :src="image1" v-if="image1" />
                </div>

                <div class="col-md-6">
                    <img class="img-fluid" :src="image2" v-if="image2" />
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" :src="image3" v-if="image3" />
                </div>

                <div class="col-md-6">
                    <img class="img-fluid" :src="image4" v-if="image4" />
                </div>
            </div>
        </div>

        <div>
            <p>{{ message }}</p>
        </div>

    </b-container>

</template>

<script>

    import {EventService} from "../services/EventService";

    export default {
        mounted() {

            this.message = 'Scan drankkaart om te beginnen...';
            this.eventId = this.$route.params.id;

            this.$nfcService.on('card:connect', (data) => {
                var uid = data.uid;
                this.takePicture(uid);
            });

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
                        setTimeout(resolve, duration);
                    }
                );
            },

            takePicture: async function(name) {

                this.message = 'Taking picture...';

                this.image1 = null;
                this.image2 = null;
                this.image3 = null;
                this.image4 = null;

                console.log(this);

                this.wait(1);
                this.image1 = (await this.$cameraService.takePicture(name)).url;

                this.wait(1);
                this.image2 = (await this.$cameraService.takePicture(name)).url;

                this.wait(1);
                this.image3 = (await this.$cameraService.takePicture(name)).url;

                this.wait(1);
                this.image4 = (await this.$cameraService.takePicture(name)).url;

                this.message = 'Done!';

            }

        }
    }
</script>
