<template>
    <section class="container module_text">
        <div class="html-content" role="content" v-html="data.contentHTML"></div>
    </section>
</template>


<script>
export default {
    name: 'html-text',
    props : ['data'],
    
}
</script>

<style lang="scss">

</style>
