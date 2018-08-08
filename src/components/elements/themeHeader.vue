<template>
    <header role="page-head" id="masterhead" class="page-header">
        <wpNavi location="primary-menu" ></wpNavi>
    </header>
</template>

<script>
const wpNavi = () => import('@/components/elements/wpNavi');

export default {
      name: 'theme-header',
      components: {
          wpNavi
      }
}
</script>


<style lang="sass" scoped>

</style>
