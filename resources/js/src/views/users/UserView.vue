<template>
  <div id="user-view">
    <v-row>
      <v-col
        cols="12"
        md="4"
      >
        <user-profile :user="currentUser"  class="mb-5" />

        <user-permission :role="currentUser.role" />
      </v-col>

      <v-col
        cols="12"
        md="8"
      >
        <v-card>
          <v-tabs v-model="tab" show-arrows>
            <template  v-for="tab in tabs" >
              <v-tab :key="tab.id" v-if="canViewTabRestaurant(tab)">
                <v-icon size="20" class="me-3">
                  {{ tab.icon }}
                </v-icon>
                <span>{{ tab.title }}</span>
            </v-tab>
            </template>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <user-account :accountData="currentUser" />
            </v-tab-item>

            <v-tab-item>
              <user-password-change v-if="auth.id === currentUser.id" :user="currentUser" />
              <user-password-reset :user="currentUser" v-else />
            </v-tab-item>

            <v-tab-item>

            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import { mdiAccountOutline, mdiLockOpenOutline, mdiOfficeBuildingMarkerOutline  } from '@mdi/js'
import { mapActions, mapGetters } from 'vuex'

import UserAccount from './shared/UserAccount.vue'
import UserPasswordReset from './shared/UserPasswordReset.vue'
import UserPasswordChange from './shared/UserPasswordChange.vue'
import UserProfile from './shared/UserProfile.vue'
import UserPermission from './shared/UserPermission.vue'

export default {
  components: {
    UserAccount,
    UserPasswordReset,
    UserPasswordChange,
    UserProfile,
    UserPermission
  },
  data: () => ({
    tab: null,

    tabs: [
      { id: 1, title: 'Mi Cuenta', icon: mdiAccountOutline },
      { id: 2, title: 'Seguridad', icon: mdiLockOpenOutline },
      { id: 3, title: 'Negocio', icon: mdiOfficeBuildingMarkerOutline },
    ],
  }),
  computed: {
    ...mapGetters('user', ['currentUser']),

    auth() {
      return this.$store.state.auth.user;
    },

    canViewTabRestaurant() {
      return (tab) => {
        if (tab.title !== 'Negocio') return true;

        return tab.title === 'Negocio' && this.currentUser.role === 'adminrestaurant';
      }
    }
  },
  methods: {
    ...mapActions('user', ['getUser'])
  },
  async created() {
    await this.getUser(this.$route.params.id || this.auth.id);
  }
}
</script>
