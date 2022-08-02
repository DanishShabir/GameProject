require('dotenv').config()
const appName = process.env.APP_NAME || 'Red6Six Game'
module.exports = {
  telemetry: false,
  dev: process.env.NODE_ENV === 'development',
  debug: process.env.NODE_ENV === 'development',
  srcDir: __dirname,
  vue: {
    config: {
      performance: process.env.NODE_ENV === 'development',
    },
  },
  server: {
    port: process.env.PORT || 3000, // default: 3000
    host: process.env.HOST || '0.0.0.0', // default: localhost
  },
  env: {
    apiUrl: process.env.API_URL || 'https://api.red6six.com/api/',
    appName,
    webSocketUrl: process.env.WEBSOCKET_URL || 'wss://websocket.red6six.com',
    gveUrl: process.env.GAME_VALIDATION_URL || 'https://gve.red6six.com',
    appLocale: process.env.APP_LOCALE || 'en',
    githubAuth: !!process.env.GITHUB_CLIENT_ID,
  },
  /*
   ** Headers of the page
   */
  head: {
    title: appName,
    titleTemplate: '%s - ' + appName,
    script: [
      {
        src: '//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js',
        async: true,
      },
    ],
    meta: [
      { charset: 'utf-8' },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no',
      },
      { hid: 'description', name: 'description', content: 'Red6Six Game' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/icon.png' },
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap',
      },
    ],
  },
  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#d70000' },

  router: {
    middleware: ['locale', 'check-auth'],
  },
  /*
   ** Global CSS
   */
  css: ['~/assets/css/tailwind.css', 'video.js/dist/video-js.css'],

  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    '~plugins/gtag',
    '~components/global',
    '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    '~plugins/apiClient',
    { src: '~/plugins/pwa-update.js', mode: 'client' },
    { src: '~/plugins/vidle.js', mode: 'client' },
    { src: '~plugins/countdown', mode: 'client' },
    { src: '~plugins/bootstrap', mode: 'client' },
  ],
  /*
   ** Nuxt.js modules
   */
  modules: ['@nuxtjs/axios', '@nuxtjs/pwa', 'nuxt-vue-select', 'nuxt-clipboard2'],

  /*
   ** Nuxt PWA settings
   */
  pwa: {
    manifest: {
      name: 'Red6Six',
      short_name: 'Red6Six',
      description: 'Sign up and be the first to test your skills in our unique cash prize competitions. Why leave things to chance?',
      background_color: '#d70000',
    },
  },
  buildModules: [
    '@nuxtjs/router',
    '@nuxtjs/style-resources',
    /* '@nuxtjs/eslint-module', */
    '@nuxtjs/tailwindcss',
    '@nuxtjs/moment',
  ],
  /*
   ** Build configuration
   */
  build: {
    transpile: ['ohzi-core', 'planck-js', 'dat.gui'],
    extractCSS: process.env.NODE_ENV === 'development',
    /*
     ** You can extend webpack config here
     */
    extend(config, ctx) {},
  },
}
