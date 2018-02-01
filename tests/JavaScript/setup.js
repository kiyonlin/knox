require('jsdom-global')();

let expect = require('expect');

global.expect = expect;

// helper functions

global.see = (wrapper, text, selector) => {
    let wrap = selector ? wrapper.find(selector) : wrapper;

    expect(wrap.html()).toContain(text);
};

global.type = (wrapper, selector, text) => {
    let node = wrapper.find(selector);

    node.element.value = text;
    node.trigger('input');
};

global.click = (wrapper, selector) => {
    wrapper.find(selector).trigger('click');
};