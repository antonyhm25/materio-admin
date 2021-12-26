<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->
        <v-card-title class="d-flex align-center justify-center py-7">
          <router-link to="/" class="d-flex align-center">
            <v-img
              :src="require('@/assets/images/logos/logo.svg').default"
              max-height="30px"
              max-width="30px"
              alt="logo"
              contain
              class="me-3"
            ></v-img>

            <h2 class="text-2xl font-weight-semibold">Restaurant</h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary mb-2">Panel de Administración! 🔐</p>
          <p class="mb-2">Inicie sesión en su cuenta</p>
        </v-card-text>

        <v-alert
          v-if="errors.length > 0"
          outlined
          color="error"
          prominent
          border="left"
        >
         <ul>
           <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
         </ul>
        </v-alert>

        <!-- login form -->
        <v-card-text>
          <ValidationObserver
            ref="login"
          >
            <v-form @submit.prevent="onSubmit">
              <ValidationProvider name="email" rules="required|email" v-slot="{ errors }">
                <v-text-field
                  v-model="email"
                  outlined
                  label="Email"
                  placeholder="john@example.com"
                  class="mb-3"
                  :error-messages="errors"
                ></v-text-field>
              </ValidationProvider>

              <ValidationProvider name="contraseña" rules="required" v-slot="{ errors }">
                <v-text-field
                  v-model="password"
                  outlined
                  :type="isPasswordVisible ? 'text' : 'password'"
                  label="Contraseña"
                  placeholder="············"
                  :error-messages="errors"
                  :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                  @click:append="isPasswordVisible = !isPasswordVisible"
                ></v-text-field>
              </ValidationProvider>
              <v-btn
                block color="primary"
                class="mt-6"
                type="submit"
              > Entrar </v-btn>
            </v-form>
          </ValidationObserver>
        </v-card-text>

        <!-- divider -->
        <v-card-text class="d-flex align-center mt-2">
          <v-divider></v-divider>
          <span class="mx-5">🔑</span>
          <v-divider></v-divider>
        </v-card-text>
      </v-card>
    </div>

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="173"
      :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default"
    />

    <!-- tree -->
    <v-img class="auth-tree" width="247" height="185" :src="require('@/assets/images/misc/tree.png').default"></v-img>

    <!-- tree  -->
    <v-img
      class="auth-tree-3"
      width="377"
      height="289"
      :src="require('@/assets/images/misc/tree-3.png').default"
    ></v-img>
  </div>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js';
import { getErrorFields } from '@/utils';
import { mapActions } from 'vuex';

import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';

extend('email', {
  ...email,
  message: '{_field_} debería ser valido.'
});
extend('required', {
  ...required,
  message: '{_field_} no puede ser vacio.'
});

export default {
  components: {
    ValidationObserver,
    ValidationProvider
  },
  data: () => ({
    isPasswordVisible: false,
    email: '',
    password: '',

    icons: {
      mdiEyeOutline,
      mdiEyeOffOutline,
    },

    errors: []
  }),
  methods: {
    ...mapActions('auth', ['login']),

    async onSubmit() {
      try {
        const valid = await this.$refs.login.validate()
        if (valid) {
          await this.login({
            email: this.email,
            password: this.password,
            deviceName: 'browser'
          });

          this.errors = [];
          this.$router.push({ name: 'dashboard' });
        }
      } catch (error) {
        this.errors = getErrorFields(error);
      }
    }
  }
}
</script>

<style lang="scss">
@import '~@resources/sass/preset/pages/auth.scss';
</style>
