<template>
  <v-card class="mx-auto">
    <v-card-title>Permisos</v-card-title>
    <v-list>
      <v-list-group
        v-for="item in permissionsFiltered"
        :key="item.title"
        v-model="item.active"
        :prepend-icon="item.action"
        no-action
      >
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title v-text="item.title"></v-list-item-title>
          </v-list-item-content>
        </template>

        <v-list-item
          v-for="child in item.items"
          :key="child.title"
        >
          <v-list-item-content>
            <v-list-item-title v-text="child.title"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-card>
</template>
<script>
import {
  mdiShieldAccountOutline,
  mdiShieldLockOutline,
  mdiAccountGroup,
  mdiAccount,
  mdiOfficeBuildingMarkerOutline,
  mdiFood
} from '@mdi/js'

import { groupBy } from 'lodash'

export default {
  props: {
    role: {
      type: String,
      default: () => 'superadmin'
    }
  },
  data: () => ({
    allPermissions: [
      {
        action: mdiShieldLockOutline,
        items: [{ title: 'Ver Lista de Permisos', roles: ['superadmin'] }],
        title: 'Permisos'
      },
    ],
    permissions: [
      { module: 'Roles', title: 'Ver Lista de Roles', roles: ['superadmin'] },
      { module: 'Permisos', title: 'Ver Lista de Permisos', roles: ['superadmin'] },
      { module: 'Cuenta', title: 'Ver Cuenta', roles: ['superadmin', 'adminrestaurant', 'local'] },
      { module: 'Cuenta', title: 'Actualizar Cuenta', roles: ['superadmin', 'adminrestaurant', 'local'] },
      { module: 'Usuarios', title: 'Ver Lista de Usuarios', roles: ['superadmin'] },
      { module: 'Usuarios', title: 'Crear y Actualizar Usuarios', roles: ['superadmin'] },
      { module: 'Usuarios', title: 'Eliminar Usuarios', roles: ['superadmin'] },
      { module: 'Restaurantes', title: 'Ver Lista de Restaurantes', roles: ['superadmin'] },
      { module: 'Restaurantes', title: 'Crear y Actualizar Restaurantes', roles: ['superadmin'] },
      { module: 'Comidas', title: 'Ver Lista de Comidas', roles: ['superadmin', 'adminrestaurant'] },
      { module: 'Comidas', title: 'Crear y Actualizar Comidas', roles: ['adminrestaurant'] },
      { module: 'Comidas', title: 'Eliminar Comidas',  roles: ['adminrestaurant'] },
      { module: 'Ofertas de Comidas', title: 'Ver Lista de Ofertas de Comidas', roles: ['superadmin', 'adminrestaurant', 'local'] },
      { module: 'Ofertas de Comidas', title: 'Crear y Actualizar Ofertas de Comidas', roles: ['adminrestaurant'] },
      { module: 'Ofertas de Comidas', title: 'Eliminar Ofertas de Comidas', roles: ['adminrestaurant'] },
      { module: 'Ofertas de Comidas', title: 'Aceptar Ofertas de Comidas', roles: ['local'] },
    ],

    icons: {
      'Roles': mdiShieldAccountOutline,
      'Permisos': mdiShieldLockOutline,
      'Cuenta': mdiAccount,
      'Usuarios': mdiAccountGroup,
      'Restaurantes': mdiOfficeBuildingMarkerOutline,
      'Comidas': mdiFood,
      'Ofertas de Comidas': mdiFood
    }
  }),
  computed: {
    permissionsFiltered() {
      const permissions = this.permissions.filter(e => e.roles.includes(this.role))

      const result = [];

      const itemGroup = groupBy(permissions, 'module');
      for (const property in itemGroup) {
        let permission = {
          action: this.icons[property],
          title: property,
          items: itemGroup[property].map(e => {
            return {
              title: e.title
            }
          })
        };

        result.push(permission)
      }

      return result;
    }
  }
}
</script>
