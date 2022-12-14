import Vue from 'vue'
import Meta from 'vue-meta'
import ClientOnly from 'vue-client-only'
import NoSsr from 'vue-no-ssr'
import { createRouter } from './router.js'
import NuxtChild from './components/nuxt-child.js'
import NuxtError from './components/nuxt-error.vue'
import Nuxt from './components/nuxt.js'
import App from './App.js'
import { setContext, getLocation, getRouteData, normalizeError } from './utils'
import { createStore } from './store.js'

/* Plugins */

import nuxt_plugin_workbox_0c1ee9f8 from 'nuxt_plugin_workbox_0c1ee9f8' // Source: ./workbox.js (mode: 'client')
import nuxt_plugin_nuxtvueselect_62752011 from 'nuxt_plugin_nuxtvueselect_62752011' // Source: ./nuxt-vue-select.js (mode: 'client')
import nuxt_plugin_axios_35072030 from 'nuxt_plugin_axios_35072030' // Source: ./axios.js (mode: 'all')
import nuxt_plugin_router_7e5e18c7 from 'nuxt_plugin_router_7e5e18c7' // Source: ./router.js (mode: 'all')
import nuxt_plugin_global_0a6ae274 from 'nuxt_plugin_global_0a6ae274' // Source: ../client/components/global (mode: 'all')
import nuxt_plugin_i18n_56ca5e75 from 'nuxt_plugin_i18n_56ca5e75' // Source: ../client/plugins/i18n (mode: 'all')
import nuxt_plugin_vform_f95cee7a from 'nuxt_plugin_vform_f95cee7a' // Source: ../client/plugins/vform (mode: 'all')
import nuxt_plugin_axios_fb9c9a02 from 'nuxt_plugin_axios_fb9c9a02' // Source: ../client/plugins/axios (mode: 'all')
import nuxt_plugin_nuxtclientinit_37cfbfdf from 'nuxt_plugin_nuxtclientinit_37cfbfdf' // Source: ../client/plugins/nuxt-client-init (mode: 'all')
import nuxt_plugin_bootstrap_0f900877 from 'nuxt_plugin_bootstrap_0f900877' // Source: ../client/plugins/bootstrap (mode: 'client')

// Component: <ClientOnly>
Vue.component(ClientOnly.name, ClientOnly)

// TODO: Remove in Nuxt 3: <NoSsr>
Vue.component(NoSsr.name, {
  ...NoSsr,
  render (h, ctx) {
    if (process.client && !NoSsr._warned) {
      NoSsr._warned = true

      console.warn('<no-ssr> has been deprecated and will be removed in Nuxt 3, please use <client-only> instead')
    }
    return NoSsr.render(h, ctx)
  }
})

// Component: <NuxtChild>
Vue.component(NuxtChild.name, NuxtChild)
Vue.component('NChild', NuxtChild)

// Component NuxtLink is imported in server.js or client.js

// Component: <Nuxt>
Vue.component(Nuxt.name, Nuxt)

Vue.use(Meta, {"keyName":"head","attribute":"data-n-head","ssrAttribute":"data-n-head-ssr","tagIDKeyName":"hid"})

const defaultTransition = {"name":"page","mode":"out-in","appear":true,"appearClass":"appear","appearActiveClass":"appear-active","appearToClass":"appear-to"}

