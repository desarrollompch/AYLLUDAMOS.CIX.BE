<template>
    <input >
</template>

<script>
    export default {
        props: ['options'],
        mounted(){
            new Cleave(this.$el, this.options);

            this.$el.oninput = (e) => {
                this.$emit('oninput', e.target.value)
            }
        }
    }
</script>