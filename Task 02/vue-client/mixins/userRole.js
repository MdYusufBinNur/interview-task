import { mapGetters } from 'vuex'
// eslint-disable-next-line import/no-duplicates
import db from '@/assets/logo.png'
// eslint-disable-next-line import/no-duplicates
import logo from '@/static/icon.png'
// eslint-disable-next-line camelcase
import icon_db from '@/static/logo.png'

const ROLE_ADMIN_SUPER = 1
const ROLE_ADMIN_STANDARD = 2
const ROLE_ADMIN_LIMITED = 3

const ROLE_STAFF_STANDARD = 4
const ROLE_STAFF_LIMITED = 5

const ROLE_CUSTOMER_STAR = 6
const ROLE_CUSTOMER_GENERAL = 7

const ROLE_VENDOR_STANDARD = 8
const ROLE_VENDOR_LIMITED = 9

export default {
  data () {
    return {
      db,
      logo,
      icon_db
    }
  },

  methods: {
    rolesTitle () {
      return this.roles.map(role => role.role.title)
    },

    isAdmin () {
      return this.hasRole([ROLE_ADMIN_SUPER, ROLE_ADMIN_STANDARD, ROLE_ADMIN_LIMITED])
    },

    isVendor () {
      return this.hasRole([ROLE_VENDOR_LIMITED, ROLE_VENDOR_STANDARD])
    },

    isCutomer () {
      return this.hasRole([ROLE_CUSTOMER_GENERAL, ROLE_CUSTOMER_STAR], true)
    },

    isStaff () {
      return this.hasRole([ROLE_STAFF_STANDARD, ROLE_STAFF_LIMITED], true)
    },

    isSuperAdmin () {
      return this.hasRole(ROLE_ADMIN_SUPER)
    },

    isStandardAdmin () {
      return this.hasRole(ROLE_ADMIN_STANDARD)
    },

    isLimitedAdmin () {
      return this.hasRole(ROLE_ADMIN_LIMITED)
    },

    isStandardStaff () {
      return this.hasRole(ROLE_STAFF_STANDARD, true)
    },

    isLimitedStaff () {
      return this.hasRole(ROLE_STAFF_LIMITED, true)
    },

    isCustomerStar () {
      return this.hasRole(ROLE_CUSTOMER_STAR)
    },

    isCustomerGeneral () {
      return this.hasRole(ROLE_CUSTOMER_GENERAL)
    },

    isVendorStandard () {
      return this.hasRole(ROLE_VENDOR_STANDARD)
    },

    isVendorLimited () {
      return this.hasRole(ROLE_VENDOR_LIMITED)
    },

    hasRole (hayStack, withPropertyCheck = false) {
      return this.roles.some((role) => {
        return Array.isArray(hayStack)
          ? hayStack.includes(role.role.id)
          : role.role.id === hayStack
      })
    },

    /*
        get ids of resident, unit, user and property from userData
        here type will be for 'id', 'resdientId' 'propertyId' and 'unitId'
    */
    getIds (data, type) {
      const getIds = data.map((item) => {
        let ids = []
        ids = item[`${type}`]
        return ids
      })
      return Array.from(new Set(getIds)).join(',')
    }
  },
  computed: {
    ...mapGetters({
      userData: 'auth/userData'
    }),

    profilePic () {
      return this.userData && this.userData.profilePic && this.userData.profilePic.fileUrl ? this.userData.profilePic : null
    },

    roles () {
      return this.userData ? this.userData.roles : []
    },

    userRoleIds () {
      return this.userData ? this.userData.roles.map(a => a.roleId) : []
    },

    isAuthenticatedUser () {
      return !!(this.userData && Object.keys(this.userData).length)
    },

    activeUserId () {
      const id = this.userData && this.userData.roles.length ? this.getIds(this.userData.roles, 'userId') : null
      if (id !== null) { return parseInt(id) } else { return id }
    }
  }
}
