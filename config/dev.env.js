'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')
const homeUrl = prodEnv.NODE_ENV_HOME_URL

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  NODE_VUE_APP_URL: '"'+homeUrl+'/"',
  NODE_VUE_REST_URL: '"'+homeUrl+'/wp-json"',
  NODE_VUE_API_URL: '"'+homeUrl+'/api"',// it is used for extra WP plugin that make more complex requests, e.g for users, log in users etc things like that.
  NODE_LOCAL_URL: '"'+homeUrl+'"'
})
