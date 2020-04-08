/**
 * @author SJ
 * @date   2019.12.23
 */

import 'babel-polyfill'
import Vue from 'vue'
import Vuetify from 'vuetify'
import axios from 'axios'
import VContentManager from '../vue/ContentManager'
import VuetifyDraggableTreeview from 'vuetify-draggable-treeview'
import {store} from '../vue/store/store'

Vue.use(Vuetify)
Vue.use(VuetifyDraggableTreeview)
Vue.prototype.$axios = axios

window.app = new Vue({
    el: '#content-manager',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi'
        }
    }),
    store,
    components: {
        VContentManager,
        VuetifyDraggableTreeview
    }
})
