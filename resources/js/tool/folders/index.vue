<template>
  <div :class="[`nml-item relative mb-2 cursor-pointer `, { 'nml-folder-action': className }]" @click="action(type)">
    <div :class="`icon rounded-lg shadow-md nml-icon-folder`+className"></div>
    <div class="title truncate">{{ title[type] }}</div>
  </div>
</template>

<script src="./script.js"></script>
