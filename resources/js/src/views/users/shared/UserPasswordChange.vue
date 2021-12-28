<template>
  <div id="user-password-change" class="mt-5">
    <v-card-text>
      <ValidationObserver ref="passwordChange">
        <v-form class="multi-col-validation" @submit.prevent="onSubmit">
          <alert-form :errors="errors" />

          <v-row>
            <v-col cols="12">
              <ValidationProvider name="Contraseña Actual" rules="required" v-slot="{ errors }">
                <v-text-field
                  v-model="payload.password"
                  label="Contraseña Actual"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                  :error-messages="errors"
                  @click:append="isPasswordVisible = !isPasswordVisible"
                  outlined
                  dense
                ></v-text-field>
              </ValidationProvider>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <ValidationProvider name="Contraseña Nueva" rules="required|min:6|alpha_dash|confirmed:confirmation" v-slot="{ errors }">
                <v-text-field
                  v-model="payload.newPassword"
                  label="Contraseña Nueva"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                  :error-messages="errors"
                  @click:append="isPasswordVisible = !isPasswordVisible"
                  outlined
                  dense
                ></v-text-field>
              </ValidationProvider>
            </v-col>

            <v-col cols="12" md="6">
              <ValidationProvider name="Confirmar Contraseña" rules="required" v-slot="{ errors }" vid="confirmation">
                <v-text-field
                  v-model="payload.repeatPassword"
                  label="Confirmar Contraseña"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                  :error-messages="errors"
                  @click:append="isPasswordVisible = !isPasswordVisible"
                  outlined
                  dense
                ></v-text-field>
              </ValidationProvider>
            </v-col>

            <v-col cols="12">
              <v-btn
                color="primary"
                type="submit"
                :disabled="isSaving"
                :loading="isSaving"
              >
                Guardar
              </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </ValidationObserver>
    </v-card-text>
  </div>
</template>
<script>
import { mdiEyeOffOutline, mdiEyeOutline } from '@mdi/js'
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mapActions } from 'vuex'
import { getErrorFields } from '@/utils';

import AlertForm from '@/components/AlertForm'

export default {
  components: {
    ValidationObserver,
    ValidationProvider,
    AlertForm
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    payload: {
      password: '',
      newPassword: '',
      repeatPassword: ''
    },

    isPasswordVisible: false,

    icons: {
      mdiEyeOffOutline,
      mdiEyeOutline
    },

    errors: [],
    isSaving: false
  }),
  methods: {
    ...mapActions('user', ['passwordChange']),

    async onSubmit() {
      const valid = await this.$refs.passwordChange.validate();
      if (valid) {
        try {
          this.isSaving = true;

          const payload = {
            id: this.user.id,
            payload: this.payload
          };

          await this.passwordChange(payload);

          this.errors = [];
        } catch (error) {
          if (error.status === 422) {
            this.errors = getErrorFields(error.data);
          }
        } finally {
          this.isSaving = false;
        }
      }
    }
  }
}
</script>
