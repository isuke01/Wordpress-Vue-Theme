<template>
    <section role="hadmin-bar" id="adminBar" :class="{ 'open' : show}">
        <a class="admin-link bar-button" :href="adminUrl" target="_blank" >
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 447.674 447.674" style="enable-background:new 0 0 447.674 447.674;" xml:space="preserve"><g><path style="fill:#fff;" d="M134.289,138.16h-24.722l67.399,190.521l37.732-107.825l-29.254-82.696H159.36v-18.154h115.508 v18.154h-27.049l67.398,190.521l24.227-69.234c31.781-88.702-26.048-116.333-26.048-136.129s16.048-35.843,35.843-35.843 c1.071,0,2.111,0.058,3.13,0.153c-33.541-31.663-78.768-51.08-128.534-51.08c-65.027,0-122.306,33.146-155.884,83.458h66.336 v18.154L134.289,138.16L134.289,138.16z"/><path style="fill:#fff;" d="M36.548,223.837c0,71.704,40.302,133.986,99.483,165.458l-84.52-238.919 C41.883,172.932,36.548,197.761,36.548,223.837z"/><path style="fill:#fff;" d="M386.833,131.547c2.679,15.774,1.868,33.503-2.243,51.301h0.745l-2.832,8.092l0,0 c-1.678,5.843-3.791,11.82-6.191,17.693l-64.444,180.541c59.057-31.51,99.256-93.725,99.256-165.338 C411.124,190.279,402.29,158.788,386.833,131.547z"/><path style="fill:#fff;" d="M166.075,402.033c18.195,5.894,37.603,9.091,57.762,9.091c19.228,0,37.777-2.902,55.239-8.285 l-54.784-154.862L166.075,402.033z"/><path style="fill:#fff;" d="M382.113,65.56C339.836,23.283,283.625,0,223.836,0S107.837,23.283,65.56,65.56S0,164.047,0,223.837 c0,59.789,23.283,115.999,65.56,158.276s98.488,65.56,158.277,65.56s115.999-23.283,158.277-65.56 c42.277-42.277,65.56-98.488,65.56-158.276C447.673,164.047,424.39,107.837,382.113,65.56z M223.836,431.883 c-114.717,0-208.046-93.329-208.046-208.046S109.119,15.79,223.836,15.79s208.046,93.33,208.046,208.047 S338.554,431.883,223.836,431.883z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
            <span>Admin Panel</span>
        </a>
        <a v-if="currentPageID" class="bar-button" :href="adminUrl+'/post.php?post='+currentPageID+'&action=edit'" target="_blank" >Edytuj </a>
        
        <div class="admin-dropdown">
            <button class="bar-button" arial-label="show-add-button">Dodaj Nowy</button>
            <ul aria-visible="false" class="dropdown">
                <li v-if="type.show_bar" v-for="(type, i) in postTypes" :key="i" >
                    <a target="_blank" :href="adminUrl+'post-new.php?post_type='+type.name">+ {{type.labels.name_admin_bar}}</a>
                </li>
            </ul>
        </div>

        <a class="user-edit bar-button" :href="adminUrl+'/profile.php'" target="_blank" >
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 482.9 482.9" style="enable-background:new 0 0 482.9 482.9;" xml:space="preserve" width="512px" height="512px"><g><g><path d="M239.7,260.2c0.5,0,1,0,1.6,0c0.2,0,0.4,0,0.6,0c0.3,0,0.7,0,1,0c29.3-0.5,53-10.8,70.5-30.5    c38.5-43.4,32.1-117.8,31.4-124.9c-2.5-53.3-27.7-78.8-48.5-90.7C280.8,5.2,262.7,0.4,242.5,0h-0.7c-0.1,0-0.3,0-0.4,0h-0.6    c-11.1,0-32.9,1.8-53.8,13.7c-21,11.9-46.6,37.4-49.1,91.1c-0.7,7.1-7.1,81.5,31.4,124.9C186.7,249.4,210.4,259.7,239.7,260.2z     M164.6,107.3c0-0.3,0.1-0.6,0.1-0.8c3.3-71.7,54.2-79.4,76-79.4h0.4c0.2,0,0.5,0,0.8,0c27,0.6,72.9,11.6,76,79.4    c0,0.3,0,0.6,0.1,0.8c0.1,0.7,7.1,68.7-24.7,104.5c-12.6,14.2-29.4,21.2-51.5,21.4c-0.2,0-0.3,0-0.5,0l0,0c-0.2,0-0.3,0-0.5,0    c-22-0.2-38.9-7.2-51.4-21.4C157.7,176.2,164.5,107.9,164.6,107.3z" fill="#FFFFFF"/><path d="M446.8,383.6c0-0.1,0-0.2,0-0.3c0-0.8-0.1-1.6-0.1-2.5c-0.6-19.8-1.9-66.1-45.3-80.9c-0.3-0.1-0.7-0.2-1-0.3    c-45.1-11.5-82.6-37.5-83-37.8c-6.1-4.3-14.5-2.8-18.8,3.3c-4.3,6.1-2.8,14.5,3.3,18.8c1.7,1.2,41.5,28.9,91.3,41.7    c23.3,8.3,25.9,33.2,26.6,56c0,0.9,0,1.7,0.1,2.5c0.1,9-0.5,22.9-2.1,30.9c-16.2,9.2-79.7,41-176.3,41    c-96.2,0-160.1-31.9-176.4-41.1c-1.6-8-2.3-21.9-2.1-30.9c0-0.8,0.1-1.6,0.1-2.5c0.7-22.8,3.3-47.7,26.6-56    c49.8-12.8,89.6-40.6,91.3-41.7c6.1-4.3,7.6-12.7,3.3-18.8c-4.3-6.1-12.7-7.6-18.8-3.3c-0.4,0.3-37.7,26.3-83,37.8    c-0.4,0.1-0.7,0.2-1,0.3c-43.4,14.9-44.7,61.2-45.3,80.9c0,0.9,0,1.7-0.1,2.5c0,0.1,0,0.2,0,0.3c-0.1,5.2-0.2,31.9,5.1,45.3    c1,2.6,2.8,4.8,5.2,6.3c3,2,74.9,47.8,195.2,47.8s192.2-45.9,195.2-47.8c2.3-1.5,4.2-3.7,5.2-6.3    C447,415.5,446.9,388.8,446.8,383.6z" fill="#FFFFFF"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
        </a>        
        <button class="hide-admin" @click="toggleAdminBar" >^</button>
    </section>
