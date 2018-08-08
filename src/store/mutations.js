
export default {
	frontPageLoadStatus(state, val) { state.frontPageLoaded = val },
	storePage(state, payload) {
		if (payload && payload[0]) payload.forEach( post => {
			if (!state.postsData[post.type]) state.postsData = { ...state.postsData, ...{ [post.type]: [] } };
			state.postsData[post.type].push(post);
		});
	},

	USER_LOGG_IN(state, payload) {
		state.userSession = payload;
		//wordpress_logged_in_b19045721adeaf90c2ea87489caf9b79
		//wordpress_b19045721adeaf90c2ea87489caf9b799
		//wordpress_b19045721adeaf90c2ea87489caf9b79
	}
}