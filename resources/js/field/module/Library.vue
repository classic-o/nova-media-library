<template>
  <div class="popup fixed pin z-40 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.popup = null"></div>

    <div class="relative z-30 bg-white p-8 rounded-lg shadow-lg m-auto">

      <button type="button" class="close select-none" @click="$parent.popup = null">&times;</button>

      <Library :listing="field.listing" :field="field.attribute" :types="types" />

    </div>

  </div>
</template>

<script>
import Library from '../../module/Index/index.vue'

export default {
  props: ['field'],
  components: { Library },
  data() {
    return {
      types: Array.isArray(this.field.withTypes) ? this.field.withTypes : []
    }
  },
  mounted() {
    document.body.classList.add('overflow-hidden');
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
</script>
