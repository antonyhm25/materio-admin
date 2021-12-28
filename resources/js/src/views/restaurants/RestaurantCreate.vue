<template>
  <div id="restaurant-create">
    <v-row>
      <v-col
        cols="12"
        md="8"
      >
        <v-card>
          <v-card-title>Nuevo Restaurante</v-card-title>

          <v-divider></v-divider>

          <user-form
            :user="user"
            :errors="errors"
            :isSaving="isSaving"
            @on-submit="onSubmit"
          />
        </v-card>
      </v-col>

      <v-col
        cols="12"
        md="4"
      >
       <user-permission role="adminrestaurant" />
      </v-col>
    </v-row>
  </div>
</template>
<script>
import { mapActions } from 'vuex'
import { getErrorFields } from '@/utils';

import UserPermission from '@/views/users/shared/UserPermission.vue'
import UserForm from '@/views/users/shared/UserForm.vue'

export default {
  components: {
    UserPermission,
    UserForm
  },
  data: () => ({
    user: {
      firstName: '',
      lastName: '',
      email: '',
      password: '',
      repeatPassword: '',
      enable: 1,
      restaurant: {
        name: '',
        address: ''
      }
    },

    errors: [],
    isSaving: false
  }),
  methods: {
    ...mapActions('restaurant', ['createRestaurant']),

    async onSubmit() {
      try {
        this.isSaving = true;

        const id = await this.createRestaurant(this.user);

        this.$router.push({ name: 'admin-restaurants-view', params: { id } });
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
</script>
