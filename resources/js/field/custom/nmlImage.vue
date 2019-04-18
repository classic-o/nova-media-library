<template>
  <div>

    <div class="max-w-xs">
      <div v-if="!src || error" class="card border border-lg border-50 max-w-xs p-8 text-center">
        <template v-if="!src">No image selected</template>
        <template v-else>
          <a :href="src" target="_blank" class="text-primary dim no-underline">This image</a> could not be found.
        </template>
      </div>

      <a v-else :href="src" target="_blank">
        <img :src="src" class="block w-full rounded-lg" alt=" This image could not be found.">
      </a>
    </div>

    <div class="mt-4" v-if="isForm">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        Open Library
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeImage(null)" v-if="src">
        Clear
      </a>

      <transition name="fade">
        <Library v-if="popup" :field="field.attribute" />
      </transition>
    </div>

  </div>
</template>

<script src="./nmlImage.js"></script>
