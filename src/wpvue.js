import axios from 'axios';
// Load params from WP in case it is development and it not maintained by php server

if (process.env.NODE_ENV === 'development') {
    const wpVars = localStorage.getItem('WPVUE');
    window.WPVUE = JSON.parse(wpVars);
    axios.get(process.env.NODE_VUE_REST_URL + '/wpvue/v2/getparams/')
      .then(res => {
        let wpVarsLoaded = JSON.stringify(res.data);
        if ( (!window.WPVUE && wpVarsLoaded) || (wpVarsLoaded !== wpVars) ) {
          localStorage.setItem('WPVUE', wpVarsLoaded);
          location.reload();
        }
  
    });  
}
