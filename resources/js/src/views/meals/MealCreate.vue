<template>
  <div id="meals-create">
    <v-row>
      <v-col
        cols="12"
      >
        <v-card>
          <v-card-title>Nuevo Platillo</v-card-title>

          <v-divider></v-divider>

          <meal-form
            :meal="meal"
            :errors="errors"
            :is-saving="isSaving"
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
import { mapActions } from 'vuex';
import { getErrorFields } from '@/utils'
import MealForm from './shared/MealForm.vue';

export default {
  components: {
    MealForm
  },
  data: () => ({
    isSaving: false,
    meal: {
      name: '',
      description: '',
    },
    photo: null,
    errors: []
  }),
  computed: {
    restaurant() {
      return this.$store.state.auth.user.restaurant;
    }
  },
  methods: {
    ...mapActions('meal', ['createMeal', 'uploadPhoto']),

    async onSubmit() {
      try {
        this.isSaving = true;



        const id = await this.createMeal({
          idRestaurant: this.restaurant.id,
          payload: this.meal
        });

        if (this.photo) {
          await this.uploadPhoto({
            idRestaurant: this.restaurant.id,
            file: this.photo,
            id
          })
        }

        this.$router.push({ name: 'module-meals-view', params: { id } })
      } catch (error) {
        if (error.status === 422) {
          this.errors = getErrorFields(error.data);
        }
      } finally {
        this.isSaving = false;
      }
    },

    onPhoto(file) {
      this.photo = file
    }
  }
}
</script>
