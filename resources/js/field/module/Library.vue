<template>
  <div class="popup absolute top-0 left-0 right-0 pin z-40 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.$parent.popup = null"></div>

    <div class="relative z-30 p-8 rounded-lg m-auto border-2 border-gray-200 shadow-lg bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

      <button type="button" class="nml-close select-none" @click="$parent.$parent.popup = null">&times;</button>

      <Library :field="field.attribute" :isArray="field.nmlArray" :types="types" />

    </div>

  </div>
</template>

<script>
import Library from '../../tool/'

export default {
  props: ['field'],
  components: { Library },
  data() {
    return {
      types: Array.isArray(this.field.nmlTypes) ? this.field.nmlTypes : []
    }
  },
  mounted() {
    //document.body.classList.add('overflow-hidden');
  },
  beforeUnmount() {
    document.body.classList.remove('overflow-hidden');
  }
}
</script>
