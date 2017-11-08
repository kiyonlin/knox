<template>
  <div>
      <input type="text" class="coupon-code" v-model="code" @input="validate">

      <p v-text="feedback"></p>
  </div>
</template>
<script>
    export default {
        data() {
            return {
                code: '',
                valid: false,
                coupons: [
                    {
                        code: '10OFF',
                        message: '10% off',
                        discount: 10
                    },
                    {
                        code: 'FREE',
                        message: 'Entirely Free!',
                        discount: 100
                    }
                ]
            };
        },

        computed: {
            selectedCoupon() {
                return this.coupons.find(coupon => coupon.code == this.code);
            },
            message() {
                return this.selectedCoupon.message;
            },
            feedback() {
                return this.valid
                    ? `Coupon Redeemed: ${this.message}`
                    : 'Invalid Coupon Code';
            }
        },
        methods: {
            validate() {
                this.valid = !! this.selectedCoupon;

                if(this.valid) {
                    this.$emit('applied', this.selectedCoupon.discount);
                }
            }
        }
    }
</script>