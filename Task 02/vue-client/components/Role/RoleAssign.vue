<template>
  <v-card>
    <v-card-title>
      <v-list width="100%">
        <v-list-item>
          <v-list-item-title>
            User Role
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
                  Assign A Role
                </v-btn>
              </template>
              <v-card flat elevation="8" light>
                <v-card-title class="py-3 secondary white--text">
                  <span class="headline">Assign</span>
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
                        <UserSelect
                          ref="user"
                          :error-messages="errorMessages.admin_id"
                          :rules="validationRules.required"
                          outlined
                          color="blue darken-4"
                          hide-details="auto"
                          item-text="name"
                          item-value="id"
                          label="Select User"
                          return-id
                          @setSelectedUser="getSelectedUser"
                        />
                      </v-col>
                      <v-col
                        cols="12"
                        sm="12"
                        md="12"
                        lg="12"
                      >
                        <RoleSelect
                          ref="role"
                          :error-messages="errorMessages.role_id"
                          :rules="validationRules.required"
                          outlined
                          color="blue darken-4"
                          hide-details="auto"
                          item-text="name"
                          item-value="id"
                          label="Select Role"
                          return-id
                          @setSelectedRole="getSelectedRole"
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
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>

    </v-data-table>
    <v-dialog v-model="dialogDelete" max-width="300px" :persistent="loadingDelete">
      <v-card>
        <div class="text-center pt-5">
          <v-icon color="red" size="45">
            mdi-delete-outline
          </v-icon>
          <i class="fas fa-trash-alt" />
        </div>
        <v-card-title class="text-center">
          Are you sure you detach role from this user?
        </v-card-title>
        <v-divider />
        <v-card-actions>
          <v-spacer />
          <v-btn :disabled="loadingDelete" small text color="secondary" @click="closeDelete">
            No
          </v-btn>
          <v-btn :loading="loadingDelete" small text color="primary" @click="confirmDelete">
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
        loadingDelete: false,
        search: '',
        headers: [
          {
            text: 'User',
            align: 'start',
            value: 'admin',
          },
          { text: 'Role', value: 'role' },
          { text: 'Action', value: 'actions' },
        ],
        roleLists: [],
        loading: false,
        editedItem: {
          admin_id: '',
          role_id: '',
        },
        defaultItem: {
          admin_id: '',
          role_id: '',
        },
        errorMessages: {
          admin_id: '',
          role_id: '',
        },
      }
    },
    components: {
      UserSelect: () => import('@/components/Select/UserSelect'),
      RoleSelect: () => import('@/components/Select/RoleSelect'),
    },
    mixins: [validationMixin],
    watch:{

      dialogDelete (val) {
        val || this.closeDelete()
      },

    },
    created() {
      this.getRoleList()
    },
    methods: {
      getRoleList(){
        this.loading = true
        return this.$axios.get('/userWithRole')
          .then((response) =>{
            console.log(response.data)
            this.roleLists = JSON.parse(JSON.stringify(response.data))
          })
          .catch((error)=>{})
          .finally(()=>{
            this.loading = false
          })
      },
      save(){
       this.loadingSaveData = true
        return this.$axios.post('/assign', this.editedItem)
          .then((response) => {
            this.$toast.success(response.data.message)
            this.getRoleList()
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
      getSelectedUser (value) {
        this.editedItem.admin_id = value
      },
      getSelectedRole (value) {
        this.editedItem.role_id = value
      },
      deleteItem (item) {
        this.dialogDelete = true
        const editedIndex = this.roleLists.indexOf(item)
        this.editedItem.admin_id = this.roleLists[editedIndex].admin_id
        this.editedItem.role_id = this.roleLists[editedIndex].role_id
      },
      closeDelete(){
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
        })
      },
      confirmDelete(){
        this.loadingDelete = true
        return this.$axios.post('/removeRole', this.editedItem)
        .then((response) => {
          this.$toast.success(response.data.message)
          this.getRoleList()
        })
        .catch((error) => {
          this.$toast.error('Something Went Wrong')
        })
        .finally(() => {

          this.closeDelete()
        })
      }
    }
  }
</script>

<style scoped>

</style>
