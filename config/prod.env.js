'use strict'
module.exports = {
  NODE_ENV: '"production"',
  NODE_ENV_PUBLIC_PATCH: '/wp-content/themes/vutheme', // it is required for prod, because standard path is looking for http://example/dist_directory, but we're have it into our wp-content .... direcotry
  NODE_ENV_HOME_URL: 'http://vuetheme.wp'   // it is required for development, otverwise system try to call locallhost:8080/wp-json ... and this is incorrect path
}
