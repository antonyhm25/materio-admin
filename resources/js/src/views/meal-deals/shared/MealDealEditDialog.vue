<template>
  <v-dialog
    v-model="showDialog"
    width="600"
    persistent
  >
    <template v-slot:activator="{ on, attrs }">
      <v-list-item
        v-bind="attrs"
        v-on="on"
      >
        <v-list-item-title class="cursor-pointer">Modificar</v-list-item-title>
      </v-list-item>
    </template>
    <v-card>
      <v-card-title class="headline d-flex align-center">
       <span>Editar Oferta</span> <small class="text--secondary ml-2">{{ meal.meal }}</small>
      </v-card-title>

      <v-card-text>
        <alert-form :errors="errors" />
      </v-card-text>

      <ValidationObserver ref="mealDealForm">
        <v-form @submit.prevent="onSubmit">
          <v-card-text>
            <v-row>
              <v-col cols="12" md="4">
                <ValidationProvider name="Precio" rules="required|double" v-slot="{ errors }">
                  <v-text-field
                    v-model="meal.price"
                    label="Precio"
                    type="number"
                    :error-messages="errors"
                    outlined
                    dense
                  ></v-text-field>
                </ValidationProvider>
              </v-col>

              <v-col cols="12" md="4">
                <ValidationProvider name="Precio Oferta" rules="double" v-slot="{ errors }">
                  <v-text-field
                    v-model="meal.newPrice"
                    label="Precio de Oferta"
                    type="number"
                    :error-messages="errors"
                    outlined
                    dense
                  ></v-text-field>
                </ValidationProvider>
              </v-col>

              <v-col cols="12" md="4">
                <ValidationProvider name="Cantidad" rules="integer" v-slot="{ errors }">
                  <v-text-field
                    v-model="meal.amount"
                    label="Cantidad"
                    type="number"
                    :error-messages="errors"
                    outlined
                    dense
                  ></v-text-field>
                </ValidationProvider>
              </v-col>

              <v-col cols="12">
                <v-dialog
                  ref="timeDialog"
                  v-model="showTimeDialog"
                  :return-value.sync="meal.time"
                  persistent
                  width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <div class="d-flex flex-row-reverse mb-2">
                      <span class="text--secondary" v-show="meal.time">Vence: el {{  `${$date(meal.available).format('lll')}` }}</span>
                    </div>

                    <ValidationProvider name="Oferta" rules="required" v-slot="{ errors }">
                      <v-text-field
                        v-model="meal.time"
                        v-bind="attrs"
                        v-on="on"
                        label="Oferta valida hasta"
                        :prepend-icon="icons.mdiClockTimeFourOutline"
                        :error-messages="errors"
                        readonly
                        dense
                        outlined
                      ></v-text-field>
                    </ValidationProvider>
                  </template>
                  <v-time-picker
                    v-if="showDialog"
                    v-model="meal.time"
                    full-width
                  >
                    <v-spacer></v-spacer>
                    <v-btn
                      text
                      color="primary"
                      @click="showTimeDialog = false"
                    >
                      Cancelar
                    </v-btn>
                    <v-btn
                      text
                      color="primary"
                      @click="$refs.timeDialog.save(meal.time)"
                    >
                      OK
                    </v-btn>
                  </v-time-picker>
                </v-dialog>
              </v-col>
            </v-row>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="error"
              @click="showDialog = false"
              outlined
            >
              Cancelar
            </v-btn>
            <v-btn
              color="success"
              type="submit"
            >
              Ofertar
            </v-btn>
          </v-card-actions>
        </v-form>
      </ValidationObserver>
    </v-card>
  </v-dialog>
</template>
<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { mdiFood, mdiClockTimeFourOutline } from '@mdi/js'
import { mapActions } from 'vuex';
import { getErrorFields } from '@/utils'

import AlertForm from '@/components/AlertForm'

export default {
  props: {
    meal: {
      type: Object,
     required: true
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    AlertForm
  },
  data: () => ({
    showDialog: false,
    showTimeDialog: false,
    isSaving: false,

    errors: [],
    icons: {
      mdiFood,
      mdiClockTimeFourOutline
    }
  }),
  watch: {
    'meal.time'(val) {
      this.meal.available = `${this.$date().format('YYYY-M-D')} ${val}`
    }
  },
  computed: {
    restaurant() {
      return this.$store.state.auth.user.restaurant;
    }
  },
  methods: {
    ...mapActions('mealDeal', ['updateMealDeal']),

    async onSubmit() {
      const valid = await this.$refs.mealDealForm.validate();

      if (valid) {
        try {
          this.isSaving = true;

          const payload = {
            price: this.meal.price,
            newPrice: this.meal.newPrice,
            amount: this.meal.amount,
            available: this.meal.available,
            time: this.meal.time,
            mealId: this.meal.id
          };

          await this.updateMealDeal({
            idRestaurant: this.restaurant.id,
            id: this.meal.id,
            payload
          });

          this.showDialog = false
        } catch (error) {
          if (error.status === 422) {
            this.errors = getErrorFields(error.data);
          }
        } finally {
          this.isSaving = false;
        }
      }
    },
  }
}
</script>
