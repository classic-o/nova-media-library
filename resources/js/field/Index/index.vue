<template>
  <div class="nml-field-index nml-item">

    <template v-if="!item">—</template>

    <template v-else>
      <a :href="item.url"
         target="_blank"
         :class="`block icon shadow-md relative nml-icon-`+mime(item)">

        <div v-if="count > 1" class="count">{{ count }}</div>

      </a>
    </template>

  </div>
</template>

<script src="./script.js"></script>
