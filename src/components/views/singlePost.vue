<template>
    <article  v-if="currentPage" class="single-page" id="home-page">
        <h1 class="section-title">{{currentPage.title.rendered}}</h1>
        <div :key="currentPageID" class="std-content container">
            <wpImage v-if="currentPage.featured_image_src"  size="extra_large" :lazy="true" :data="currentPage.featured_image_src" :placeholder="true"  />
            <div class="html-content" role="content" v-html="currentPage.content.rendered"></div>
        </div>
        <moduleBuilder 
            v-if="currentPage.acf && currentPage.acf.modules"
            class="modules" 
            :modules="currentPage.acf.modules"
        />
    </article>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import wpImage from '@/components/elements/wpImage';
import moduleBuilder from '@/components/elements/moduleBuilder';

export default {
  name : 'single-page',
  props : ['data'],
  components: {
    wpImage,
    moduleBuilder,
  },
  data(){
      return{

      }
  },
    computed:{
      ...mapGetters([
          'currentPage',
          'currentPageID'
      ])
  },

}
</script>
