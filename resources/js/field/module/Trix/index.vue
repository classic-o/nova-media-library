<template>
  <div>

    <a class="cursor-pointer dim inline-block text-primary font-bold mt-2" @click="popup = true">
      {{ __('Media Library') }}
    </a>

    <transition name="fade" mode="out-in">
      <Library v-if="popup" :field="field" />
    </transition>

  </div>
</template>

<script src="./script.js"></script>
