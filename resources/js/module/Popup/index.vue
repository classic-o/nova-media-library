<template>
  <div class="popup fixed pin z-20 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.popup = null"></div>

    <div class="relative z-30 bg-white p-8 rounded-lg shadow-lg m-auto">

      <button type="button" class="close select-none" @click="$parent.popup = null">&times;</button>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __("nml_id") }}</div>
        <div class="w-3/4 py-4">{{ $parent.popup.id }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __("nml_created") }}</div>
        <div class="w-3/4 py-4">{{ $parent.popup.created }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __("nml_type") }}</div>
        <div class="w-3/4 py-4">{{ $parent.popup.type }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __("nml_size") }}</div>
        <div class="w-3/4 py-4">{{ $parent.popup.size }}</div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80 leading-loose">{{ __("nml_description") }}</div>
        <div class="w-3/4 py-4">
          <input class="form-control form-input w-full shadow-md" v-model="$parent.popup.description">
        </div>
      </div>

      <div class="flex border-b border-40">
        <div class="w-1/4 py-4 font-bold text-80">{{ __("nml_url") }}</div>
        <div class="w-3/4 py-4">
          <a class="text-primary font-bold no-underline" :href="$parent.popup.url" target="_blank">{{ __("nml_open") }}</a>
          <button type="button" class="text-80 font-bold no-underline float-right"
                  v-copy="$parent.popup.url" v-copy:callback="onCopy">{{ __("nml_copy") }}</button>
        </div>
      </div>

      <div class="flex mt-6">
        <button type="button" class="btn-default btn-primary cursor-pointer mr-6 shadow-md"
                @click="update">
          {{ __("nml_update") }}
        </button>

        <button type="button" class="btn-default bg-success text-white cursor-pointer mr-6 shadow-md"
                @click="$parent.popupType = 'crop'"
                v-if="$parent.config.nml_crop && 'image' === $parent.popup.mime">
          {{ __("nml_edit_image") }}
        </button>

        <button type="button" class="btn-default btn-danger cursor-pointer ml-auto shadow-md"
                @click="$parent.deleteFiles([$parent.popup.id])">
          {{ __("nml_delete") }}
        </button>
      </div>

    </div>
  </div>
</template>

<script src="./script.js"></script>
