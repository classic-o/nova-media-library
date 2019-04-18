<template>
  <div class="popup fixed pin z-40 py-view bg-primary-70% overflow-y-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg m-auto">

      <button type="button" class="close select-none" @click="$parent.popup = null">&times;</button>

      <Library :gallery="gallery" :field="field" />

    </div>
  </div>
</template>

<script>
import Library from '../../Index/Index.vue'
export default {
  props: ['gallery','field'],
  components: { Library },
  created() {
    document.body.classList.add('overflow-hidden');
  },
  beforeDestroy() {
    document.body.classList.remove('overflow-hidden');
  }
}
</script>
