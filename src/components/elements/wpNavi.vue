<template>
      <ul class="wp-navi" :class="menuLocation">
        <li v-for="(item, i) in menus" :key="i">
            <wpLink
            :url="item.url"
            :title="item.attr+' '+item.description"
            :classname="item.classes+' type-'+item.object"
            :target="item.target"
            :type="item.type"
            >{{item.title}}</wpLink>
        </li>
      </ul> 
</template>

<script>
import wpLink from '@/components/elements/wpLink';

export default {
    name: 'wp-navi',
    props: ['location'],
    components:{
        wpLink
    },
	mounted: function() {
		this.getMenu();
	},
	data() {
		return {
            menus: [],
		};
    },
    methods:{
		getMenu: function() {
			const vm = this;
			vm.$rest.get( 'wp-api-menus/v2/menu-locations/'+vm.menuLocation )
			.then( ( res ) => {
				vm.menus = res.data;
			} )
			.catch( ( res ) => {
				console.log( `Something went wrong during loading menu: `, vm.menuLocation );
			} );
		},
		getUrlName: function( url ) {
			url = url.replace(/\/$/, '');
            const array = url.split( '/' );
            if(array.length === 3 ){
                return '/';
            }else{
			    return array[ array.length - 1 ];
            }

		},
    },
    computed: {
        menuLocation(){
            if(this.location){
                return this.location;
            }else{
                return 'primary-menu';
            }

        }
    },
};
</script>
