<template>
  <div class="mt-6 select-none text-center">

    <template v-if="!$parent.loading && $parent.items.total !== $parent.items.array.length">
      <button class="btn-default btn-primary cursor-pointer shadow-md mt-8 m-auto w-40"
              type="button"
              @click="$parent.loader">
        {{ __('More') }}
      </button>

      <div class="mt-1 help-text" v-if="$parent.items.total">
        {{ __('Displayed') }} {{ $parent.items.array.length }} / {{ $parent.items.total }}
      </div>
    </template>

    <div class="fixed pin z-30 bg-white-50% loader" v-if="$parent.loading">
      <loading-view />
    </div>

  </div>
</template>

<script src="./script.js"></script>
