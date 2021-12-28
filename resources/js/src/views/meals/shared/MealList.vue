<template>
  <div id="meal-list">
    <v-data-table
      item-key="name"
      v-model="currentRows"
      :headers="headers"
      :items="items"
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
            <router-link :to="{ name: 'admin-restaurants-view', params: { id: item.id } }" class="text--primary font-weight-semibold text-truncate cursor-pointer text-decoration-none">{{ item.name }}</router-link>
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
export default {
  props: {
    items: {
      type: Array,
      default: () => []
    },
    totalItems: {
      type: Number,
      default: () => 0
    },
    loading: {
      type: Boolean,
      default: () => true
    },
    itemsPerPage: {
      type: Number,
      default: () => 15
    }
  },
  data: () => ({
    currentRows: [],

    options: {
      sortBy: ['name'],
      sortDesc: [false]
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
    currentRows(val) {
      this.$emit('on-selected-rows', val)
    }
  },
  computed: {
    photo() {
      return (photo) => {
        if (!photo) return  require('@/assets/images/avatars/no-food.png').default;
        return photo;
      }
    }
  }
}
</script>
