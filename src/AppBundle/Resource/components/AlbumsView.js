import Marionette from 'backbone.marionette';
import template from '../templates/album.jst';

var Item = Marionette.View.extend({
    tagName: 'div',
    className: 'album',
    template: template
});
export default Marionette.CollectionView.extend({
    tagName: 'div',
    className: 'albums',
    childView: Item
});