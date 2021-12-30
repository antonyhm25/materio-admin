<template>
  <div id="users-page">
    <v-card>
      <v-card-title>Usuarios Administradores</v-card-title>

      <v-divider></v-divider>

      <v-card-text class="d-flex align-center flex-wrap">
        <v-text-field
          v-debounce:500="onSearch"
          class="me-3 user-search"
          placeholder="Buscar"
          :prepend-inner-icon="icons.mdiAccountSearch"
          hide-details
          outlined
          dense
        ></v-text-field>

        <v-spacer></v-spacer>

        <div class="d-flex align-center flex-wrap">
          <v-btn
            class="mr-2"
            color="primary"
            :to="{ name: 'admin-users-create' }"
            depressed
          >
            <v-icon left>{{ icons.mdiAccountPlus }}</v-icon>
            Agregar Usuario
          </v-btn>
          <v-dialog
            v-model="confirmDeletion"
            width="300"
            persistent
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="error"
                v-bind="attrs"
                v-on="on"
                :loading="isDeleting"
                :disabled="disabledDelete"
                depressed
              >
                <v-icon left>{{ icons.mdiDelete }}</v-icon>
                Eliminar
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="headline">
                Confirmación
              </v-card-title>
              <v-card-text>
                Esta a punto de eliminar uno o más usuarios, ¿Está seguro?
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="error"
                  @click="confirmDeletion = false"
                  outlined
                >
                  No
                </v-btn>
                <v-btn
                  color="success"
                  @click="onDelete"
                >
                  Si
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </v-card-text>

      <v-data-table
        item-key="name"
        v-model="currentRows"
        :headers="headers"
        :items="users"
        :options.sync="options"
        :server-items-length="totalItems"
        :loading="loading"
        :footer-props="{
          itemsPerPageOptions: [15, 30, 50, 100]
        }"
        show-select
      >
        <template v-slot:item.name="{ item }">
          <div class="d-flex align-center">
            <v-avatar>
              <img
                style="height: 32px; min-width: 32px; width: 32px;"
                :src="item.avatar"
                :alt="item.name"
              >
            </v-avatar>
            <div class="d-flex flex-column">
              <router-link :to="{ name: 'admin-users-view', params: { id: item.id } }" class="text--primary font-weight-semibold text-truncate cursor-pointer text-decoration-none">{{ item.name }}</router-link>
              <small>{{ item.role }}</small>
            </div>
          </div>
        </template>

        <template v-slot:item.enable="{ item }">
          <v-chip
            :color="getColor(item.enable)"
            small
            dark
          >
            {{ item.enable === 1 ? 'Activo' : 'Desactivado' }}
          </v-chip>
        </template>

        <template v-slot:item.createdAt="{ item }">
          <span>{{ $date(item.creaAt).format('ll') }}</span>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>
<script>
import { mdiAccountPlus, mdiAccountSearch, mdiDelete } from '@mdi/js'
import { mapActions, mapGetters } from 'vuex'
import { toQueryParams } from '@/utils'

export default {
  data: () => ({
    itemsPerPage: 15,
    loading: true,
    options: {
      sortBy: ['name'],
      sortDesc: [false],
    },
    currentRows: [],
    isDeleting: false,
    confirmDeletion: false,

    headers: [
      {
        text: 'Nombre',
        value: 'name'
      },
      {
        text: 'Email',
        value: 'email'
      },
      {
        text: 'Activo',
        value: 'enable'
      },
      {
        text: 'Fecha Registro',
        value: 'createdAt'
      }
    ],

    icons: {
      mdiAccountPlus,
      mdiAccountSearch,
      mdiDelete
    }
  }),
  watch: {
    options: {
      handler() {
        this.paginate()
      },
      deep: true
    }
  },
  computed: {
    ...mapGetters('user', [
      'users',
      'totalItems'
    ]),

    disabledDelete() {
      return (this.currentRows.length === 0
        || this.isDeleting === true);
    }
  },
  methods: {
    ...mapActions('user', [
      'getUsers',
      'deleteUsers'
    ]),

    onSearch(val) {
      this.loading = true;

      if (val.length >= 3) {
        this.paginate(val);
      }

      if (val.length === 0) {
        this.paginate()
      }
    },

    async paginate(search = null) {
      const {
        sortBy,
        sortDesc,
        page,
        itemsPerPage
      } = this.options;

      let order = null;
      if (sortDesc[0]) {
        order = sortDesc[0] === true ? 'desc' : 'asc';
      }

      const query = toQueryParams({
        sortBy: sortBy[0] || 'name',
        order: order || 'asc',
        size: itemsPerPage,
        type: 'system',
        search,
        page
      })

      await this.getUsers(query);
      this.loading = false;
    },

    async onDelete() {
      this.confirmDeletion = false;
      this.loading = true;
      this.isDeleting = true;

      const ids = this.currentRows.map(e => e.id);
      await this.deleteUsers(ids);
      await this.paginate();

      this.isDeleting = false;
    },

    getColor (enable) {
      return enable === 1 ? 'success' : 'error';
    },
  },
  created() {
    this.$store.commit('user/SET_USER', null)
  }
}
</script>
<style scoped>
  .user-search {
    max-width: 300px;
  }
</style>
