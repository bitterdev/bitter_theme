/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

if (typeof counter === "undefined") {
    var counter = {};
}

counter.backend = {
    currentIndex: 0,
    
    getNextIndex: function() {
        this.currentIndex++;
        
        return this.currentIndex;
    },

    appendEmptyItem: function() {
        this.appendItem("", "");
    },

    appendItem: function(value, description) {
        $("#itemsContainer").append(Mustache.render($("#itemTemplate").html(), {
            id: this.getNextIndex(),
            value: value,
            description: description
        }));
    },

    bindEventHandlers: function() {
        var self = this;
        
        $("#addItem").bind("click", function() {
            self.appendEmptyItem();
        });
    },

    removeItem: function(id) {
        $("#item-" + id).remove();
    },

    loadItems: function(items) {
        for(var i in items) {
            var item = items[i];
            this.appendItem(item.counterValue, item.counterDescription);
        }
    },

    init: function(items) {
        this.bindEventHandlers();
        this.loadItems(items);
    }
};