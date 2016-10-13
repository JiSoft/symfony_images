import Backbone   from 'backbone';
import Marionette from 'backbone.marionette';
import template   from '../templates/social.jst';

export default Marionette.View.extend({
    model: new Backbone.Model({
        fb_link: "http://facebook.com/",
        tw_link: "http://twitter.com/"
    }),
    className: 'social top',
    tagName: 'section',
    template: template
});