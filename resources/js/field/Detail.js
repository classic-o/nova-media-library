import NmlGallery from './custom/nmlGallery.vue'
import NmlImage from './custom/nmlImage.vue'

export default {
  components: { NmlGallery, NmlImage },
  props: ['resource', 'resourceName', 'resourceId', 'field'],
  data() {
    return {
      isHidden: this.field.isHidden === true
    }
  }
}
