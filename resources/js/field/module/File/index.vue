<template>
  <div>

    <div class="card border border-lg border-50 max-w-xs p-8 text-center cursor-pointer max-w-xs"
         v-if="!item"
         @click="popup = true">
      {{ __('Select File') }}
    </div>

    <a v-else-if="item" :href="item.url" target="_blank" class="no-underline">
      <img class="block rounded-lg shadow-md max-w-xs"
           v-if="`image` === mime(item)"
           :src="item.preview || item.url"
           :alt="__('This file could not be found')" />

      <div class="nml-display-list" v-else>
        <div class="nml-item relative mb-2 cursor-pointer" :title="item.title || item.name">

          <div :class="'icon rounded-lg shadow-md nml-icon-'+mime(item)" :style="bg(item)" />

          <div class="title truncate" v-text="item.title || item.name" />

        </div>
      </div>

    </a>


    <div class="mt-4" v-if="isForm && item">
      <a class="cursor-pointer dim inline-block text-primary font-bold" @click="popup = true">
        {{ __('Media Library') }}
      </a>

      <a class="cursor-pointer dim inline-block text-danger font-bold ml-8" @click="changeFile(null)">
        {{ __('Clear') }}
      </a>
    </div>


    <transition name="bounce">
      <Library v-if="popup" :field="field" />
    </transition>


  </div>
</template>

<script src="./script.js"></script>

<style>
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.25);
  }
  100% {
    transform: scale(1);
  }
}
</style>
