<template>
  <div id="cropper" class="popup fixed pin z-20 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.popupType = 'info'"></div>

    <div class="relative z-30 bg-white overflow-hidden rounded-lg shadow-lg m-auto">

      <div><img :src="$parent.popup.url +'?'+ Date.now()" id="cropper-img" /></div>

      <template>

        <div class="flex border-b border-50 leading-loose">
          <div class="py-2 px-4">{{ __("nml_width") }} x {{ __("nml_height") }}</div>
          <div class="ml-auto py-2 px-4 font-bold text-90 text-right">{{ info.width }} x {{ info.height }}</div>
        </div>

        <div class="flex border-b border-50 leading-loose">
          <div class="py-2 px-4">{{ __("nml_rotate") }}</div>
          <div class="ml-auto py-2 px-4 font-bold text-90 text-right">
            <input
              class="form-control-sm form-input shadow-md"
              type="number" min="0" max="360"
              v-model="info.rotate"
              @input="rotate"
            />
          </div>
        </div>

        <div class="flex py-4 px-4">
          <template v-if="info.width">
            <button type="button" class="btn-default btn-primary cursor-pointer mr-4 shadow-md" @click="save(0)">
              {{ __("nml_save_as_new") }}
            </button>

            <button type="button" class="btn-default btn-primary cursor-pointer mr-4 shadow-md" @click="save(1)">
              {{ __("nml_overwrite_existing") }}
            </button>
          </template>

          <button type="button" class="btn-default btn-danger cursor-pointer ml-auto shadow-md" @click="$parent.popupType = 'info'">
            {{ __("nml_cancel") }}
          </button>
        </div>

      </template>

    </div>

  </div>
</template>

<script src="./script.js"></script>
