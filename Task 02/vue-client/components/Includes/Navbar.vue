<template>
  <div>
    <v-app-bar
      fixed
      app
      light
      height="100"
      flat
    >
      <v-spacer />
      <v-img
        class="mx-2"
        contain
        aspect-ratio="4"
      />
      <v-spacer />
    </v-app-bar>
  </div>

</template>

<script>
export default {
  data () {
    return {
      title: 'Vuetify.js'
    }
  }
}

</script>

<style scoped>

</style>
