/*
 * CatLab Photobooth - Online photobooth
 * Copyright (C) 2019 Thijs Van der Schaeghe
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

import {SettingService} from "./services/SettingService";

require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import BootstrapVue from "bootstrap-vue";
import moment from 'moment'
import AirbrakeClient from 'airbrake-js';

if (AIRBRAKE_CONFIG) {
    var airbrake = new AirbrakeClient(AIRBRAKE_CONFIG);
    Vue.config.errorHandler = function (err, vm, info) {
        airbrake.notify({
            error: err,
            params: {info: info}
        });
    };
}

Vue.use(VueRouter);
Vue.use(BootstrapVue);

import App from './views/App'
import Events from './views/Events'
import Settings from "./views/Settings";
import {OrganisationService} from "./services/OrganisationService";
import {CameraService} from "./camera/CameraService";
import {NfcReader} from "./nfccards/nfc/NfcReader";
import {OfflineStore} from "./nfccards/store/OfflineStore";
import {Logger} from "./nfccards/tools/Logger";
import Photobooth from "./views/Photobooth";

Vue.component(
    'logout-link',
    require('./components/LogoutLink.vue').default
);

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(value).format('DD/MM/YYYY hh:mm:ss');
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
const router = new VueRouter({
    mode: 'history',
    base: '/photobooth/',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Events
        },

        {
            path: '/events',
            name: 'events',
            component: Events,
        },

        {
            path: '/events/:id/photobooth',
            name: 'photobooth',
            component: Photobooth,
        },

        {
            path: '/settings',
            name: 'settings',
            component: Settings
        }
    ],
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Bootstrap card service
Vue.prototype.$settingService = new SettingService();
Vue.prototype.$organisationService = new OrganisationService();

Vue.prototype.$settingService.load()
    .then(
        function() {

            let offlineStore = new OfflineStore(window.ORGANISATION_ID);
            Vue.prototype.$nfcService = new NfcReader(offlineStore, new Logger());

            // Only try to connect to the nfc reader if config variables are set.
            if (Vue.prototype.$settingService.nfcServer) {
                Vue.prototype.$nfcService.connect(
                    Vue.prototype.$settingService.nfcServer,
                    Vue.prototype.$settingService.nfcPassword
                );
            }

            Vue.prototype.$cameraService = new CameraService(
                window.axios.create({
                    baseURL: '/api/v1',
                    json: true
                }),
            );
            if (Vue.prototype.$settingService.gphoto2Server) {
                Vue.prototype.$cameraService.connect(
                    Vue.prototype.$settingService.gphoto2Server,
                    Vue.prototype.$settingService.gphoto2Password
                );
            }

            // and now boot the app
            const app = new Vue({
                el: '#app',
                components: { App },
                router,
                methods: {
                    refreshToken: function() {
                        window.location.reload();
                    }
                }
            });

        }.bind(this)
    );
