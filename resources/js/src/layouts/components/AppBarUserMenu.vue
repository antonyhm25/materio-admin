<template>
  <v-menu offset-y left nudge-bottom="14" min-width="230" content-class="user-profile-menu-content">
    <template v-slot:activator="{ on, attrs }">
      <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
        <v-avatar size="40px" v-bind="attrs" v-on="on">
          <v-img :src="photo(auth)"></v-img>
        </v-avatar>
      </v-badge>
    </template>
    <v-list>
      <div class="pb-3 pt-2">
        <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
          <v-avatar size="40px">
            <v-img :src="photo(auth)"></v-img>
          </v-avatar>
        </v-badge>
        <div class="d-inline-flex flex-column justify-center ms-3" style="vertical-align: middle">
          <span class="text--primary font-weight-semibold mb-n1">
            <span
              class="d-inline-block text-truncate"
              style="max-width: 130px;"
            >
              {{ auth ? auth.name : '' }}
            </span>
          </span>
          <small class="text--disabled text-capitalize">
            <span
              class="d-inline-block text-truncate"
              style="max-width: 130px;"
            >
              {{ auth ? auth.roleDisplay : '' }}
            </span>
          </small>
        </div>
      </div>

      <v-divider></v-divider>

      <!-- Profile -->
      <v-list-item link>
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            {{ icons.mdiAccountOutline }}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title @click="$router.push({ name: 'account-view' })">Mi Cuenta</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider class="my-2"></v-divider>

      <!-- Logout -->
      <v-list-item link>
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            {{ icons.mdiLogoutVariant }}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title @click="onLogout">Salir</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
import {
  mdiAccountOutline,
  mdiLogoutVariant,
} from '@mdi/js'

import { mapActions } from 'vuex'

export default {
  data: () => ({
    icons: {
      mdiAccountOutline,
      mdiLogoutVariant,
    }
  }),
  computed: {
    auth() {
      return this.$store.state.auth.user;
    },
    photo() {
      return (user) => {
        if (user) return user.avatar
        return ''
      }
    }
  },
  methods: {
    ...mapActions('auth', ['logout']),

    async onLogout() {
      await this.logout();
      this.$router.push({ name: 'pages-login' });
    }
  }
}
</script>

<style lang="scss">
.user-profile-menu-content {
  .v-list-item {
    min-height: 2.5rem !important;
  }
}
</style>
