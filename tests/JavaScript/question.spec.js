import { mount } from 'vue-test-utils';
import Question from '../../resources/assets/js/components/exercise/Question.vue'
import moxios from 'moxios';

describe('Question', () => {
    let wrapper;

    beforeEach(() => {
        moxios.install();
        
        wrapper = mount(Question, {
            propsData: {
                dataQuestion: {
                    title: 'The title',
                    body: 'The body'
                }
            }
        });
    });

    afterEach(() => moxios.uninstall());

    it('presents the title and the body', () => {
        see(wrapper, 'The title');
        see(wrapper, 'The body');
    });

    it ('can be edited', () => {
        expect(wrapper.contains('input[name=title]')).toBe(false);
        expect(wrapper.contains('textarea[name=body]')).toBe(false);

        click(wrapper, '#edit');

        expect(wrapper.find('input[name=title]').element.value).toBe('The title');
        expect(wrapper.find('textarea[name=body]').element.value).toBe('The body');
    });

    it ('hides the edit button during edit mode.', () => {
        click(wrapper, '#edit');

        expect(wrapper.contains('#edit')).toBe(false);
    });

    it ('updates the question after being edited', (done) => {
        click(wrapper, '#edit');

        type(wrapper, 'input[name=title]', 'Changed title');
        type(wrapper, 'textarea[name=body]', 'Changed body');

        moxios.stubRequest(/questions\/\d+/, {
            status: 200,
            response: {
                title: 'Changed title',
                body: 'Changed body'
            }
        });
        
        click(wrapper, '#update');
        
        see(wrapper, 'Changed title');
        see(wrapper, 'Changed body');

        moxios.wait(() => {
            see(wrapper, 'Your question has been updated.');

            done();
        });
    });

    it ('can cancel out of edit mode', () => {
        click(wrapper, '#edit');

        type(wrapper, 'input[name=title]', 'Changed title');

        click(wrapper, '#cancel');

        see(wrapper, 'The title')
    });
});