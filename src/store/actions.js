import { REST, API } from './../axios/';

export default {

	getFrontPage( { commit } ) {
		const frontPage = WPVUE.front_page;
		return REST.get('wp/v2/pages/' + frontPage, {
			params: {nocache: new Date().getTime()}
		})
		.then((res) => {
			if (res.data) {
				let data = [];
				data[0] = res.data;
				commit('frontPageLoadStatus', true);					
				commit('storePage', data);					
			}
		} )
		.catch( ( res ) => {
			console.log( `Something went wrong (getFrontPage): ${ res }` );
		});
	},

	/**
	 * 
	 * @param {*} param0 
	 * @param {mixed} payload = can be only page ID if load pages, but if other post types or attr it must be an object that contain page ID, attrs, postType 
	 */
	async loadPage({ commit, getters }, payload) {
		let stored = null;
		let pageID = 1;
		let postType = 'pages';
		let attrs = null;

		if (typeof payload === "object") {
			pageID = Number(payload.id)
			postType = payload.postType
			attrs = payload.attrs
			
			postType = (postType === 'page') ? 'pages' : postType;  // in rest endpoint for pages is / pages but it return post_type page :/	
			postType = (postType === 'post') ? 'posts' : postType; // same as above but for posts

		} else {
			pageID = Number(payload)
		}
		
		const storeData = await new Promise(resolve => {
			if (getters.allLoadedPages && getters.allLoadedPages[postType] && getters.allLoadedPages[postType].length) { 
				stored = getters.allLoadedPages[postType].find(it => (pageUrl === it.link.replace(/\/$/, "") ));	
			}
			resolve(stored);
		});
				
		/** End if find this page in store */
		if (storeData && storeData.id) return storeData;

		return REST.get('wp/v2/'+postType+'/' + pageID, {
			params: {
				...attrs,
				nocache: new Date().getTime()
			}
		})
		.then((res) => {
			if (res.data) {
				let data = [];
				data[0] = res.data;
				commit('storePage', data);
				return res.data;
				
			} else {
				console.log('GO 404')
				//window.location.pathname = '/404'				
				return {'status' : '404'};
				
			}
		} )
		.catch( ( res ) => {
			console.log( `Something went wrong (loadPage): ${ res }` );
		});
	},
	/**
	 * @param {Object} router  { route VUEX  }
	 */
	async getPageByRoute({ commit, getters }, router) {
		if (router.name === 'frontPage') return false; // should return front page!
		if (router.name === '404') return false; // should return front page!
		if (!router.meta || !router.meta.type || router.meta.type !== 'single') return false; // it is Archive or something
		const postType = router.meta.post_type; 
		let PTypeForRest = (postType === 'page')? 'pages' : postType;  // in rest endpoint for pages is / pages but it return post_type page :/	
			PTypeForRest = (postType === 'post') ? 'posts' : PTypeForRest; // same as above but for posts
		const slug = router.params.name.replace(/\/$/, ""); 
		const pagePath = router.fullPath;
		const pageUrl = (WPVUE.home_url + pagePath).replace(/\/$/, "");

		let loadedData = null;
		let stored = null;
		
		const storeData = await new Promise(resolve => {
			if (getters.allLoadedPages && getters.allLoadedPages[postType] && getters.allLoadedPages[postType].length) { 
				stored = getters.allLoadedPages[postType].find(it => (pageUrl === it.link.replace(/\/$/, "") ));	
			}
			resolve(stored);
		});

		/**
		 * End if find this page in store
		 */
		if (storeData && storeData.id) return storeData;

		
		const loadedPage = await REST.get('wp/v2/' + PTypeForRest, {
			params: {slug: slug, nocache: new Date().getTime()}
		})
		.then((res) => {
			if(res.data.length){
				loadedData = res.data.filter(item => ( pageUrl === item.link.replace(/\/$/, "") ) );
			}
			if (loadedData && loadedData.length >= 1 && res.data.length) {
				commit('storePage', loadedData);
				return loadedData[0];
			} else {
				console.log('IS 404')
				//window.location.pathname = '/404'
				
				return {'status' : '404'};
				//commit('storePage', loadedData);	 404												
			}
		})
		.catch((res) => {
			console.log(`Something went wrong (getPageByRoute) : ${ res }`,);
		});

		return loadedPage;
	}
}