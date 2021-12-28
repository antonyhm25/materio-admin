<template>
  <div id="user-account" class="mt-5">
    <user-form
      :user="accountData"
      :errors="errors"
      :show-password="false"
      :isSaving="isSaving"
      @on-submit="onSubmit"
    />
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { getErrorFields } from '@/utils';

import UserForm from './UserForm.vue'

export default {
  props: {
    accountData: {
      type: Object,
      default: () => {},
    },
  },
  data: () => ({
    errors: [],
    isSaving: false
  }),
  components: {
    UserForm
  },
  methods: {
    ...mapActions('user', ['updateUser']),

    async onSubmit() {
      try {
        this.isSaving = true;

        const payload = {
          id: this.accountData.id,
          payload: {
            firstName: this.accountData.firstName,
            lastName: this.accountData.lastName,
            email: this.accountData.email,
            enable: this.accountData.enable,
          }
        };

        await this.updateUser(payload);
      } catch (error) {
        if (error.status === 422) {
          this.errors = getErrorFields(error.data);
        }
      } finally {
        this.isSaving = false
      }
    }
  }
}
</script>
