<template>
  <div id="user-form" class="mt-4">
    <v-card-text>
      <ValidationObserver
        ref="userForm"
      >
        <v-form class="multi-col-validation" @submit.prevent="onSubmit">
          <alert-form :errors="errors" />

          <v-row>
            <v-col
              cols="12"
              md="6"
            >
              <ValidationProvider name="Nombre" rules="required" v-slot="{ errors }">
                <v-text-field
                  v-model="user.firstName"
                  label="Nombre"
                  :error-messages="errors"
                  outlined
                  dense
                ></v-text-field>
              </ValidationProvider>
            </v-col>

            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="user.lastName"
                label="Apellidos"
                outlined
                dense
              ></v-text-field>
            </v-col>

            <v-col
              cols="12"
              md="6"
            >
              <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                <v-text-field
                  v-model="user.email"
                  label="Email"
                  outlined
                  dense
                  placeholder="Email"
                  :error-messages="errors"
                ></v-text-field>
              </ValidationProvider>
            </v-col>

            <v-col
              cols="12"
              md="6"
            >
              <v-select
                label="Estado"
                v-model="user.enable"
                :items="status"
                item-text="name"
                item-value="value"
                dense
                outlined
              ></v-select>
            </v-col>

            <v-col
              v-if="showPassword"
              cols="12"
              md="6"
            >
              <ValidationProvider name="Contraseña" rules="required|min:6|alpha_dash|confirmed:confirmation" v-slot="{ errors }">
                <v-text-field
                  v-model="user.password"
                  label="Contraseña"
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

            <v-col
              v-if="showPassword"
              cols="12"
              md="6"
            >
              <ValidationProvider name="Confirmar Contraseña" rules="required" vid="confirmation" v-slot="{ errors }">
                <v-text-field
                  v-model="user.repeatPassword"
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
import AlertForm from '@/components/AlertForm'

export default {
  props: {
    user: {
      type: Object,
      required: true
    },
    errors: {
      type: Array,
      required: true
    },
    showPassword: {
      type: Boolean,
      default: () => true
    },
    isSaving: {
      type: Boolean,
      default: () => false
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    AlertForm
  },
  data: () => ({
    status: [
      { name: 'Activado', value: 1 },
      { name: 'Desactivado', value: 0 },
    ],

    isPasswordVisible: false,

    icons: {
      mdiEyeOffOutline,
      mdiEyeOutline
    }
  }),
  methods: {
    async onSubmit() {
      const valid = await this.$refs.userForm.validate();
      if (valid) {
        this.$emit('on-submit');
      }
    }
  }
}
</script>
