import Marionette from 'backbone.marionette';
import template from '../templates/album_detailed.jst';

var Item = Marionette.View.extend({
    tagName: 'div',
    template: template
});
export default Marionette.CollectionView.extend({
    tagName: 'div',
    className: 'albums_detailed',
    childView: Item
});
