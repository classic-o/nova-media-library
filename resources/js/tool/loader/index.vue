<template>
  <div class="mt-6 select-none text-center">

    <template v-if="!$parent.loading && $parent.items.total !== $parent.items.array.length">
      <button class="bg-green-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded cursor-pointer shadow-md mt-8 m-auto w-20"
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
