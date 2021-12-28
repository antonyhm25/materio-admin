<template>
  <div id="user-profile">
    <v-card>
      <v-card-title class="d-flex justify-center flex-column flex-wrap">
        <v-avatar
          rounded
          size="120"
          class="mb-4"
        >
          <v-img :src="user.avatar"></v-img>
        </v-avatar>
        <span class="mb-2">{{ user.name }}</span>
        <v-chip>{{ user.roleName }}</v-chip>
      </v-card-title>

      <v-card-text class="d-flex justify-center flex-wrap mt-2 pe-sm-0">
        <div class="d-flex align-center me-8 mb-4">
          <v-avatar
            size="40"
            color="#CE93D8"
            rounded
            class="me-3"
          >
            <v-icon
              dark
              color="white"
              size="30"
            >
              {{ icons.mdiShieldAccountOutline }}
            </v-icon>
          </v-avatar>
          <div>
            <h3 class="text-xl font-weight-medium mb-n1">{{ user.permissions.length }}</h3>
            <span>Permisos</span>
          </div>
        </div>
      </v-card-text>

      <v-card-text>
        <span class="text-xl font-weight-semibold mb-2">Detalles</span>
        <v-divider></v-divider>
        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">email: </span>
              <span class="text--secondary">{{ user.email }}</span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">Role: </span>
              <span class="text--secondary">{{ user.role }}</span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="px-0 mb-n2" dense>
          <v-list-item-content>
            <v-list-item-title>
              <span class="font-weight-medium me-2">Estado: </span>
              <span class="text--secondary"><v-chip small :color="getColorEnable(user.enable)">{{ user.enable === 1 ? 'Activado' : 'Desactivado' }}</v-chip></span>
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
    </v-card>
  </div>
</template>
<script>
import { mdiShieldAccountOutline } from '@mdi/js'
import { email } from 'vee-validate/dist/rules';

export default {
  props: {
    user: {
      type: Object,
      default: () => { permissions: [] }
    }
  },
  data: () => ({
    icons: {
      mdiShieldAccountOutline
    }
  }),
  computed: {
    getColorEnable() {
      return (enable) => {
        return enable === 1 ? '#81C784' : '#EF5350'
      }
    }
  }
}
</script>
