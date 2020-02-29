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


    <b-container fluid>

        <h1>Home</h1>
        <p> <a @click="uploadExistingPhotos()" class="btn btn-primary">Upload existing images</a></p>

        <h2>Images</h2>
        <table class="table">
            <tr v-for="(item, index) in images">
                <td>{{ item.file }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.url }}</td>
                <td>{{ item.status }}</td>
            </tr>
        </table>

    </b-container>

</template>

<script>

    import {EventService} from "../services/EventService";
    import {UploadService} from "../services/UploadService";

    export default {
        async mounted() {

            this.uploadService = new UploadService();

            this.uploadExistingPhotos();

        },

        beforeDestroy() {

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
                currentImage: 10,
                images: []
            }
        },

        methods: {

            uploadExistingPhotos: async function() {

                console.log('Upload existing photos.');
                let result = await this.$cameraService.listPictures(name);

                result.map((item) => {
                    item.status = 'queued';
                });

                this.images = result;
                console.log(result);

                this.uploadNext();
            },

            uploadNext: async function() {

                let image = this.images[this.currentImage];
                image.status = 'uploading';

                this.uploadService.uploadImage(image.url, image.name, image.name);

                this.currentImage ++;

            }
        }
    }
</script>
