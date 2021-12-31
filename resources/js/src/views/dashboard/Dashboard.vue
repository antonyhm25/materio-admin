<template>
  <v-row>
    <v-col cols="12">
      <v-card class="mb-7">
        <v-divider></v-divider>
        <dashboard-meals />
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import DashboardMeals from '@/views/restaurants/shared/DashboardMeals.vue';

export default {
  components: {
    DashboardMeals
  }
}
</script>
