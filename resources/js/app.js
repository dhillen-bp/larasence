import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from "ziggy-js"
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';


createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(PrimeVue, {
        theme: {
            preset: Aura,
            // options: {
            //     prefix: 'p',
            //     darkModeSelector: 'system',
            //     cssLayer: false
            // }
        }
      })
      .use(
        Vue3Toastify,
        {
          autoClose: 3000,
        } ,
      )
      .mount(el)
  },
})
