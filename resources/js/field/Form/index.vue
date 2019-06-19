<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div class="nml-field-form">

                <span class="cursor-pointer dim inline-block text-primary font-bold pt-2 leading-tight"
                      v-if="isHidden" @click="isHidden = 0"
                >{{ __("nml_show") }}</span>

                <template v-else>
                    <nmlList v-if="field.listing" :field="field" :handler="handleChange" />
                    <nmlTrix v-else-if="field.forTrix" :field="field" />
                    <nmlCallback v-else-if="field.jsCallback" :field="field" />
                    <nmlFile v-else :field="field" :handler="handleChange" />
                </template>

            </div>
        </template>
    </default-field>
</template>

<script src="./script.js"></script>
