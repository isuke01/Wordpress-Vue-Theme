<template>
    <figure :class="{ 'has-caption' : data.caption, 'background-image' : background }" class="image-hold">
        <div  :class="{ 'useholder' : placeholder, 'hero-thumb' : hero }" class="wp-image lazyloading" >
        <img
            v-if="!background"
            :src="(lazy)? imagePlaceholder : imageUrl"
            :srcset="(lazy)? srcSet : '' "
            :data-src="imageUrl"
            :data-srcset="srcSet"
            :data-alt="data.alt"
            :class="{'lazyload' : lazy }" />
        <div v-else class="bg-image" :class="{'lazyload' : lazy }" :data-bg="imageUrl" ></div>
        <div class="lazy-placeholder" :style="'background-image: url('+imagePlaceholder+')'" v-if="placeholder" ></div>
        <slot class="slot-in" name="in"></slot>        
        </div>
        <figcaption v-if="(data.caption && caption) || (data.title && title)" role="description">
            <span v-if="title" class="title">{{ data.title }}</span>
            <span v-if="caption" class="caption">{{data.caption}}</span>
        </figcaption>
        <slot class="slot-out" name="out"></slot>
    </figure>

</template>

<script>


export default {
    name: 'wp-image',
    props: { 
        data: Object, 
        size: { type: String, default: 'medium'},
        caption: { type: Boolean, default: false },
        title: { type: Boolean, default: false },
        lazy: {
            type: Boolean, default: false
        },
        placeholder: {
            type: Boolean, default: false
        },
        background: {
            type: Boolean, default: false
        },
        hero: {
            type: Boolean, default: false
        }
    },
	mounted: function() {
        const VM = this;
        VM.imageUrl = VM.data.url
        if(VM.size && VM.size != '' ){            
            if(VM.data.sizes[VM.size]){
                VM.imageUrl = VM.data.sizes[VM.size]['url'];
            }
        }
	},
	data() {
		return {
            imageUrl: '',
		};
    },
    computed: {
        imagePlaceholder(){
                if(this.data.sizes.min){
                    return this.data.sizes.min['url'];
                }else{
                    if(this.data.sizes.thumbnail){
                        return this.data.sizes.thumbnail['url'];
                    }else{
                        return this.data.url;

                    }
                }
        },
        srcSet(){
            const VM = this;            
            let srcset = '';
            Object.keys(VM.data.sizes).map( key =>{
                    srcset += VM.data.sizes[key]['url']+' '+VM.data.sizes[key]['width']+'w, ';
            })
            return srcset;
        }
    },
};
</script>


<style lang="scss" scope>
.lazyloading{
    position: relative;
    overflow: hidden;
    img{
        //display: none;
        &.lazyload{
            //filter: blur(5px);   
        }
    }
    &.useholder{
        .lazyloaded{
            ~.lazy-placeholder{
                opacity: 0;
            }
        }
        
    }
    .lazy-placeholder{
        position: absolute;
        top: -8px;
        bottom: -8px;
        left: -8px;
        right: -8px;
        z-index: 20;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        filter: blur(8px);
        transition: opacity 0.2s ease-in-out;
        will-change: opacity;
        opacity: 1;
    }
}
</style>