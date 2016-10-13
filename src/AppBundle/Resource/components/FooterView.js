import Marionette from 'backbone.marionette';
import template from '../templates/footer.jst';

export default Marionette.View.extend({

    model: new Backbone.Model({
        company: "JiSoft",
        product: "Symfony + Marionette Images Gallery",
        year: new Date().getFullYear()
    }),
    template: template
});