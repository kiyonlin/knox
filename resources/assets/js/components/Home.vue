<template>
    <div class="container">
        <div class="columns">
            <div class="column">
                <div class="message">
                    <div class="message-header">Kiyon said...s</div>

                    <div class="message-body">
                        I'm an example component!
                    </div>
                    <input type="text" v-model="form.body">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data () {
            return {
                form: new Form({body: ''})
            }
        },

        mounted () {
            console.log('Component mounted.')
        }
    }
</script>
