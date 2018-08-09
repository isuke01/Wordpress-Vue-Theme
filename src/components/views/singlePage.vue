<template>
    <article  v-if="currentPage && currentPage.protection.locked === false" class="single-page" id="home-page">
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
    <passProtected v-else-if="currentPage && currentPage.protection.locked === true"></passProtected>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import wpImage from '@/components/elements/wpImage';
import moduleBuilder from '@/components/elements/moduleBuilder';
import passProtected from '@/components/partials/passProtectedContent';

export default {
  name : 'single-page',
  props : ['data'],
  components: {
    wpImage,
    moduleBuilder,
    passProtected
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
