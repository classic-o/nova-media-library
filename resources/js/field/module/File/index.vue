<template>
  <div>

    <div class="max-w-xs">

      <div v-if="!url" class="card border border-lg border-50 max-w-xs p-8 text-center">
        {{ __("nml_no_file_selected") }}
      </div>

      <a v-else :href="url" target="_blank">
        <img v-if="isImage(url)" :src="url" class="block w-full rounded-lg" alt="This file could not be found">
        <div v-else class="no-image-file nml-icon-file field shadow-md card" :title="url"></div>
      </a>

    </div>


    <div class="mt-4" v-if="isForm">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        {{ __("nml_open_library") }}
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeFile(null)" v-if="url">
        {{ __("nml_clear") }}
      </a>

      <transition name="fade">
        <Library v-if="popup" :field="field" />
      </transition>
    </div>

  </div>
</template>

<script src="./script.js"></script>
