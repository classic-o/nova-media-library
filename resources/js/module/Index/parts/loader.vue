<template>
  <div>

    <template v-if="!$parent.loading && !$parent.items.full">
      <button
        class="form-file-btn btn-default btn-primary cursor-pointer mt-8 m-auto block shadow-md"
        type="button"
        @click="$parent.loader"
      >{{ $parent.tool ? __("nml_no_autoload") : __("nml_load_more") }}</button>

      <div v-if="$parent.tool" class="help-text help-text pb-4 mt-2 m-auto block text-center">
        {{ __("nml_scroll_automatically") }}
      </div>
    </template>


    <div class="fixed pin z-30 bg-white-50%" v-if="$parent.loading">
      <loading-view/>
    </div>

  </div>
</template>
