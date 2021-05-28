<template>
  <v-card class="mx-auto" max-width="434" tile>
    <v-img height="100%" src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"></v-img>
    <v-col>
      <v-avatar size="100" style="position:absolute; top: 130px">
        <v-img src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"></v-img>
      </v-avatar>
    </v-col>
    <v-list-item color="rgba(0, 0, 0, .4)">
      <v-list-item-content>
        <v-list-item-title class="title pa-2" v-text="profileName"></v-list-item-title>
        <v-list-item-subtitle class="pa-2" v-text="roleName"></v-list-item-subtitle>
      </v-list-item-content>
      <v-list-item-icon>
        <v-icon right large>
          mdi-pencil
        </v-icon>
      </v-list-item-icon>
    </v-list-item>
  </v-card>
</template>

<script>
  export default {
    name: "Profile",
    data() {
      return {
        profileData: {},
        userRoles: [],
        loading: false,
        profileName: '',
        roleName: ''
      }
    },
    created() {
      this.getProfile()
    },
    methods: {
      getProfile(){
        return this.$axios.get('/profile')
        .then((response) => {
          Object.assign(this.profileData, JSON.parse(JSON.stringify(response.data.data)))
          this.userRoles = JSON.parse(JSON.stringify(response.data.data.roles))

          this.profileName = this.profileData.name
          this.roleName = 'Role : ' + this.userRoles[0].name
        })
        .catch((error) => {})
        .finally(() => {
          this.loading = false
        })
      }
    }
  }
</script>

<style scoped>

</style>
