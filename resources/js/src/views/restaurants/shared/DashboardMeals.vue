<template>
  <v-card>
    <v-card-title class="align-start">
      <span class="font-weight-semibold">Estadistica del Restaurante</span>
    </v-card-title>

    <v-card-subtitle class="mb-8 mt-n5">
      <span class="font-weight-semibold text--primary me-1">Total {{ restaurantMeals.meals }} Platillos</span>
    </v-card-subtitle>

    <v-card-text>
      <v-row>
        <v-col
          cols="6"
          md="3"
          class="d-flex align-center"
        >
          <v-avatar
            size="50"
            :color="resolveStatisticsIconVariation ('Platillos').color"
            rounded
            class="elevation-1"
          >
            <v-icon
              dark
              color="white"
              size="30"
            >
              {{ resolveStatisticsIconVariation ('Platillos').icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              Platillos
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ restaurantMeals.meals }}
            </h3>
          </div>
        </v-col>

        <v-col
          v-for="data in statisticsData"
          :key="data.title"
          cols="6"
          md="3"
          class="d-flex align-center"
        >
          <v-avatar
            size="50"
            :color="resolveStatisticsIconVariation (data.title).color"
            rounded
            class="elevation-1"
          >
            <v-icon
              dark
              color="white"
              size="30"
            >
              {{ resolveStatisticsIconVariation (data.title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ data.title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ data.total }}
            </h3>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiTrendingUp, mdiFood } from '@mdi/js'
import { mapGetters, mapActions } from 'vuex';

export default {
  props: {
    idRestaurant: {
      type: Number,
      required: true
    }
  },
  computed: {
    ...mapGetters('dashboard', ['restaurantMeals']),

    statisticsData() {
      return this.restaurantMeals.types.map(e => {
        return { title: e.status, total: e.total }
      })
    }
  },
  methods: {
    ...mapActions('dashboard', ['getRestaurantMeals']),

    resolveStatisticsIconVariation(data) {
      if (data === 'Platillos') return { icon: mdiFood, color: 'primary' }
      if (data === 'Disponibles') return { icon: mdiTrendingUp, color: 'success' }
      if (data === 'Reservados') return { icon: mdiTrendingUp, color: 'warning' }
      if (data === 'Entregados') return { icon: mdiTrendingUp, color: 'info' }

      return { icon: mdiTrendingUp, color: 'success' }
    }
  },
  async created() {
    await this.getRestaurantMeals(this.idRestaurant)
  }
}
</script>
