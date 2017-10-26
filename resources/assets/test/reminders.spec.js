import { mount } from 'vue-test-utils';
import Reminders from '../js/components/Reminders.vue'
import expect from 'expect';

describe('Reminders', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(Reminders);
    });

    it.only('hides the reminders list if there are none', () => {
        expect(wrapper.contains('ul')).toBe(false);
    });

    it('can add reminders', () => {

    });
});