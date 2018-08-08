export default {
    getUserData: state => state.userSession,
    allLoadedPages: state => state.postsData,
    currentPostType: state => (state.route.meta && state.route.meta.post_type)? state.route.meta.post_type : null,
    currentPage: (state, getters, rootState) => { 
        if (state.postsData && state.postsData[getters.currentPostType]) {
            //console.log(state.route.fullPath);
            const pagePath = state.route.fullPath;
            const pageUrl = WPVUE.home_url +''+ pagePath
            return state.postsData[getters.currentPostType].find(it => {
                return pageUrl.replace(/\/$/, "") === it.link.replace(/\/$/, "")
            })
        } else {
            return null;
        }
    },
    currentPageID: (state, getters) => { 
        if(getters.currentPage) return getters.currentPage.id
        if (state.route.name === 'frontPage') return Number(WPVUE.front_page)
        else return null
    },
}