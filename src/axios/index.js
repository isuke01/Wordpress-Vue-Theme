import axios from 'axios';


if(process.env.NODE_ENV !== 'development'){
	axios.defaults.headers.common['X-WP-Nonce'] = WPVUE.nonce;
}


export const REST = axios.create({
	baseURL: (process.env.NODE_ENV === 'development')? process.env.NODE_VUE_REST_URL : WPVUE.rest

});

export const API = axios.create({
	baseURL: (process.env.NODE_ENV === 'development')? process.env.NODE_VUE_API_URL : WPVUE.api,
});