async function createApp (ssrContext) {
  const router = await createRouter(ssrContext)

  const store = createStore(ssrContext)
  // Add this.$router into store actions/mutations
  store.$router = router

  // Create Root instance

  // here we inject the router and store to all child components,
  // making them available everywhere as `this.$router` and `this.$store`.
  const app = {
    store,
    router,
    nuxt: {
      defaultTransition,
      transitions: [defaultTransition],
      setTransitions (transitions) {
        if (!Array.isArray(transitions)) {
          transitions = [transitions]
        }
        transitions = transitions.map((transition) => {
          if (!transition) {
            transition = defaultTransition
          } else if (typeof transition === 'string') {
            transition = Object.assign({}, defaultTransition, { name: transition })
          } else {
            transition = Object.assign({}, defaultTransition, transition)
          }
          return transition
        })
        this.$options.nuxt.transitions = transitions
        return transitions
      },

      err: null,
      dateErr: null,
      error (err) {
        err = err || null
        app.context._errored = Boolean(err)
        err = err ? normalizeError(err) : null
        const nuxt = this.nuxt || this.$options.nuxt
        nuxt.dateErr = Date.now()
        nuxt.err = err
        // Used in src/server.js
        if (ssrContext) {
          ssrContext.nuxt.error = err
        }
        return err
      }
    },
    ...App
  }

  // Make app available into store via this.app
  store.app = app

  const next = ssrContext ? ssrContext.next : location => app.router.push(location)
  // Resolve route
  let route
  if (ssrContext) {
    route = router.resolve(ssrContext.url).route
  } else {
    const path = getLocation(router.options.base, router.options.mode)
    route = router.resolve(path).route
  }

  // Set context to app.context
  await setContext(app, {
    store,
    route,
    next,
    error: app.nuxt.error.bind(app),
    payload: ssrContext ? ssrContext.payload : undefined,
    req: ssrContext ? ssrContext.req : undefined,
    res: ssrContext ? ssrContext.res : undefined,
    beforeRenderFns: ssrContext ? ssrContext.beforeRenderFns : undefined,
    ssrContext
  })

  const inject = function (key, value) {
    if (!key) {
      throw new Error('inject(key, value) has no key provided')
    }
    if (value === undefined) {
      throw new Error('inject(key, value) has no value provided')
    }

    key = '$' + key
    // Add into app
    app[key] = value

    // Add into store
    store[key] = app[key]

    // Check if plugin not already installed
    const installKey = '__nuxt_' + key + '_installed__'
    if (Vue[installKey]) {
      return
    }
    Vue[installKey] = true
    // Call Vue.use() to install the plugin into vm
    Vue.use(() => {
      if (!Object.prototype.hasOwnProperty.call(Vue, key)) {
        Object.defineProperty(Vue.prototype, key, {
          get () {
            return this.$root.$options[key]
          }
        })
      }
    })
  }

  if (process.client) {
    // Replace store state before plugins execution
    if (window.__NUXT__ && window.__NUXT__.state) {
      store.replaceState(window.__NUXT__.state)
    }
  }

  // Plugin execution

  if (process.client && typeof nuxt_plugin_workbox_0c1ee9f8 === 'function') {
    await nuxt_plugin_workbox_0c1ee9f8(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_nuxtvueselect_62752011 === 'function') {
    await nuxt_plugin_nuxtvueselect_62752011(app.context, inject)
  }

  if (typeof nuxt_plugin_axios_35072030 === 'function') {
    await nuxt_plugin_axios_35072030(app.context, inject)
  }

  if (typeof nuxt_plugin_router_7e5e18c7 === 'function') {
    await nuxt_plugin_router_7e5e18c7(app.context, inject)
  }

  if (typeof nuxt_plugin_global_0a6ae274 === 'function') {
    await nuxt_plugin_global_0a6ae274(app.context, inject)
  }

  if (typeof nuxt_plugin_i18n_56ca5e75 === 'function') {
    await nuxt_plugin_i18n_56ca5e75(app.context, inject)
  }

  if (typeof nuxt_plugin_vform_f95cee7a === 'function') {
    await nuxt_plugin_vform_f95cee7a(app.context, inject)
  }

  if (typeof nuxt_plugin_axios_fb9c9a02 === 'function') {
    await nuxt_plugin_axios_fb9c9a02(app.context, inject)
  }

  if (typeof nuxt_plugin_nuxtclientinit_37cfbfdf === 'function') {
    await nuxt_plugin_nuxtclientinit_37cfbfdf(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_bootstrap_0f900877 === 'function') {
    await nuxt_plugin_bootstrap_0f900877(app.context, inject)
  }

  // If server-side, wait for async component to be resolved first
  if (process.server && ssrContext && ssrContext.url) {
    await new Promise((resolve, reject) => {
      router.push(ssrContext.url, resolve, () => {
        // navigated to a different route in router guard
        const unregister = router.afterEach(async (to, from, next) => {
          ssrContext.url = to.fullPath
          app.context.route = await getRouteData(to)
          app.context.params = to.params || {}
          app.context.query = to.query || {}
          unregister()
          resolve()
        })
      })
    })
  }

  return {
    store,
    app,
    router
  }
}

export { createApp, NuxtError }
