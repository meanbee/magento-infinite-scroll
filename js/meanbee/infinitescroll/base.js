var Meanbee_InfiniteScroll = Class.create({
    initialize: function (config) {
        this.config = Object.extend({
            pager_selector: '.pager',
            bottom_toolbar_selector: '.toolbar-bottom',
            button_selector: '.meanbee-infinitescroll-button',
            busy_selector: '.meanbee-infinitescroll-busy',
            display_mode: 'grid',
            grid_item_selector: '.item',
            grid_container_selector: '.products-grid:last',
            list_item_selector: '.item',
            list_container_selector: '.products-grid:last',
            endpoint: false,
            request_parameters: {},
            autoscroll_enabled: true,
            scroll_distance: 500
        }, config);

        /** @TODO Override this value if 'p' appears in this.config.post_parameters **/
        this.page = 1;

        this.hit_bottom = false;
        this.currently_fetching = false;

        this._hidePager();
        this._hideBottomToolbar();

        this._attachEvents();
    },

    _hidePager: function () {
        $$(this.config.pager_selector).invoke('hide');
    },

    _hideBottomToolbar: function () {
        $$(this.config.bottom_toolbar_selector).invoke('hide');
    },

    _hideButton: function () {
        $$(this.config.button_selector).invoke('hide');
    },

    _showButton: function () {
        $$(this.config.button_selector).invoke('show');
    },

    _hideBusy: function () {
        $$(this.config.busy_selector).invoke('hide');
    },

    _showBusy: function () {
        $$(this.config.busy_selector).invoke('show');
    },

    _attachEvents: function () {
        $$(this.config.button_selector).each(function (el) {
            $(el).observe('click', function (e) {
                Event.stop(e);
                this._fetch();
            }.bind(this))
        }.bind(this));

        if (this.config.autoscroll_enabled) {
            Event.observe(window, "scroll", function() {
                if (!this.hit_bottom) {
                    var distance_from_bottom = $($$('html')[0]).getHeight() - document.viewport.getScrollOffsets()[1] - document.viewport.getHeight();

                    if (distance_from_bottom <= this.config.scroll_distance && !this.currently_fetching) {
                        this._fetch();
                    }
                }
            }.bind(this));
        }
    },

    _getItemSelector: function () {
        if (this.config.display_mode != 'grid') {
            return this.config.list_item_selector;
        } else {
            return this.config.grid_item_selector;
        }
    },

    _getContainerSelector: function () {
        if (this.config.display_mode != 'grid') {
            return this.config.list_container_selector;
        } else {
            return this.config.grid_container_selector;
        }
    },

    _getContainerAction: function () {
        if (this.config.display_mode != 'grid') {
            return this.config.list_container_action;
        } else {
            return this.config.grid_container_action;
        }
    },

    _fetch: function () {
        var post_parameters = Object.extend(this.config.request_parameters, {
            p: ++this.page
        })

        this.currently_fetching = true;
        this._hideButton();
        this._showBusy();

        new Ajax.Request(this.config.endpoint, {
            postBody: Object.toQueryString(post_parameters),
            onSuccess: function(response) {
                var json = response.responseJSON;
                var dom = new Element('div');

                if (json.content.last) {
                    this.hit_bottom = true;
                } else {
                    this._showButton();
                }

                var item_selector = this._getItemSelector();

                dom.update(json.content.block);
                dom.select(item_selector).each(function (el) {
                    var container_selector = this._getContainerSelector();
                    var container_action = this._getContainerAction();

                    var container = $$(container_selector);

                    if (container.length >= 1) {
                        var insert_config = {};

                        insert_config[container_action] = $(el);

                        $(container[0]).insert(insert_config);
                    }
                }.bind(this))

                this.currently_fetching = false;
                this._hideBusy();
            }.bind(this)
        });
    }
});
