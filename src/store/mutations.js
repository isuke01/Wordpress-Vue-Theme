import _uniqBy from 'lodash.uniqby';
import _orderby from 'lodash.orderby';

export default {
	frontPageLoadStatus(state, val) { state.frontPageLoaded = val },
	storePage(state, payload) {
		if (payload && payload[0]) {
			payload.forEach(post => {
				if (!state.postsData[post.type]) state.postsData = { ...state.postsData, ...{ [post.type]: [] } };
				if (state.postsData[post.type]) {
					// replace freshly laoded page if it is same as previous one, eg after enter password
					state.postsData[post.type].filter( (item, key) => {
						if (post.id === item.id) { 
							state.postsData[post.type][key] = post
						}
					})
				}
				state.postsData[post.type].push(post);

			});
			
			
			let filtered = _uniqBy(state.postsData[payload[0].type], 'id');
            //filtered = _orderby(filtered, 'date', 'desc');
			
			if(filtered) state.postsData[payload[0].type] = filtered;
		}	
	},

	USER_LOGG_IN(state, payload) {
		state.userSession = payload;
		//wordpress_logged_in_b19045721adeaf90c2ea87489caf9b79
		//wordpress_b19045721adeaf90c2ea87489caf9b799
		//wordpress_b19045721adeaf90c2ea87489caf9b79
	}
}