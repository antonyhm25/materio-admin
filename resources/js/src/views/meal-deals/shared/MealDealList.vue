<template>
  <div id="meal-list">
    <v-data-table
      item-key="name"
      :headers="headers"
      :items="mealDeals"
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="loading"
      :footer-props="{
        itemsPerPageOptions: [10, 20, 30]
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
    </v-data-table>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import { toQueryParams } from '@/utils'

export default {
  data: () => ({
    loading: false,
    itemsPerPage: 10,

    options: {
      sortBy: ['available'],
      sortDesc: [false]
    },

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
        text: 'Vigencia',
        value: 'available'
      },
    ]
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
    ...mapGetters('user', ['currentUser']),
    ...mapGetters('mealDeal', ['mealDeals', 'totalItems']),

    photo() {
      return (photo) => {
        if (!photo) return  require('@/assets/images/avatars/no-food.png').default;
        return photo;
      }
    }
  },
  methods: {
    ...mapActions('mealDeal', ['getMealDeals']),

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
        sortBy: sortBy[0] || 'created_at',
        order: order || 'desc',
        size: itemsPerPage,
        search,
        page
      })

      await this.getMealDeals({ id: this.currentUser.restaurant.id, query });
      this.loading = false;
    },
  }
}
</script>
