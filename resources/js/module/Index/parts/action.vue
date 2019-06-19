<template>
  <div class="flex mb-6">

    <checkbox-with-label
      class="mr-6"
      v-if="$parent.tool || $parent.listing"
      :checked="$parent.bulk.is"
      @change="changeBulk"
    >{{ __("nml_bulk_select") }}</checkbox-with-label>

    <button
      class="btn-default btn-primary cursor-pointer shadow-md mr-6"
      v-if="$parent.bulk.is"
      @click="bulkAll"
      type="button"
    >{{ __("nml_select_all") }}</button>

    <button
      class="btn-default btn-danger cursor-pointer shadow-md"
      type="button"
      v-if="$parent.bulk.is && $parent.bulk.array.length"
      @click="$parent.deleteFiles($parent.bulk.array)"
    >{{ __("nml_delete_selected") }} ({{ $parent.bulk.array.length }})</button>

    <button
      class="btn-default text-white bg-success cursor-pointer shadow-md ml-6"
      type="button"
      v-if="!$parent.tool && $parent.bulk.is && $parent.bulk.array.length"
      @click="pushFiles"
    >{{ __("nml_add_to_listing") }} ({{ $parent.bulk.array.length }})</button>

    <label class="btn btn-default btn-primary cursor-pointer shadow-md ml-auto">
      <input
        id="nml_upload"
        class="form-file-input"
        :accept="$parent.config.nml_accept"
        type="file"
        @change="selectFiles"
        multiple
      />
      {{ __("nml_upload_files") }}
    </label>
  </div>
</template>

<script src="./action.js"></script>
