import Marionette from 'backbone.marionette';
import ImagesCollection from './ImagesCollection';
import SocialView from './SocialView';
import FooterView from './FooterView';
import HomeView   from './HomeView';
import AlbumsView from './AlbumsView';
import ImagesView from './ImagesView';
import ErrorView  from './ErrorView';

"use strict";

export default Marionette.View.extend({
        el: "#gallery",

        regions: {
            social: {el:"#social", replaceElement: true},
            content:{el:"#content",replaceElement: false},
            footer: {el:"#footer", replaceElement: false}
        },

        initialize() {
             this.options.perPage = 10;
             this.showChildView('social', new SocialView());
             this.showChildView('footer', new FooterView());
        },

        onShowHome() {
            this.showChildView('content', new HomeView());
        },

        onShowAlbumList() {

            var AlbumsCollection = Backbone.Collection.extend({
                parse (response) {
                    for (var prop in response) {
                        if (response.hasOwnProperty(prop) && typeof response[prop]['createdAt'] != 'undefined') {
                            var parts = response[prop].createdAt.split(" ");
                            response[prop].albumDate = parts[0];
                            response[prop].albumTime = parts[1];
                        }
                    }
                    return response;
                }
            });

            var self = this;
            var collection = new AlbumsCollection();
            collection.fetch({
                url: '/albums',
                success: function() {
                    self.options.albums = collection;
                    self.showChildView('content', new AlbumsView({collection: collection}));
                },
                error: function() {
                    self.showChildView('content', new ErrorView({
                        model: new Backbone.Model({message: "Hasn't being received the answer from the server"})
                    }));
                }
            });
        },

        onShowImageList(albumId) {
            var album = this.options.albums.get({id:albumId});
            this.options.collection = new ImagesCollection();
            this.options.url = '/album/'+albumId;
            var self = this;
            this.options.collection.fetch({
                url: self.options.url,
                success: function() {
                    album.set('page', 2);
                    self.showChildView('content', new ImagesView({collection: self.options.collection, album: album}));
                },
                error: function() {
                    self.showChildView('content', new ErrorView({
                        model: new Backbone.Model({message: "Hasn't being received the answer from the server"})
                    }));
                }
            });
        },

        onPaginateImageList(albumId, pageNo) {
            var album = this.options.albums.get({id:albumId});

            var pages = Math.ceil(album.get('imagesCount')/this.options.perPage);
            if (this.options.page>this.options.pages)
                return;

            var self = this;
            this.options.collection.fetch({
                url: this.options.url,
                data: {page: pageNo},
                success: function() {
                    album.set('page', pageNo+1);
                    self.showChildView('content', new ImagesView({collection: self.options.collection, album: album}));
                },
                error: function() {
                    self.showChildView('content', new ErrorView({
                        model: new Backbone.Model({message: "Hasn't being received the answer from the server"})
                    }));
                }
            });
        }
});