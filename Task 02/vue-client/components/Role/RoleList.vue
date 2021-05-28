<template>
  <v-card>
    <v-card-title>
      <v-list width="100%">
        <v-list-item>
          <v-list-item-title>
            Role List
          </v-list-item-title>
          <v-list-item-action>
            <v-dialog
              v-model="dialog"
              max-width="500px"
              persistent
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="red darken-3"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  New Role
                </v-btn>
              </template>
              <v-card flat elevation="8" light>
                <v-card-title class="py-3 secondary white--text">
                  <span class="headline">Create New Role</span>
                  <v-spacer />
                  <v-icon
                    fab
                    color="white"
                    :disabled="loadingSaveData"
                    @click="close"
                  >
                    mdi-close
                  </v-icon>
                </v-card-title>

                <v-form ref="form">
                  <v-container grid-list-sm>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                        lg="12"
                      >
                        <v-text-field
                          v-model="editedItem.name"
                          :error-messages="errorMessages.title"
                          :rules="validationRules.title"

                          outlined
                          hide-details="auto"
                          label="Role Name"
                        />
                      </v-col>
                    </v-row>
                  </v-container>
                </v-form>

                <v-card-actions class="pb-5">
                  <v-spacer />
                  <v-btn
                    color="primary"
                    small
                    :disabled="loadingSaveData"
                    @click="close"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="secondary"
                    small
                    :loading="loadingSaveData"
                    @click="save"
                  >
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-list-item-action>
        </v-list-item>
      </v-list>

    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="roleLists"
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
  import validationMixin from "../../mixins/validationMixin";

  export default {
    data () {
      return {
        loadingSaveData: false,
        singleSelect: false,
        dialog: false,
        dialogDelete: false,
        search: '',
        headers: [
          {
            text: 'Name',
            align: 'start',
            value: 'name',
          },
          { text: 'Action', value: 'actions' },
        ],
        desserts: [
          {
            name: 'Frozen Yogurt',
            email: 'super@gmail.com',
            active: 1,
          },
        ],
        roleLists: [],
        loading: false,
        editedItem: {
          name: ''
        },
        defaultItem: {
          name: ''
        },

        errorMessages: {
          name: ''
        },
      }
    },
    mixins: [validationMixin],
    created() {
      this.getRoleList()
    },
    methods: {
      getRoleList(){
        this.loading = true
        return this.$axios.get('/role')
          .then((response) =>{
            this.roleLists = JSON.parse(JSON.stringify(response.data.data))
          })
          .catch((error)=>{})
          .finally(()=>{
            this.loading = false
          })
      },
      save(){
        this.loadingSaveData = true
        return this.$axios.post('/role', this.editedItem)
        .then((response) => {
          this.$toast.success(response.data.message)
          this.roleLists.push(JSON.parse(JSON.stringify(response.data.data)))
        })
        .catch((error) => {
          this.$toast.error('Something went wrong !!')
        })
        .finally(() => {
          this.close()
          this.loadingSaveData = false
        })
      },
      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
        })
        this.$refs.form.reset()
      },

    }
  }
</script>

<style scoped>

</style>
