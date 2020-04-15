<template>
  <div id="nml-tool" :class="'nml-store-'+ (`folders` === config.store ? 'folders' : 'together')">

    <heading class="mb-6">{{ __('Media Library') }}</heading>

    <Action />

    <Search />

    <Items />

    <Loader />

    <transition name="fade" mode="out-in">
      <Popup v-if="item && 'info' === popup" />
    </transition>

    <transition name="fade" mode="out-in">
      <Uploader v-if="'uploader' === popup" />
    </transition>

    <transition name="fade" mode="out-in">
      <Crop v-if="item && 'crop' === popup" />
    </transition>

  </div>
</template>

<script src="./script.js"></script>
