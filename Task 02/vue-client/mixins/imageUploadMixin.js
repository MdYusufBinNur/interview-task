
export default {
  data () {
    return {
      showDeleteImageModal: false,
      uploadingImage: false,
      deletingImage: false,
      showCloseButtonAfterImageUpload: true,

      files: null,
      resourceId: null,
      attachmentId: null,

      images: [],

      image: {
        id: null,
        fileName: null,
        resourceId: null,
        fileUrl: null,
        type: null,
        descriptions: null,
        fileType: null,
        fileSize: null
      },

      attachment: {
        type: null,
        resourceId: null,
        resizeImage: false,
        height: null,
        width: null,
        multipleTypes: []
      },

      fileUrl: ''
    }
  },

  watch: {
    attachmentId: {
      handler (nv, ov) {
        if (this.attachmentId === null || this.attachmentId === undefined) {
          this.setEmptyImage()
        }
      },
      deep: true,
      immediate: true
    },

    files: {
      handler (nv, ov) {
        if (this.files) {
          this.setFileUrl()
        } else {
          this.fileUrl = null
        }
      },
      deep: true,
      immediate: false
    }
  },

  methods: {
    setImageIfExists (image) {
      if (image && image.fileUrl) {
        for (const key in this.image) {
          this.image[key] = image[key]
        }
        this.resourceId = parseInt(image.resourceId, 10)
        this.attachmentId = image.id
      }
    },

    setFileUrl () {
      if (this.files) {
        const fr = new FileReader()
        fr.readAsDataURL(this.files)

        fr.addEventListener('load', () => {
          this.fileUrl = fr.result
        })
      } else {
        this.fileUrl = this.db
      }
    },

    setEmptyImage () {
      for (const key in this.image) {
        this.image[key] = null
      }
      this.files = null
      this.resourceId = null
      this.attachmentId = null
    },

    deleteAttachment () {
      if (this.attachmentId) {
        this.deletingImage = true
        this.$axios.delete('/attachment/' + this.attachmentId)
          .then((response) => {
            this.$toast.success('Image Deleted')
            this.showDeleteImageModal = false
            this.setEmptyImage()
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message)
          })
          .finally(() => {
            this.deletingImage = false
          })
      } else {
        this.$toast.info('No Image selected for delete')
      }
    },

    saveAttachment () {
      if (this.files && this.resourceId) {
        if (this.files.length) {
          this.files.forEach((file) => {
            this.storeAttachment(file)
          })
        } else {
          this.storeAttachment(this.files)
        }
      } else {
        this.$toast.info('No file selected for save')
      }
    },

    storeAttachment (file) {
      this.uploadingImage = true

      if (file !== undefined) {
        const fr = new FileReader()
        fr.readAsDataURL(file)

        fr.addEventListener('load', () => {
          this.image.fileUrl = fr.result
        })

        const formData = new FormData()

        formData.append('fileSource', file)
        formData.append('type', this.attachment.type)
        formData.append('resourceId', this.resourceId)

        if (this.attachment.resizeImage) {
          formData.append('resizeImage', this.attachment.resizeImage)
          formData.append('height', this.attachment.height)
          formData.append('width', this.attachment.width)
        }

        if (this.attachment.multipleTypes.length) {
          formData.append('multipleTypes', this.attachment.multipleTypes)
        }

        return this.$axios.post('/attachment', formData)
          .then((response) => {
            this.$toast.success('Image Uploaded')
            this.attachmentId = response.data.data.id
            this.showCloseButtonAfterImageUpload = true
            this.close()
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message)
          })
          .finally(() => {
            this.uploadingImage = false
          })
      } else if (this.image && this.image.fileUrl) {
        this.setEmptyImage()
      }
    }
  }
}
