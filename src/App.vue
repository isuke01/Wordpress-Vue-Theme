<template>
	<div id="wp-app">
		<adminBar v-if="user.loggedIn && user.caps ==='administrator' && user.admin" />
		<themeHeader />
		<router-view/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

import objectFitImages from 'object-fit-images';

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
			'getPageByRoute'
		])
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
