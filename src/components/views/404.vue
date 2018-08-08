<template>
    <article   role="404" id="page404">
            <div class="container page_404">
                    <h1 class="section-title">404</h1>
                    <h2 class="section-title">Sorry page not found</h2>
                    <p>There is no page to see :(</p>
                    <router-link to="/" class="button button--primary">Home</router-link>

            </div>
    </article>
</template>

<script>

export default {
  name : 'page404',
  components: {
      
  },
  beforeMount(){
      //jQuery('html').addClass('is_404');
  },
  beforeDestroy(){
      
      //jQuery('html').removeClass('is_404');
  },
  mounted(){
      console.log('mount')
  },

  computed:{
     
  },
  methods:{
      
  }
}
</script>

<style lang="scss">

    html.is_404{
        background: #c7e1d1;
    }
    .page_404{
        text-align: center;
        padding-top: 60px;
        padding-bottom: 85px;

    }

</style>
