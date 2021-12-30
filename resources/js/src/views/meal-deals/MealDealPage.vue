<template>
  <div id="meal-deals-page">
    <v-card>
      <v-card-title class="pb-0">Ofertas</v-card-title>

      <v-row class="px-2 ma-0">
        <v-col cols="12" md="8"></v-col>
        <v-col cols="12" md="2">
          <v-select
            v-model="currentVigence"
            label="Vigencia"
            class="filters-select"
            item-text="name"
            item-value="value"
            :items="filterVigences"
            dense
            outlined
          ></v-select>
        </v-col>
        <v-col cols="12" md="2">
          <v-select
            v-model="currentStatus"
            label="Estado"
            class="filters-select"
            item-text="name"
            item-value="value"
            :items="filterStatus"
            dense
            outlined
          ></v-select>
        </v-col>
      </v-row>

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
      </v-card-text>

      <v-data-table
        item-key="id"
        :headers="headers"
        :items="mealDeals"
        :options.sync="options"
        :server-items-length="totalItems"
        :loading="loading"
        :footer-props="{
          itemsPerPageOptions: [15, 30, 50, 100]
        }"
      >
        <template v-slot:item.folio="{ item }">
          <span class="text-uppercase">{{ item.folio }}</span>
        </template>

        <template v-slot:item.name="{ item }">
          <div class="d-flex align-center">
            <v-avatar>
              <img
                style="height: 32px; min-width: 32px; width: 32px;"
                :src="photo(item.photo)"
                :alt="item.meal"
              >
            </v-avatar>
            <div class="d-flex flex-column">
              <span class="text--primary font-weight-semibold text-truncate cursor-pointer text-decoration-none">{{ item.meal }}</span>
              <small>General</small>
            </div>
          </div>
        </template>

        <template v-slot:item.price="{ item }">
          <span :class="[item.newPrice > 0 ? 'text-decoration-line-through' : '']">${{ item.price }}</span>
          <span v-if="item.newPrice > 0"> a ${{ item.newPrice }}</span>
        </template>

        <template v-slot:item.available="{ item }">
          <span class="text-capitzalize">{{ $date().to($date(item.available)) }}</span>
        </template>

        <template v-slot:item.status="{ item }">
          <span class="text-capitalize">{{ statusText(item.status) }}</span>
        </template>

        <template v-slot:item.actions="{ item }">
          <div class="text-center">
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  v-bind="attrs"
                  v-on="on"
                  small
                >
                  Opciones
                </v-btn>
              </template>
              <v-list dense>
                <v-dialog
                  v-if="item.status === 'available'"
                  v-model="confirmDeletion"
                  width="400"
                  persistent
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-list-item
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-list-item-title class="cursor-pointer">Eliminar</v-list-item-title>
                    </v-list-item>
                  </template>
                  <v-card>
                    <v-card-title class="headline">
                      Confirmación
                    </v-card-title>
                    <v-card-text>
                      Esta a punto de eliminar la oferta, ¿Está seguro?
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
                        @click="onDelete(item)"
                        :loading="isDeleting"
                        :disabled="isDeleting"
                      >
                        Si
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>

                <v-list-item v-if="item.status === 'reserved'" @click="onDelivery(item)">
                  <v-list-item-title class="cursor-pointer">Entregar</v-list-item-title>
                </v-list-item>

                <meal-deal-edit-dialog v-if="item.status === 'available'" :meal="item" />
              </v-list>
            </v-menu>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>
<script>
import { mdiFood, mdiAccountSearch, mdiDelete } from '@mdi/js'
import { mapActions, mapGetters } from 'vuex'
import { toQueryParams } from '@/utils'

import MealDealEditDialog from './shared/MealDealEditDialog.vue';

export default {
  components: {
    MealDealEditDialog
  },
  data: () => ({
    itemsPerPage: 15,
    loading: true,
    options: {
      sortBy: ['available'],
      sortDesc: [false],
    },

    isDeleting: false,
    confirmDeletion: false,

    headers: [
      {
        text: 'Folio',
        value: 'folio',
        sortable: false
      },
      {
        text: 'Platillo',
        value: 'name'
      },
      {
        text: 'Precio',
        value: 'price'
      },
      {
        text: 'Reservo',
        value: 'user'
      },
      {
        text: 'Vigencia',
        value: 'available'
      },
      {
        text: 'Estado',
        value: 'status'
      },
      {
        text: 'Acciones',
        value: 'actions'
      }
    ],

    currentVigence: 0,
    filterVigences: [
      { name: 'Vigentes', value: 0 },
      { name: 'Expirados', value: 1 },
      { name: 'Todos', value: null },
    ],

    currentStatus: null,
    filterStatus: [
      { name: 'Disponibles', value: 'available' },
      { name: 'Apartados', value: 'reserved' },
      { name: 'Entregados', value: 'delivered' },
      { name: 'Todos', value: null },
    ],

    icons: {
      mdiFood,
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
    },

    async currentStatus() {
      await this.paginate()
    },

    async currentVigence() {
      await this.paginate()
    }
  },
  computed: {
    ...mapGetters('mealDeal', [
      'mealDeals',
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
        return `/storage/${photo}`;
      }
    },

    statusText() {
      return (status) => {
        if (status === 'available') {
          return 'disponible';
        } else if (status === 'reserved') {
          return 'reservado';
        } else {
          return 'entregado';
        }
      }
    }
  },
  methods: {
    ...mapActions('mealDeal', [
      'getMealDeals',
      'deleteMealDeal',
      'delivery'
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
        sortBy: sortBy[0] || 'createdAt',
        order: order || 'desc',
        size: itemsPerPage,
        status: this.currentStatus,
        expired: this.currentVigence,
        search,
        page
      })

      await this.getMealDeals({ id: this.auth.restaurant.id, query });
      this.loading = false;
    },

    async onDelete(item) {
      this.isDeleting = true;

      await this.deleteMealDeal({
        idRestaurant: this.auth.restaurant.id,
        id: item.id
      });

      this.confirmDeletion = false;
      this.isDeleting = false;
      this.loading = true;

      await this.paginate();
    },

    async onDelivery(meal) {
      this.loading = true;

      await this.delivery({
        idRestaurant: this.auth.restaurant.id,
        id: meal.id,
        payload: {
          price: meal.price,
          newPrice: meal.newPrice,
          amount: meal.amount,
          available: meal.available,
          time: meal.time,
          status: 'delivered',
        }
      });

      await this.paginate();
    }
  },
}
</script>
<style scoped>
  .user-search {
    max-width: 300px;
  }
</style>
