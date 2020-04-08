import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        loading: false,
        editing: false,
        groups: []
    },
    mutations: {
        setLoading(state, loading) {
            state.loading = loading
        },
        setEditing(state, editing) {
            state.editing = editing
        },
        setGroups(state, groups) {
            state.groups = groups
        }
    },
    getters: {
        loading: state => {
            return state.loading
        },
        editing: state => {
            return state.editing
        },
        groups: state => {
            return state.groups
        }
    }
})
