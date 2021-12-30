<template>
  <div id="meal-list">
    <v-data-table
      item-key="name"
      :headers="headers"
      :items="meals"
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="loading"
      :footer-props="{
        itemsPerPageOptions: [10, 20, 30]
      }"
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
            <span class="text--primary font-weight-semibold text-truncate cursor-pointer text-decoration-none">{{ item.name }}</span>
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

      <template v-slot:item.createdAt="{ item }">
        <span>{{ $date(item.creaAt).format('ll') }}</span>
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
      sortBy: ['createdAt'],
      sortDesc: [true]
    },

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
        value: 'createdAt'
      }
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
    ...mapGetters('meal', ['meals', 'totalItems']),

    photo() {
      return (photo) => {
        if (!photo) return  require('@/assets/images/avatars/no-food.png').default;
        return photo;
      }
    }
  },
  methods: {
    ...mapActions('meal', ['getMeals']),

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
        search,
        page
      })

      await  this.getMeals({ id: this.currentUser.restaurant.id, query });
      this.loading = false;
    },
  }
}
</script>
