<template>
  <div id="restaurant-profile">
    <v-card>
      <v-img :src="photo" height="250" />
      <v-card-title class="justify-center"> {{ user.restaurant.name }} </v-card-title>

      <v-card-text>
        <span class="text-xl font-weight-semibold mb-2">Detalles</span>
        <v-divider></v-divider>
        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">Propietario: </span>
              <span class="text--secondary">{{ user.name }}</span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">Dirección: </span>
              <span class="text--secondary">{{ user.restaurant.address }}</span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">Fecha de registro: </span>
              <span class="text--secondary">{{ $date(user.createdAt).format('ll') }}</span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-card-text>

      <v-card-actions class="justify-center flex-column">
        <v-btn
          color="primary"
          class="me-3 mt-5"
          @click="$refs.refInputEl.click()"
        >
          <v-icon class="d-sm-none">
            {{ icons.mdiCloudUploadOutline }}
          </v-icon>
          <span class="d-none d-sm-block">Actualizar Foto</span>
        </v-btn>

        <input
          ref="refInputEl"
          type="file"
          accept=".jpeg,.png,.jpg,GIF"
          :hidden="true"
          @change="updatePhoto"
        />

        <p class="text-sm mt-5">
          Permitido JPG, GIF o PNG. tamaño maximo de 2MB
        </p>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import { mdiShieldAccountOutline } from '@mdi/js'
import { mapActions } from 'vuex';

export default {
  props: {
    user: {
      type: Object,
      default: () => { }
    }
  },
  data: () => ({
    icons: {
      mdiShieldAccountOutline
    },
    localPhoto: null,
  }),
  computed: {
    photo() {
      if (this.localPhoto) {
        return this.localPhoto
      }

      if (this.user.restaurant.photo) {
        return `/storage/${this.user.restaurant.photo}`;
      }

      return require('@/assets/images/pages/card-basic-influencer.png').default;
    }
  },
  methods: {
    ...mapActions('restaurant', ['uploadPhoto']),

    async updatePhoto(input) {
      if (input.target.files && input.target.files[0]) {

        const formData = new FormData();
        formData.append('photo', input.target.files[0]);

        const payload = {
          id: this.user.restaurant.id,
          file: formData
        };

        const data = await this.uploadPhoto(payload);

        this.localPhoto = `/storage/${data.path}`;
        this.$store.commit('auth/UPDATE_PHOTO_RESTAURANT', this.localPhoto);
      }
    }
  }
}
</script>
