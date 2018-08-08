<template>
    <router-link v-if="linkType !== 'custom'"
    :to="linkUrl"
    :class="classname"
    :target="target"
    :title="title"
    >
     <slot></slot>
    </router-link>

    <a v-else 
    :href="linkUrl"
    :class="classname"
    :target="target"
    :title="title"
    >
    <slot></slot>
    </a>
</template>

<script>
export default {
    name: 'wp-link',
    props: ['url', 'title', 'classname', 'target', 'type'],
	mounted: function() {
        
	},
	data() {
		return {
            homeUrl: window.WPVUE.home_url
		};
    },
    computed:{
        linkUrl(){
            if(this.url && this.url !== ''){
                return this.url.replace(this.homeUrl, '');
            }else return '';
        },
        linkType(){
            if(this.type){
                return this.type;
            }else{
                if( !this.url.includes(this.homeUrl) ){
                    return 'custom';
                }
            }
        }
    },
    methods:{
		
    },
};
</script>
