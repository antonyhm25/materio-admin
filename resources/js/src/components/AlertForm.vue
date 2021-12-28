<template>
  <v-alert
    v-if="errors.length > 0"
    outlined
    color="error"
    prominent
    border="left"
  >
    <ul>
      <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
    </ul>
  </v-alert>
</template>
<script>
export default {
  props: {
    errors: {
      type: Array,
      required: true
    }
  }
}
</script>
