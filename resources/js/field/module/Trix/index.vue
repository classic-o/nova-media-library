<template>
  <div>

    <a class="cursor-pointer dim inline-block text-primary font-bold mt-2" @click="popup = true">
      {{ __("nml_open_library") }}
    </a>

    <transition name="fade">
      <Library v-if="popup" :field="field" />
    </transition>

  </div>
</template>

<script src="./script.js"></script>
