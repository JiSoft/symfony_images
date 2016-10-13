import Marionette from 'backbone.marionette';
import template from '../templates/error.jst';

export default Marionette.View.extend({
    className: 'error',
    template: template
});