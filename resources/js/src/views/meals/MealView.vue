<template>
  <div id="meals-create">
    <v-row>
      <v-col
        cols="12"
        md="4"
      >
        <restaurant-profile :user="auth" />
      </v-col>
      <v-col
        cols="12"
        md="8"
      >
        <v-card>
          <v-card-title>
            Platillo {{ currentMeal.name }}
            <v-spacer></v-spacer>
            <v-btn  color="primary">
              Nueva Oferta
            </v-btn>
          </v-card-title>

          <v-divider></v-divider>

          <meal-form
            :meal="currentMeal"
            :errors="errors"
            :is-saving="isSaving"
            :meal-photo="currentMeal.photo"
            @on-submit="onSubmit"
            @on-photo="onPhoto"
          />
        </v-card>
      </v-col>

      <v-col
        cols="12"
        md="4"
      >
      </v-col>
    </v-row>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
import { getErrorFields } from '@/utils'
import MealForm from './shared/MealForm.vue';
import RestaurantProfile from '@/views/restaurants/shared/RestaurantProfile.vue'

export default {
  components: {
    MealForm,
    RestaurantProfile
  },
  data: () => ({
    isSaving: false,
    errors: [],
    photo: null
  }),
  computed: {
    ...mapGetters('meal', ['currentMeal']),

    auth() {
      return this.$store.state.auth.user;
    },

    restaurant() {
      return this.$store.state.auth.user.restaurant;
    }
  },
  methods: {
    ...mapActions('meal', ['updateMeal', 'getMeal', 'uploadPhoto']),

    async onSubmit() {
      try {
        this.isSaving = true;

        await this.updateMeal({
          idRestaurant: this.restaurant.id,
          id: this.currentMeal.id,
          payload: this.currentMeal
        });

        if (this.photo) {
          await this.uploadPhoto({
            idRestaurant: this.restaurant.id,
            file: this.photo,
            id: this.currentMeal.id
          })
        }
      } catch (error) {
        console.log(error);
        if (error.status === 422) {
          this.errors = getErrorFields(error.data);
        }
      } finally {
        this.isSaving = false;
      }
    },

    onPhoto(file) {
      this.photo = file;
    }
  },
  async created() {
    const id = this.$route.params.id;
    await this.getMeal({ idRestaurant: this.restaurant.id, id })
  }
}
</script>
