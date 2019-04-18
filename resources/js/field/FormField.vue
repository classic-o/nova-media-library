<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div id="nml-field">

                <span v-if="isHidden" @click="isHidden = 0"
                   class="cursor-pointer dim inline-block text-primary font-bold pt-2 leading-tight">
                    Show
                </span>

                <template v-else>
                    <NmlGallery v-if="field.isGallery" :field="field" :handler="handleChange" />
                    <NmlImage v-else :field="field" :handler="handleChange" />
                </template>

                <input
                  :id="field.name"
                  type="hidden"
                  class="hidden"
                  :class="errorClasses"
                  :placeholder="field.name"
                  v-model="value"
                />

            </div>
        </template>
    </default-field>
</template>

<script src="./Form.js"></script>
