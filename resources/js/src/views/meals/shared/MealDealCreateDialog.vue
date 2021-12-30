<template>
  <v-dialog
    v-model="showDialog"
    width="600"
    persistent
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="primary"
        v-bind="attrs"
        v-on="on"
        :loading="isSaving"
        :disabled="isSaving"
        depressed
        small
      >
        <v-icon left>{{ icons.mdiFood }}</v-icon>
        Crear Oferta
      </v-btn>
    </template>
    <v-card>
      <v-card-title class="headline d-flex align-center">
       <span>Nueva Oferta</span> <small class="text--secondary ml-2">{{ meal.name }}</small>
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
                    v-model="mealDeal.price"
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
                    v-model="mealDeal.newPrice"
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
                    v-model="mealDeal.amount"
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
                  :return-value.sync="mealDeal.time"
                  persistent
                  width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <div class="d-flex flex-row-reverse mb-2">
                      <span class="text--secondary" v-show="mealDeal.time">Vence: el {{  `${$date().format('ll')} a las ${mealDeal.time}` }}</span>
                    </div>

                    <ValidationProvider name="Oferta" rules="required" v-slot="{ errors }">
                      <v-text-field
                        v-model="mealDeal.time"
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
                    v-model="mealDeal.time"
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
                      @click="$refs.timeDialog.save(mealDeal.time)"
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

    mealDeal: {
      price: 0.0,
      newPrice: 0.0,
      amount: 1,
      available: null,
      time: null,
    },

    errors: [],
    icons: {
      mdiFood,
      mdiClockTimeFourOutline
    }
  }),
  computed: {
    restaurant() {
      return this.$store.state.auth.user.restaurant;
    }
  },
  methods: {
    ...mapActions('mealDeal', ['createMealDeal']),

    async onSubmit() {
      const valid = await this.$refs.mealDealForm.validate();

      if (valid) {
        try {
          this.isSaving = true;

          const payload = {
            price: this.mealDeal.price,
            newPrice: this.mealDeal.newPrice,
            amount: this.mealDeal.amount,
            status: 'available',
            available: `${this.$date().format('YYYY-M-D')} ${this.mealDeal.time}`,
            time: this.mealDeal.time,
            mealId: this.meal.id
          };

          await this.createMealDeal({
            id: this.restaurant.id,
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
