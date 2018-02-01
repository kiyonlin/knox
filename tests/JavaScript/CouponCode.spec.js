import { mount } from 'vue-test-utils';
import CouponCode from '../../resources/assets/js/components/exercise/CouponCode.vue'

describe('CouponCode', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(CouponCode);

        wrapper.setData({
            coupons: [
                {
                    code: '50OFF',
                    message: '50% off',
                    discount: 50
                }
            ]
        });
    });

    it('accepts a coupon code', () => {
        expect(wrapper.contains('input.coupon-code')).toBe(true);
    });

    it('validates a real coupon code', () => {
        type(wrapper, 'input.coupon-code', '50OFF');

        expect(wrapper.html()).toContain('Coupon Redeemed: 50% off');
    });

    it('validates a fake coupon code', () => {
        type(wrapper, 'input.coupon-code', 'NOTREAL');
        
        expect(wrapper.html()).toContain('Invalid Coupon Code');
    });

    it('broadcasts the percentage discount when a valid coupon code is applied', () => {
        type(wrapper, 'input.coupon-code', '50OFF');

        expect(wrapper.emitted().applied).toBeTruthy();
        expect(wrapper.emitted().applied[0]).toEqual([50]);
    });

});