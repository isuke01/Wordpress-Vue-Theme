<template>
    <div id="module-builder" class="elements">
        <div 
            v-if="modules"
            v-for="(modul, i) in modules"
            :key="i"
            :class="'module_'+modul.acf_fc_layout"
            >
            <htmlText  if="modul.acf_fc_layout === 'text_block'" :data="modul" />
        </div>
    </div>
</template>


<script>


import htmlText from '@/components/modules/htmlText';

export default {
    name: 'module-builder',
    props: {
        modules: {
          type: Array,
          required: true,
      },
    },
    components: {
      htmlText,
  },
}
</script>
