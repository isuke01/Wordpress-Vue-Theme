# vutheme

> Vue wordperss theme

This theme require extra plugins.
### WP REST API Menus
[WP REPO](https://wordpress.org/plugins/wp-api-menus/) or [GITHUB](https://github.com/unfulvio/wp-api-menus)

[ACF FIELD TO MENU](https://github.com/unfulvio/wp-api-menus/issues/47)
### CONFIG
Open prod.env.js and Enter correct paths

Open index.html and Enter correct jQuery (I use local because of offline development)

If you would like to to have admin bar on locallhost, you must make correct config in functions.php

You can also look into dev.env.js if you wish too see other conf, but they are covered by settings in prod.env.js

PHP varribles pass to JS can be fund in functions.php, vt_get_localize_script_data() as array passed to script.
## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report
```

For a detailed explanation on how things work, check out the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).
