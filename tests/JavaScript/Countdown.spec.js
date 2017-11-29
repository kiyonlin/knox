import { mount } from 'vue-test-utils';
// 这个文件需要引入expect
import expect from 'expect';
import moment from 'moment';
import sinon from 'sinon';
import Countdown from '../../resources/assets/js/components/exercise/Countdown.vue';

describe('Countdown', () => {
    let wrapper, clock;

    beforeEach(() => {
        clock = sinon.useFakeTimers();

        wrapper = mount(Countdown);

        wrapper.setProps({ until: moment().add(10, 'seconds') });
    });

    afterEach(() => clock.restore());

    it('renders a countdown timer', () => {
        see(wrapper, '0 Days');
        see(wrapper, '0 Hours');
        see(wrapper, '0 Minutes');
        see(wrapper, '10 Seconds');
    });

    it ('reduces the countdown every second', async () => {
        see(wrapper, '10 Seconds');

        clock.tick(1000);

        await wrapper.vm.$nextTick();

        see(wrapper, '9 Seconds');
    });

    it('shows an expired message when the countdown has completed', async () => {
        clock.tick(10000);

        await wrapper.vm.$nextTick();

        see(wrapper, 'Now Expired');
    });

    it('shows a custom expired message when the countdown has completed', async () => {
        wrapper.setProps({ expiredText: 'Contest is over' });

        clock.tick(10000);

        await wrapper.vm.$nextTick();
        
        see(wrapper, 'Contest is over');
    });

    it('broadcasts when the countdown is finished', async () => {
        clock.tick(10000);

        await wrapper.vm.$nextTick();

        expect(wrapper.emitted()['finished']).toBeTruthy();
    });

    it ('clears the interval once completed', async () => {
        clock.tick(10000);

        expect(wrapper.vm.now.getSeconds()).toBe(10);

        await wrapper.vm.$nextTick();

        clock.tick(5000);

        expect(wrapper.vm.now.getSeconds()).toBe(10);
    });

    // // helper functions

    // let see = (text, selector) => {
    //     let wrap = selector ? wrapper.find(selector) : wrapper;

    //     expect(wrap.html()).toContain(text);
    // };

    // let type = (selector, text) => {
    //     let node = wrapper.find(selector);

    //     node.element.value = text;
    //     node.trigger('input');
    // };

    // let click = selector => {
    //     wrapper.find(selector).trigger('click');
    // };
});