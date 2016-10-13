import Marionette from 'backbone.marionette';
import LayoutView from './Layout';

var Controller = Marionette.Object.extend({
    initialize: function() {
        this.options.layout = new LayoutView();
    },

    showAlbums: function() {
        var layout = this.getOption('layout');
        layout.triggerMethod('show:album:list');
    },

    showAlbum: function(id) {
        var layout = this.getOption('layout');
        layout.triggerMethod('show:image:list', id);
    },

    paginateAlbum: function(id, page) {
        var layout = this.getOption('layout');
        layout.triggerMethod('paginate:image:list', id, page);
    },

    home: function() {
        var layout = this.getOption('layout');
        layout.triggerMethod('show:home');
    }
});

export default Marionette.AppRouter.extend({

    appRoutes: {
        '': 'home',
        'albums':    'showAlbums',
        'album/:id': 'showAlbum',
        'album/:id/page/:page': 'paginateAlbum'
    },

    controller: new Controller
});