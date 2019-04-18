<template>
    <div v-if="field.value">
        <div :style="{backgroundImage: `url(${src})`}"
             class="inline-block bg-cover bg-center rounded-full h-12 w-12">
        </div>
        <span v-if="count>1" class="align-top text-xs">+{{ count }}</span>
    </div>
</template>

<script src="./Index.js"></script>
