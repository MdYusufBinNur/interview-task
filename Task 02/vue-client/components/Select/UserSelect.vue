<template>
  <div>
    <v-autocomplete
      v-model="selected"
      v-debounce:800ms="debouncedInitData"
      :items="userLists"
      :no-data-text="noDataText"
      :loading="loading"
      :search-input.sync="name"
      :debounce-events="['onclick', 'oninput', 'onkeydown']"
      v-bind="$attrs"
      @change="onChangeSelectedItem(selected)"
    />
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  export default {
    props: {
      initLists: {
        type: Boolean,
        required: false,
        default () {
          return true
        }
      }
    },
    data () {
      return {
        selected: null,
        name: null,
        userLists: [],
        loading: false
      }
    },

    computed: {
      ...mapGetters({
        users: 'user/getUsers'
      }),

      searchQuery () {
        return (
          `${this.name !== '' && this.name !== null ? '?name=' + this.name : ''}`
        )
      },

      noDataText () {
        let text = ''
        if (this.loading) {
          text = 'Data is Loading...'
        } else {
          text = 'No data available'
        }
        return text
      }
    },

    watch: {
      users: {
        handler (nv, ov) {
          if (this.users.length) {
            this.userLists = JSON.parse(JSON.stringify(this.users))
          }
        },
        immediate: true,
        deep: true
      }
    },

    created () {
      if (this.users.length <= 0 && this.initLists) {
        this.initialize(this.searchQuery)
      }
    },

    methods: {
      onChangeSelectedItem (item) {
        this.$emit('setSelectedUser', item)
      },

      async initialize(query) {
        this.loading = true
        await this.$store.dispatch('user/initUser')
          .then((response) => {
            console.log(response)
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message)
          })
          .finally(() => {
            this.loading = false
          })
      },

      debouncedInitData (v) {
        this.name = v
        this.initialize(this.searchQuery)
      },

      setId (id) {
        const found = this.userLists.find(el => el.id === id)
        if (found) {
          this.selected = parseInt(id, 10)
        } else {
          this.initialize('?id=' + id)
          this.selected = parseInt(id, 10)
        }
      }
    }
  }
</script>
