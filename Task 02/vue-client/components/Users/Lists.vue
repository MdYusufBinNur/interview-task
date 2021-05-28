<template>
  <v-card>
    <v-card-title>
      <v-list-item-content>
        <v-card-text>
          User Lists
        </v-card-text>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field >
      </v-list-item-content>

    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="userLists"
      :search="search"
      :loading="loading"
    >
      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          color="blue"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          color="red"
          small
        >
          mdi-delete
        </v-icon>
      </template>

    </v-data-table>
  </v-card>
</template>

<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'Name',
            align: 'start',
            value: 'name',
          },
          { text: 'Email', value: 'email' },
          { text: 'Active', value: 'active' },
          { text: 'Action', value: 'actions' },
        ],
        desserts: [
          {
            name: 'Frozen Yogurt',
            email: 'super@gmail.com',
            active: 1,
          },
        ],
        userLists: [],
        loading: false
      }
    },
    created() {
      this.getUserList()
    },
    methods: {
      getUserList(){
        this.loading = true
        return this.$axios.get('/users')
        .then((response) =>{
          this.userLists = JSON.parse(JSON.stringify(response.data.data))
        })
        .catch((error)=>{})
        .finally(()=>{
          this.loading = false
        })
      }
    }
  }
</script>

<style scoped>

</style>
