<template>
    <v-dialog :model-value="visible" max-width="400px" @update:model-value="updateVisibility">
        <v-card>
            <v-card-title>Выберите дополнительное время аренды</v-card-title>
            <v-card-text>
                <v-radio-group v-model="additionalDuration" column>
                    <v-radio label="4 часа" :value="4" />
                    <v-radio label="8 часов" :value="8" />
                    <v-radio label="12 часов" :value="12" />
                    <v-radio label="24 часа" :value="24" />
                </v-radio-group>
            </v-card-text>
            <v-card-actions>
                <v-btn text color="red" @click="closeModal">Отмена</v-btn>
                <v-btn text color="green" @click="confirmExtendRent">Продлить аренду</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
        transactionId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            additionalDuration: 4,
        };
    },
    methods: {
        updateVisibility(newValue) {
            this.$emit('update:visible', newValue);
        },
        closeModal() {
            this.$emit('update:visible', false);
        },
        confirmExtendRent() {
            this.$emit('extendRent', { transactionId: this.transactionId, hours: this.additionalDuration });
            this.closeModal();
        },
    },
};
</script>
