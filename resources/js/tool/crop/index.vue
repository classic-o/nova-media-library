<template>
  <div id="nml_cropper" class="popup fixed top-10 left-0 right-0 pin z-20 py-view bg-primary-70% overflow-y-auto">
    <div class="absolute pin z-20" @click="$parent.$parent.popup = 'info'"></div>

    <div class="relative z-30 bg-white overflow-hidden rounded-lg shadow-lg m-auto">

      <div><img :src="$parent.$parent.item.url +'?'+ Date.now()" id="cropper-img" /></div>


      <div class="flex border-b border-50 leading-loose">
        <div class="py-2 px-4">{{ __('Width x Height') }}</div>
        <div class="ml-auto py-2 px-4 font-bold text-90 text-right">{{ info.width || 0 }} x {{ info.height || 0 }}</div>
      </div>

      <div class="flex border-b border-50 leading-loose">
        <div class="py-2 px-4">{{ __('Rotate') }}</div>
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
        <template v-if="info.width && info.height">
          <button type="button" class="btn-default btn-primary cursor-pointer mr-4 shadow-md" @click="save(0)">
            {{ __('Save as new') }}
          </button>

          <button type="button" class="btn-default btn-primary cursor-pointer mr-4 shadow-md" @click="save(1)">
            {{ __('Overwrite existing') }}
          </button>
        </template>

        <button type="button" class="btn-default cursor-pointer bg-success text-black ml-auto px-3 shadow-md"
                @click="crop.clear().reset()">x1</button>
        <button type="button" class="btn-default btn-danger cursor-pointer ml-4 shadow-md" @click="$parent.$parent.popup = 'info'">
          {{ __('Cancel') }}
        </button>
      </div>


    </div>

  </div>
</template>

<script src="./script.js"></script>
