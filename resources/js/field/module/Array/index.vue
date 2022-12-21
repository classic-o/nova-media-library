<template>
    <div>
        <draggable
            :class="'flex flex-wrap space-x-3 nml-display-' + type"
            v-if="array && array.length"
            item-key="id"
            v-model="array"
            @end="changeArray(array)"
        >
            <template #item="{ element }">
                <div class="relative w-32">
                    <div class="title truncate text-center" v-text="element.title || element.name" />
                    <img class="h-40 w-32" :src="element.url" />
                    <!-- <button v-if="isForm" @click="remove(i)">Remove</button> -->
                </div>
            </template>
        </draggable>

        <div
            class="card border-lg border-50 max-w-xs max-w-xs cursor-pointer border p-8 text-center"
            v-else-if="isForm"
            @click="popup = true"
        >
            {{ __('Select Files') }}
        </div>

        <div class="mt-4" v-if="isForm && array && array.length">
            <a class="dim text-primary inline-block cursor-pointer font-bold" @click="popup = true">
                {{ __('Media Library') }}
            </a>

            <a class="dim text-danger ml-8 inline-block cursor-pointer font-bold" @click="changeArray([])">
                {{ __('Clear') }}
            </a>
        </div>

        <transition name="fade" mode="out-in">
            <Library v-if="popup" isArray :field="field" />
        </transition>
    </div>
</template>

<script src="./script.js"></script>