</template>

<script>
import { mapGetters } from 'vuex'
//
export default {
  name : 'admin-bar',
  data(){
      return{
          show: true,
          adminUrl: WPVUE.user.admin,
          postTypes: WPVUE.post_types,
      }
  },
  computed:{
      ...mapGetters([
          'currentPageID'
      ])
  },
  methods:{
      toggleAdminBar(){
          this.show = !this.show;
      }
  }
}
</script>



<style lang="scss" scope>

    #adminBar{
        background-color: #B9DBD0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 35px;
        top: -35px;
        transition: top 0.16s ease-in-out;
        text-align: left;
        display: flex;
        z-index: 9999;
        &.open{
            top: 0;
            .hide-admin{
                top: 0;
            }
        }
        .admin-dropdown{
            position: relative;
            &:hover{
                .dropdown{
                    display: block;
                }
            }
            .dropdown{
                display: none;
                margin: 0;
                padding: 0;
                list-style: none;
                position: absolute;
                left: 0;
                a{
                    display: block;
                    padding: 4px 15px;
                    background: darken(#E15D50, 10);
                    color: #fff;
                    text-decoration: none;
                    white-space: nowrap;
                    &:hover{
                        background-color: #E15D50;
                    }
                }
            }
        }
        .bar-button{
            background: #E15D50;
            border:1px solid darken(#E15D50, 5);
            color: #fff;
            text-decoration: none;
            padding: 4px 10px;
            display: inline-block;
            display: flex;
            height: 100%;
            align-items: center;
            &:hover{
                background-color: darken(#E15D50, 5);
            }
            svg{
                margin-right: 10px;
                width: 23px;
                height: 23px;
            }
        }

        .user-edit{
            align-self: flex-end;
            &:before {
                content: "";
                flex: auto;
            }
            svg{
                margin: 0;
            }
        }
        .hide-admin{
            position: absolute;
            right: 0;
            top: 35px;
            transition: top 0.16s ease-in-out;    
        }
    }
</style>