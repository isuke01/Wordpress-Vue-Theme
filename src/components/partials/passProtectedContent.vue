<template>
    <div>
        PAGE IS PASSWORD PROTECTED
        <input type="password" v-model="pass">
        <button @click="unlockPage">UNLOCK</button>
    </div>
</template>


<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    name: "pass-protection",
    data(){
        return{
            pass: null,
        }
    },
	computed:{
		...mapGetters([
			'currentPage',
            'currentPageID',
            'currentPostType'
		]),
	},
    methods:{
        ...mapActions([
          'loadPage'
        ]),
        unlockPage(){
            console.log('Unlock: ', this.pass)
            const payload = {
                id: this.currentPageID,
                postType: this.currentPostType,
                attrs: {password: this.pass}
            }
            this.loadPage(payload)
        }
    }
}
</script>
