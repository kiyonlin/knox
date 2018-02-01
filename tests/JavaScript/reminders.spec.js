import { mount } from 'vue-test-utils';
import Reminders from '../../resources/assets/js/components/exercise/Reminders.vue'

describe('Reminders', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(Reminders);
    });

    it('hides the reminders list if there are none', () => {
        expect(wrapper.contains('ul')).toBe(false);
    });

    it('can add reminders', () => {
        addReminder('Go to the store');

        expect(remindersList()).toContain('Go to the store');
    });

    it('can remove any reminder', () => {
        addReminder('Go to the store');
        addReminder('Finish screencast');

        let deleteButton = wrapper.find('ul > li:first-child .remove');

        deleteButton.trigger('click');

        expect(remindersList()).not.toContain('Go to the store');
        expect(remindersList()).toContain('Finish screencast');
    });

    function addReminder(body) {
        type(wrapper, '.new-reminder', body);

        click(wrapper, 'button');
    }

    function remindersList() {
        return wrapper.find('ul').text();
    }
});