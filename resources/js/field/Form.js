import { FormField, HandlesValidationErrors } from 'laravel-nova'
import NmlGallery from './custom/nmlGallery.vue'
import NmlImage from './custom/nmlImage.vue'

export default {
  components: { NmlGallery, NmlImage },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      isFormField: true,
      isHidden: this.field.isHidden === true
    }
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value
    }
  }
}
