import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from "ziggy-js"
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import 'primeicons/primeicons.css';
import { definePreset } from '@primeuix/themes';

const myCustomPreset = definePreset(Aura, {
    semantic: {
      primary: {
        50: '{purple.50}',
        100: '{purple.100}',
        200: '{purple.200}',
        300: '{purple.300}',
        400: '{purple.400}',
        500: '{purple.500}',
        600: '{purple.600}',
        700: '{purple.700}',
        800: '{purple.800}',
        900: '{purple.900}',
        950: '{purple.950}',
      },
    },
  });

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
            preset: myCustomPreset,
            // options: {
            //     prefix: 'p',
            //     darkModeSelector: '.p-dark',
            //     cssLayer: false,
            // },
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
