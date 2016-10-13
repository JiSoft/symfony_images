var Backbone = require('backbone');
import Marionette from 'backbone.marionette';
import Router from './Router';

export default Marionette.Application.extend({
    region: '#albums',

    initialize() {
        this.on('start', () => {

            new Router();
            if(Backbone.history){
                Backbone.history.start();
            }

            console.log('App has started');
        })
    }
});
