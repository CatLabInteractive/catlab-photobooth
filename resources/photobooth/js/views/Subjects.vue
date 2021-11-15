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
        <h1>Subjects</h1>

        <div v-if="!card">
            <p>Scan card</p>
        </div>

        <div v-if="card">
            <h2>CARD {{ card.uid }}</h2>

            <div class="text-center" v-if="!loaded">
                <b-spinner label="Loading data" />
            </div>

            <div v-if="model">
                <form @submit.prevent="save">
                    <b-form-group label="Name">
                        <b-form-input type="text" v-model="model.name"></b-form-input>
                    </b-form-group>

                    <b-form-group label="Email">
                        <b-form-input type="text" v-model="model.email"></b-form-input>
                    </b-form-group>

                    <div>
                        <b-btn type="submit" variant="success">Save</b-btn>

                        <b-alert v-if="saving" variant="none" show>Saving</b-alert>
                        <b-alert v-if="saved" variant="none" show="2">Saved</b-alert>
                    </div>
                </form>
            </div>
        </div>

    </b-container>

</template>

<script>

    import {EventService} from "../services/EventService";

    export default {
        async mounted() {

            this.$nfcService.on('card:connect', async (data) => {
                this.card = data;

                this.loaded = false;
                this.model = await this.$subjectService.get(this.card.uid);
                console.log(this.model);

                this.loaded = true;
            });

            this.$nfcService.on('card:disconnect', async (data) => {
                this.card = null;
                this.loaded = false;
                this.model = null;
            });
        },

        beforeDestroy() {
            this.$nfcService.off('card:connect');
            this.$nfcService.off('card:disconnect');
        },

        data() {
            return {
                loaded: false,
                card: null,
                model: null,
                saving: false,
                saved: false
            }
        },

        methods: {

            async save() {

                this.saving = true;
                this.model = await this.$subjectService.update(this.model.id, this.model);

                this.saving = false;
                this.saved = true;

                setTimeout(
                    () => {
                        this.saved = false;
                    },
                    2500
                );

            }

        }
    }
</script>
