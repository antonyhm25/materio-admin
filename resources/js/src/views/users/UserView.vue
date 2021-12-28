<template>
  <div id="user-view">
    <v-row>
      <v-col
        cols="12"
        md="4"
      >
        <user-profile :user="currentUser"  class="mb-5" />

        <restaurant-profile v-if="currentUser.restaurant" :user="currentUser"  class="mb-5" />

        <user-permission :role="currentUser.role" />
      </v-col>

      <v-col
        cols="12"
        md="8"
      >
        <v-card>
          <v-tabs v-model="tab" show-arrows>
            <v-tab  v-for="tab in tabs" :key="tab.id">
              <v-icon size="20" class="me-3">
                {{ tab.icon }}
              </v-icon>
              <span>{{ tab.title }}</span>
            </v-tab>
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

        <v-card v-if="currentUser.restaurant" class="mt-7">
          <v-card-title>Platillos</v-card-title>
          <v-divider></v-divider>
          <meal-list :loading="isMealsLoading" :items="meals" :total-items="totalItems" />
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

import RestaurantProfile from '@/views/restaurants/shared/RestaurantProfile.vue'
import MealList from '@/views/meals/shared/MealList.vue'

export default {
  components: {
    UserAccount,
    UserPasswordReset,
    UserPasswordChange,
    UserProfile,
    UserPermission,

    RestaurantProfile,
    MealList
  },
  data: () => ({
    tab: null,
    isMealsLoading: true,

    tabs: [
      { id: 1, title: 'Mi Cuenta', icon: mdiAccountOutline },
      { id: 2, title: 'Seguridad', icon: mdiLockOpenOutline },
    ],
  }),
  computed: {
    ...mapGetters('user', ['currentUser']),
    ...mapGetters('meal', ['meals', 'totalItems']),

    auth() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    ...mapActions('user', ['getUser']),
    ...mapActions('meal', ['getMeals'])
  },
  async created() {
    await this.getUser(this.$route.params.id || this.auth.id);

    if (this.currentUser && this.currentUser.restaurant) {
      await  this.getMeals({ id: this.currentUser.restaurant.id, query: '' });
      this.isMealsLoading = false;
    }
  }
}
</script>
