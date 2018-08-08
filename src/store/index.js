import Vue from 'vue';
import Vuex from 'vuex';
//import { vuex } from '../app';

import getters from './getters';
import actions from './actions';
import mutations from './mutations';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export const store = new Vuex.Store({
	state: {
		postsData: {
			posts: [],
		},

		isFrontPage: false,

		title: '',
		pageLoaded: false,
		frontPageLoaded: false,
		//user
		userSession: {
			user: false,
			nonce: false,
			loggedIn: false,
		},
		
	},
	getters,
	mutations,
	actions
});
