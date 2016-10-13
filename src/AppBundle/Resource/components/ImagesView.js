import Marionette from 'backbone.marionette';
import ImagesCollection from './ImagesCollection';
import templateI   from '../templates/image.jst';
import templateA  from '../templates/image_album.jst';

"use strict";

var Item = Marionette.View.extend({
    tagName: 'div',
    className: 'image',
    template: templateI
});
export default Marionette.CompositeView.extend({
    tagName: 'div',
    className: 'album_content',
    childViewContainer: 'div.images',
    childView: Item,
    template: templateA,

    initialize(options) {
        this.model = options.album;
    }

});