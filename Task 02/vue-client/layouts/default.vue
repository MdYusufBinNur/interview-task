<template>
  <v-app>
    <Sidebar />
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Sidebar from '@/components/Includes/Sidebar'

export default {
  middleware: ['authCheck'],
  components: {
    Sidebar
  },
  data () {
    return {
      value: []
    }
  },
}
</script>
