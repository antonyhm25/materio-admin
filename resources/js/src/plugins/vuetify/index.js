import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'
import es from 'vuetify/lib/locale/es'
import preset from './default-preset/preset'

Vue.use(Vuetify)

export default new Vuetify({
  preset,
  icons: {
    iconfont: 'mdiSvg',
  },
  theme: {
    options: {
      customProperties: true,
      variations: false,
    },
  },
  lang: {
    locales: { es },
    current: 'es'
  }
})
