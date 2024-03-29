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

        <h1>Events</h1>
        <div class="text-center" v-if="!loaded">
            <b-spinner label="Loading data" />
        </div>

        <b-row>
            <b-col>
                <b-table striped hover :items="items" :fields="fields" v-if="loaded">

                    <template v-slot:cell(name)="row">
                        <router-link :to="{ name: 'photobooth', params: { id: row.item.id } }">{{ row.item.name }}</router-link>
                    </template>

                    <template v-slot:cell(checkin_url)="row">
                        <!--
                        <a :href="row.item.order_url" target="_blank" title="Client panel">
                            <pre>{{ row.item.order_token }}></pre>
                        </a>
                        -->
                        <a :href="row.item.checkin_url">Check-In URL</a>
                    </template>

                    <template v-slot:cell(actions)="row">

                        <b-button size="sm" class="" @click="edit(row.item, row.index)" title="Edit">
                            <i class="fas fa-edit"></i>
                            <span class="sr-only">Edit</span>
                        </b-button>

                        <b-button size="sm" @click="remove(row.item)" class="btn-danger" title="Remove">
                            <i class="fas fa-trash"></i>
                            <span class="sr-only">Delete</span>
                        </b-button>


                    </template>

                </b-table>
            </b-col>

            <b-col lg="4">
                <b-card :title="(model.id ? 'Edit event ID#' + model.id : 'New event')">
                    <form @submit.prevent="save">
                        <b-form-group label="Name">
                            <b-form-input type="text" v-model="model.name"></b-form-input>
                        </b-form-group>

                        <div>
                            <b-btn type="submit" variant="success">Save</b-btn>
                            <b-btn type="button" variant="light" @click="resetForm()">Reset</b-btn>

                            <b-alert v-if="saving" variant="none" show>Saving</b-alert>
                            <b-alert v-if="saved" variant="none" show="2">Saved</b-alert>
                        </div>
                    </form>
                </b-card>
            </b-col>
        </b-row>

    </b-container>

</template>

<script>

    import {EventService} from "../services/EventService";

    export default {
        mounted() {

            console.log(window.ORGANISATION_ID);
            this.service = new EventService(window.ORGANISATION_ID); // hacky hacky
            this.refreshEvents();

        },

        data() {
            return {
                loaded: false,
                saving: false,
                saved: false,
                toggling: 0,
                items: [],
                fields: [
                    {
                        key: 'id',
                        label: '#'
                    },
                    {
                        key: 'name',
                        label: 'Event',
                    },
                    {
                        key: 'checkin_url',
                        label: 'Check-In URL'
                    },
                    {
                        key: 'actions',
                        label: 'Actions',
                        class: 'text-right'
                    }
                ],
                model: {}
            }
        },

        methods: {

            async refreshEvents() {

                this.items = (await this.service.index()).items;
                this.loaded = true;

            },

            async save() {

                this.saving = true;
                if (this.model.id) {
                    await this.service.update(this.model.id, this.model);
                } else {
                    await this.service.create(this.model);
                }

                this.model = {};
                this.saving = false;
                this.saved = true;

                setTimeout(
                    () => {
                        this.saved = false;
                    },
                    2500
                );

                this.refreshEvents();

            },

            async edit(model, index) {

                this.model = Object.assign({}, model);

            },

            async remove(model) {

                if (confirm('Are you sure you want to remove this event?')) {
                    if (this.model.id === model.id) {
                        this.model = {};
                    }

                    await this.service.delete(model.id);
                    await this.refreshEvents();
                }

            },

            async toggleIsSelling(model) {

                this.toggling = model.id;
                model.is_selling = !model.is_selling;
                await this.service.update(model.id, model);
                this.toggling = null;

            },

            resetForm() {
                this.model = {};
            }
        }
    }
</script>
