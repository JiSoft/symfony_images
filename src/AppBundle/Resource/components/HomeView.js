import Marionette from 'backbone.marionette';
import template from '../templates/home.jst';

export default Marionette.View.extend({
    className: 'home',
    template: template
});