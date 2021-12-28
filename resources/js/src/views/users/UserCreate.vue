<template>
  <div id="users-create">
    <v-row>
      <v-col
        cols="12"
        md="8"
      >
        <v-card>
          <v-card-title>Nuevo Usuario</v-card-title>

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
       <user-permission role="superadmin" />
      </v-col>
    </v-row>
  </div>
</template>
<script>
import { mapActions } from 'vuex'
import { getErrorFields } from '@/utils';

import UserPermission from './shared/UserPermission.vue'
import UserForm from './shared/UserForm.vue'

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
      role: 'adminrestaurant',
      password: '',
      repeatPassword: '',
      enable: 1
    },

    errors: [],
    isSaving: false
  }),
  computed: {
    fullName() {
      return `${this.user.firstName} ${this.user.lastName}`;
    }
  },
  methods: {
    ...mapActions('user', ['createUser']),

    async onSubmit() {
      try {
        this.isSaving = true;

        const id = await this.createUser(this.user);

        this.$router.push({ name: 'admin-users-view', params: { id } });
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
