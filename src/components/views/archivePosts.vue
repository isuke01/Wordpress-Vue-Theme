<template>
    <article role="home-page" id="home-page">
        <h1 class="section-title">Aktualności</h1>
    </article>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'


export default {
  name : 'archive-diet',
  props : ['data'],
  components: {
  },
  data(){
      return{

      }
  },
  methods:{
      ...mapActions([
          'loadPageData'
      ])
  }
}
</script>
