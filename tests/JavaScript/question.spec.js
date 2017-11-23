import {
    mount
} from 'vue-test-utils';
import Question from '../../resources/assets/js/components/exercise/Question.vue'
import expect from 'expect';
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
        see('The title');
        see('The body');
    });

    it ('can be edited', () => {
        expect(wrapper.contains('input[name=title]')).toBe(false);
        expect(wrapper.contains('textarea[name=body]')).toBe(false);

        click('#edit');

        expect(wrapper.find('input[name=title]').element.value).toBe('The title');
        expect(wrapper.find('textarea[name=body]').element.value).toBe('The body');
    });

    it ('hides the edit button during edit mode.', () => {
        click('#edit');

        expect(wrapper.contains('#edit')).toBe(false);
    });

    it ('updates the question after being edited', (done) => {
        wrapper.find('#edit').trigger('click');

        type('input[name=title]', 'Changed title');
        type('textarea[name=body]', 'Changed body');

        moxios.stubRequest(/questions\/\d+/, {
            status: 200,
            response: {
                title: 'Changed title',
                body: 'Changed body'
            }
        });
        
        click('#update');
        
        see('Changed title');
        see('Changed body');

        moxios.wait(() => {
            see('Your question has been updated.');

            done();
        });
    });

    it ('can cancel out of edit mode', () => {
        click('#edit');

        type('input[name=title]', 'Changed title');

        click('#cancel');

        see('The title')
    });

    let see = (text, selector) => {
        let wrap = selector ? wrapper.find(selector) : wrapper;

        expect(wrap.html()).toContain(text);
    }

    let type = (selector, text) => {
        let node = wrapper.find(selector);

        node.element.value = text;
        node.trigger('input');
    };

    let click = selector => {
        wrapper.find(selector).trigger('click');
    }
});