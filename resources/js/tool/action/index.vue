<template>
  <div class="flex flex-wrap mb-4 select-none">

    <checkbox-with-label
      class="cursor-pointer mr-4"
      v-if="!$parent.field || $parent.isArray"
      :checked="$parent.bulk.enable"
      @input="changeBulk"
      @change="changeBulk"
    >{{ __('Bulk Select') }}</checkbox-with-label>

    <button class="btn-default bg-primary text-white cursor-pointer shadow-md max-w-full mr-4"
            type="button"
            @click="bulkAll"
            v-if="$parent.bulk.enable"
    >{{ __('Select All') }}</button>

    <template v-if="$parent.bulkLen()">
      <button class="btn-default bg-danger text-white cursor-pointer shadow-md max-w-full mr-4"
              type="button"
              @click="$parent.deleteFiles(Object.keys($parent.bulk.ids))">
        {{ __('Delete') }} ({{ $parent.bulkLen() }})
      </button>

      <button class="btn-default bg-success text-white cursor-pointer shadow-md max-w-full mr-4"
              v-if="$parent.field && $parent.bulk.enable && $parent.bulkLen()"
              type="button"
              @click="pushFiles">
        {{ __('Choose') }} ({{ $parent.bulkLen() }})
      </button>
    </template>

    <label class="btn-default bg-90 text-white text-center cursor-pointer shadow-md max-w-full ml-auto"
           @click="showUploader"
    >
      {{ __('Upload') }}
    </label>

  </div>
</template>

<script src="./script.js"></script>
