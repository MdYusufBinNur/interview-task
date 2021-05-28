<template>
  <div>
    <v-list>
      <template v-for="item in items">
        <v-list-group
          v-if="item.children"
          :key="item.text"
          v-model="item.model"
          :append-icon="item.model ? item.icon : item['icon-alt']"
        >
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </template>
          <v-list-item
            v-for="(child, i) in item.children"
            :key="i"
            link
            :to="child.url"
            class="unlink"
          >
            <v-list-item-content>
              <v-list-item-title>
                {{ child.text }}
              </v-list-item-title>
            </v-list-item-content>
            <v-list-item-action v-if="child.icon">
              <v-icon>{{ child.icon }}</v-icon>
            </v-list-item-action>
          </v-list-item>
        </v-list-group>
        <v-list-item
          v-else
          :key="item.text"
          link
          :to="item.link"
        >
          <v-list-item-content>
            <v-list-item-title>
              {{ item.text }}
            </v-list-item-title>
          </v-list-item-content>
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
        </v-list-item>
      </template>
    </v-list>
  </div>
</template>

<script>
export default {
  data () {
    return {
      items: [
        {
          icon: 'mdi-chevron-up',
          'icon-alt': 'mdi-chevron-down',
          text: 'Category',
          model: true,
          children: [
            { icon: 'mdi-box-shadow', text: 'Create category', url: '/categories' },
            { icon: 'mdi-format-list-bulleted', text: 'category list' }
          ]
        },
        {
          icon: 'mdi-chevron-up',
          'icon-alt': 'mdi-chevron-down',
          text: 'Product',
          model: false,
          children: [
            { icon: 'mdi-bag-checked', text: 'Create Product', url: '/products' },
            { icon: 'mdi-format-list-bulleted', text: 'Product list' },
            { icon: 'mdi-android-messages', text: 'Product details' }
          ]
        },
        {
          icon: 'mdi-apps',
          'icon-alt': 'mdi-apps',
          text: 'Model',
          link: '/'
        }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
.unlink{
    text-decoration: none;
    color: black;
}
</style>
