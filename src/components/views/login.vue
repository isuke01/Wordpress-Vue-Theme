<template>
    <article role="page" id="single-page">
        <form @submit.prevent="handleForm" action="/" id="login-form" ref="login-form"> 
            <input type="text" name="login" value=""  autocomplete="bodyguru bodyguru-user" required/>
            <input type="password" name="password" value=""  autocomplete="bodyguru bodyguru-password" required/>
            <input type="submit" name="send" value="SEND" />
            <pre style="text-align: left">User Info : {{getUserData}} </pre>
        </form>
    </article>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name : 'login',
  props : ['info'],
  components: {
      
  },
  beforeMount(){

  },
  mounted(){
      this.form = this.$refs['login-form'];
      console.log(this.$refs['login-form']);
  },
  data(){
      return{
          form: null,
          user: '',
          cookie_name: 'wordpress_logged_in_b19045721adeaf90c2ea87489caf9b79',
          cookie_name2: 'wordpress_b19045721adeaf90c2ea87489caf9b79',
          cookie: encodeURI('john|1525373361|b6kx9sAiCcOBpHs0I2AqcFdOLRTWiLrm2ReAYha3Vh1|ff8cab1f8645887d968781622b4533ffa79db06f3952d23b9dd16328c09a5a5e'),
          data: {
              _wpnonce: WPVUE.nonce,
              action: '',
              user: '',
              password: ''
          }
      }
  },
  computed:{
      ...mapGetters([
        'getUserData'
      ])
  },
  methods:{
      ...mapActions([
          'loginUserAction'
      ]),
      handleForm(e){
          console.log();
          console.log(e)
          this.loginUserAction().then( (e) => {
              console.log('FINISH:', e);
          }).catch( (d) => {
              console.log('Error: ', e);

          }) 
      }
  }
}
</script>
