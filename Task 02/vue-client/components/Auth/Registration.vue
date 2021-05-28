<template>
  <v-card
    flat
    class="pa-10"
  >
    <v-form
      ref="form"
    >
      <v-text-field
        v-model="name"
        :rules="nameRules"
        :counter="10"
        outlined
        shaped
        prepend-icon="mdi-account"
        label="Name"
        required

      ></v-text-field>
      <v-text-field
        v-model="email"
        outlined
        shaped
        prepend-icon="mdi-email"
        :error-messages="emailRules"
        label="E-mail"
        required
      ></v-text-field>
      <v-text-field
        v-model="password"
        outlined
        color="secondary"
        :rules="passwordRules"
        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        label="Password"
        :type="showPassword ? 'text' : 'password'"
        hide-details="auto"
        shaped
        required
        prepend-icon="mdi-lock"
        @click:append="showPassword = !showPassword"
      />

      <v-card-actions>
        <v-spacer/>
        <v-btn
          text
          large
          color="secondary"
          :loading="isLoading"
          @click="submit"
        >
          Register
        </v-btn>
        <v-spacer/>
      </v-card-actions>

      <v-card-actions class="text-center">
        <v-card-text
          class="pa-0"
          align="start"
        >
          Already have an account?
        </v-card-text>
        <v-spacer />
        <v-btn
          text
          large
          class="pa-0"
          color="primary"
          @click="signin()"
        >
          Sign In
        </v-btn>

      </v-card-actions>
    </v-form>
    <v-snackbar
      v-model="snackbar"
      :color="errorColor"
      top
      right
    >
      {{ errorMessage }}
      <template>
        <v-btn
          color="white"
          text
          @click="false"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </v-card>
</template>

<script>
  import validationMixin from "../../mixins/validationMixin";

  export default {
    mixins: [validationMixin],
    data: () => ({
      name: '',
      email: '',
      password: '',
      isLoading : false,
      showPassword: false,
      emailRules: [
        v => !!v || 'Email is required'
      ],

      passwordRules: [
        v => !!v || 'Password is required'
      ],

      nameRules: [
        v => !!v || 'Password is required'
      ],
      snackbar: false,
      errorMessage: '',
      errorColor: ''
    }),

    computed: {
      registerInfo () {
        return {
          email: this.email,
          password: this.password,
          name: this.name
        }
      }
    },

    methods: {
      validate () {
        this.$refs.form.validate()
      },
      submit () {
        this.isLoading = true
        if (!this.$refs.form.validate()) {
          this.errorMessage = 'Please input valid data'
          this.errorColor = 'error'
          this.snackbar = true
          this.isLoading = false
        } else {
          this.$axios.post('/register', this.registerInfo)
            .then((response) => {
              console.log(response.data.error)
              if (response.data.error === false){
                this.signin()
                this.clear()
              }
            })
            .catch((error) => {
              console.log('Err',error)
              this.errorMessage = 'Something Went Wrong.This happened often if Account Exist'
              this.errorColor = 'error'
              this.snackbar = true
              // eslint-disable-next-line no-console
            })
            .finally(() => {
              this.isLoading = false
            })
        }
      },
      clear () {
        this.name = ''
        this.email = ''
        this.password = ''

      },
      signin(){
        this.$router.push('/')
      }
    },
  }
</script>

<style scoped>

</style>
