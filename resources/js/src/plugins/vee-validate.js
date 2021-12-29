import Vue from 'vue'
import { localize, extend } from 'vee-validate'
import { required, email, min, max, alpha_dash, confirmed } from 'vee-validate/dist/rules';

extend('email', {
  ...email
});

extend('required', {
  ...required,
});

extend('min', {
  ...min,
});

extend('max', {
  ...max,
});

extend('alpha_dash', {
  ...alpha_dash,
});

extend('confirmed', {
  ...confirmed,
});

import es from 'vee-validate/dist/locale/es.json'
localize('es', es)
