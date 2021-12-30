<template>
  <div id="meals-page">
    <v-card>
      <v-card-title>Platillos del restaurante {{ auth.restaurant.name }}</v-card-title>

      <v-divider></v-divider>

      <v-card-text class="d-flex align-center flex-wrap">
        <v-text-field
          v-debounce:500="onSearch"
          class="me-3 mb-4 user-search"
          placeholder="Buscar"
          :prepend-inner-icon="icons.mdiFood"
          hide-details
          outlined
          dense
        ></v-text-field>

        <v-spacer></v-spacer>

        <div class="d-flex align-center flex-wrap">
          <v-btn
            class="mb-4 mr-2"
            color="primary"
            :to="{ name: 'module-meals-create' }"
            depressed
          >
            <v-icon left>{{ icons.mdiFood }}</v-icon>
            Agregar Platillo
          </v-btn>

          <v-dialog
            v-model="confirmDeletion"
            width="300"
            persistent
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                class="mb-4"
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
                Esta a punto de eliminar uno o más platillos, ¿Está seguro?
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
        item-key="id"
        v-model="currentRows"
        :headers="headers"
        :items="meals"
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
                :src="photo(item.photo)"
                :alt="item.name"
              >
            </v-avatar>
            <div class="d-flex flex-column">
              <router-link :to="{ name: 'module-meals-view', params: { id: item.id } }" class="text--primary font-weight-semibold text-truncate cursor-pointer text-decoration-none">{{ item.name }}</router-link>
              <small>General</small>
            </div>
          </div>
        </template>

        <template v-slot:item.description="{ item }">
          <span
            class="d-inline-block text-truncate"
            style="max-width: 150px;"
            :title="item.description"
          >
            {{ item.description }}
          </span>
        </template>

        <template v-slot:item.created_at="{ item }">
          <span>{{ $date(item.created_at).format('ll') }}</span>
        </template>

        <template v-slot:item.actions="{ item }">
           <meal-deal-create-dialog :meal="item" @on-save="" />
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>
<script>
import { mdiFood, mdiAccountSearch, mdiDelete, mdiFormatListBulletedSquare } from '@mdi/js'
import { mapActions, mapGetters } from 'vuex'
import { toQueryParams } from '@/utils'

import MealDealCreateDialog from './shared/MealDealCreateDialog.vue'

export default {
  components: {
    MealDealCreateDialog
  },
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
        text: 'Platillo',
        value: 'name'
      },
      {
        text: 'Restaurante',
        value: 'restaurant'
      },
      {
        text: 'Descripción',
        value: 'description'
      },
      {
        text: 'Fecha Registro',
        value: 'created_at'
      },
      {
        text: 'Acciones',
        value: 'actions',
        sortable: false
      }
    ],

    icons: {
      mdiFood,
      mdiAccountSearch,
      mdiDelete,
      mdiFormatListBulletedSquare
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
    ...mapGetters('meal', [
      'meals',
      'totalItems'
    ]),

    auth() {
      return this.$store.state.auth.user;
    },

    disabledDelete() {
      return (this.currentRows.length === 0
        || this.isDeleting === true);
    },

    photo() {
      return (photo) => {
        if (!photo) return  require('@/assets/images/avatars/no-food.png').default;
        return photo;
      }
    },

    canMealDeal() {
      return this.currentRows.length === 1
    },

    currrentMealDeal() {
      if (this.currentRows.length === 1) {
        return this.currentRows[0].id
      }

      return null
    }
  },
  methods: {
    ...mapActions('meal', ['getMeals', 'deleteMeals']),

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
        type: 'local',
        search,
        page
      })

      await this.getMeals({ id: this.auth.restaurant.id, query });
      this.loading = false;
    },

    async onDelete() {
      this.confirmDeletion = false;
      this.loading = true;
      this.isDeleting = true;

      const ids = this.currentRows.map(e => e.id);
      await this.deleteMeals({
        idRestaurant: this.auth.restaurant.id,
        ids
      });

      await this.paginate();

      this.isDeleting = false;
    }
  }
}
</script>
<style scoped>
  .user-search {
    max-width: 300px;
  }
</style>
