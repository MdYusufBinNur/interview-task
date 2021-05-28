<template>
  <div>
    <v-autocomplete
      v-model="selected"
      v-debounce:800ms="debouncedInitData"
      :items="roleLists"
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
        roleLists: [],
        loading: false
      }
    },

    computed: {
      ...mapGetters({
        roles: 'role/getRoles'
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
      roles: {
        handler (nv, ov) {
          if (this.roles.length) {
            this.roleLists = JSON.parse(JSON.stringify(this.roles))
          }
        },
        immediate: true,
        deep: true
      }
    },

    created () {
      if (this.roles.length <= 0 && this.initLists) {
        this.initialize(this.searchQuery)
      }
    },

    methods: {
      onChangeSelectedItem (item) {
        this.$emit('setSelectedRole', item)
      },

      async initialize(query) {
        this.loading = true
        await this.$store.dispatch('role/initRole')
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
        const found = this.roleLists.find(el => el.id === id)
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
