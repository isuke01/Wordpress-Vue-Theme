<template>
	<div id="wp-app">
		<adminBar v-if="user.loggedIn && user.caps ==='administrator' && user.admin" />
		<themeHeader />
		<router-view/>
		<hr/>
		<label>LOAD PAGE BY ID:</label>
		<input type="number" v-model="pageID">
		<button @click="loadPageBTN">LOAD by ID</button>

		<hr/>
		<label>LOAD PAGE BY SLUG:</label>
		<input type="text" v-model="pageSlug">
		<button @click="loadPageBTNSlug">LOAD by Slug</button>
		<hr/>

		<br/>DEBUG:<pre v-html="debug"></pre>
	</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

//import objectFitImages from 'object-fit-images';

import themeHeader from '@/components/elements/themeHeader';
const adminBar = () => import('@/components/elements/adminBar');


export default {
	name: 'App',
	components:{
		themeHeader,
		adminBar
	},
	data(){
		return {
			pageID: 5,
			pageSlug: 'privatesomepage',
			debug: null,
			user: WPVUE.user
		}
	},
	computed:{
		...mapGetters([
			'currentPage',
			'currentPageID'
		]),
	},
	methods:{
		...mapActions([
			'getFrontPage',
			'getPageByRoute',
			'loadPage'
		]),
		loadPageBTN(){
			const VM = this;
			console.warn('START LOADING PAGE BY ID: ', VM.pageID)
			VM.loadPage(VM.pageID).then( res => {
				VM.debug = res;
			});
		},
		loadPageBTNSlug(){
			const VM = this;
			console.warn('START LOADING BY SLUG: ', VM.pageSlug);
			const payload = {
                postType: 'page',
                attrs: {slug: VM.pageSlug}
            }
			VM.loadPage(payload).then( res => {
				VM.debug = res;
			});
		}
	},
	watch:{
		'$route'(to, from){
			console.log('Route To: ', to);
			this.getPageByRoute(to);
		}
	},
	beforeMount(){
		this.getFrontPage();
	},
	mounted(){
		const VM = this;
		const homeUrl = WPVUE.base_url;

	}
}
</script>

<style lang="scss">
	@import "./assets/scss/style.scss";
	@import "../node_modules/photoswipe/dist/photoswipe.css";
	@import "../node_modules/photoswipe/dist/default-skin/default-skin.css";
</style>
