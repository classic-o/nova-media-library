<template>
    <div class="nml-field-index">

      <template v-if="!url">—</template>

      <template v-else>
        <a :href="url" target="_blank" class="block card shadow-md nml-icon-file relative" :style="setBg(url)">
          <div v-if="count > 1" class="count">{{ count }}</div>
        </a>
      </template>

    </div>
</template>

<script src="./script.js"></script>
