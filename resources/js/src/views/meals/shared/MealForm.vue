<template>
  <div id="meal-form" class="mt-4">
    <v-card-text>
      <alert-form :errors="errors" />
    </v-card-text>

    <v-card-text class="d-flex">
      <v-avatar
        rounded
        size="120"
        class="me-6"
      >
        <v-img :src="photo"></v-img>
      </v-avatar>

      <!-- upload photo -->
      <div>
        <v-btn
          color="primary"
          class="me-3 mt-5"
          @click="$refs.refInputEl.click()"
        >
          <v-icon class="d-sm-none">
            {{ icons.mdiCloudUploadOutline }}
          </v-icon>
          <span class="d-none d-sm-block">Agregar Foto</span>
        </v-btn>

        <input
          ref="refInputEl"
          type="file"
          accept=".jpeg,.png,.jpg,GIF"
          :hidden="true"
          @input="updatePhoto"
        />

        <p class="text-sm mt-5">
          Permitido JPG, GIF or PNG. Tamaño maximo de 2MB
        </p>
      </div>
    </v-card-text>

    <v-card-text>
      <ValidationObserver
        ref="mealForm"
      >
        <v-form class="multi-col-validation" @submit.prevent="onSubmit">
          <v-row>
            <v-col
              cols="12"
            >
              <ValidationProvider name="Nombre" rules="required" v-slot="{ errors }">
                <v-text-field
                  v-model="meal.name"
                  label="Nombre"
                  :error-messages="errors"
                  outlined
                  dense
                ></v-text-field>
              </ValidationProvider>
            </v-col>

            <v-col
              cols="12"
            >
              <v-textarea
                v-model="meal.description"
                label="Descripción"
                auto-grow
                outlined
                rows="3"
                row-height="15"
                counter
              ></v-textarea>
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
import { mdiCloudUploadOutline } from '@mdi/js'
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import AlertForm from '@/components/AlertForm'

export default {
  props: {
    meal: {
      type: Object,
      required: true
    },
    errors: {
      type: Array,
      required: true
    },
    isSaving: {
      type: Boolean,
      default: () => false
    },
    mealPhoto: {
      type: [String, File],
      default: () => null
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    AlertForm
  },
  data: () => ({
    localPhoto: require('@/assets/images/avatars/no-food.png').default,

    icons: {
      mdiCloudUploadOutline
    }
  }),
  computed: {
    photo() {
      if (typeof this.mealPhoto === 'string') {
        return this.mealPhoto;
      }

      return this.localPhoto;
    }
  },
  methods: {
    async onSubmit() {
      const valid = await this.$refs.mealForm.validate();
      if (valid) {
        this.$emit('on-submit');
      }
    },

    async updatePhoto(input) {
      if (input.target.files && input.target.files[0]) {
        this.meal.photo = input.target.files[0];
        const reader = new FileReader()
        reader.onload = (e) => {
          this.localPhoto = e.target.result;

          const formData = new FormData();
          formData.append('photo', input.target.files[0]);

          this.$emit('on-photo', formData);
        }

        reader.readAsDataURL(input.target.files[0])
      }
    }
  }
}
</script>
