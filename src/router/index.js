import Vue from 'vue'
import Router from 'vue-router'

import frontPage from '@/components/views/frontPage';
//Async loaded component
const singlePage = () 	=> import('@/components/views/singlePage');
const singlePost = () 	=> import('@/components/views/singlePost');
const archivePost = () 	=> import('@/components/views/archivePosts');
const page404 = () 		=> import('@/components/views/404');

Vue.use(Router)

export default new Router({
  	mode: 'history',
  	routes: [
    {
      	path: '/',
      	name: 'frontPage',
      	component: frontPage
	},
	
	{
		path: '/404', name: 'notFound', component: page404,
		meta: { post_type: null, type: '404', title: 'Page not found' }
	},
	{
		path: '/' + WPVUE.post_page_slug, name: 'archivePost', component: archivePost,
		meta: { post_type: 'post', type: 'archive', title: 'Aktuelt' },
	},
	{
		path: '/' + WPVUE.post_page_slug + '/:name', name: 'singlePost', component: singlePost,
		meta: { post_type: 'post', type: 'single', title: 'Aktuelt' },
	},
	{
		path: '/:name',
		name: 'singlePage',
			component: singlePage,
			meta: { post_type: 'page', type: 'single' },
			children: [{
			path: ':name',
				component: singlePage,
				meta: { post_type: 'page', type: 'single' },
				children: [
					{
						path: ':name',
						meta: { post_type: 'page', type: 'single' },
						component: singlePage
					},
				]
			},]
		},


  
    // { path: '/404', name: 'notFound', component: page404 },
		/*{ path: '/blog', redirect: '/' + rtwp.post_page_slug },	*/			
		
		// { path: '/' + rtwp.post_page_slug + '/:page(\\d+)?', name: 'home', component: posts },
		// { path: '/' + rtwp.post_page_slug + '/:name', name: 'post', component: post },
		
		// { path: '/' + rtwp.activity_page_slug + '/:page(\\d+)?', name: 'activities', component: activitiesArchive, props: true },		
		// { path: '/' + rtwp.activity_page_slug + '/:name', name: 'activity', component: activity },
		
		/* { path: '/category/:name', name: 'cat', component: category },
		{ path: '/tag/:name', name: 'tag', component: tag }, */

		// { path: '/:name', name: 'page', component: page },
		// { path: '/:lvl0/:name', name: 'page_v1', component: page },
		// { path: '/:lvl0/:lvl1/:name', name: 'page_v2', component: page },

		// { path: '/', name: 'front', component: front /*, redirect: '/blog'  */ },
		// { path: '*', redirect: '/404' },			
  ]
})
