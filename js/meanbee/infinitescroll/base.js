var Meanbee_InfiniteScroll = Class.create({
    initialize: function (config, cache) {
        this.config = Object.extend({
            pager_selector: '.pager',
            top_toolbar_selector: '.toolbar',
            bottom_toolbar_selector: '.toolbar-bottom',
            button_selector: '.meanbee-infinitescroll-button',
            busy_selector: '.meanbee-infinitescroll-busy',

            showall_enabled: false,
            showall_link: false,
            showall_text: 'Show all products',

            endpoint: false,
            request_parameters: {},

            autoscroll_enabled: true,
            scroll_distance: 500,

            display_mode: 'grid',

            grid_item_selector: '.products-grid',
            grid_container_selector: '.products-grid:last',
            grid_container_action: 'after',

            list_item_selector: '.item',
            list_container_selector: '.products-list',
            list_container_action: 'bottom',

            on_last_page: false,
            cookie_key: ''
        }, config);

        this.cache = cache;

        // If we've already got a page set, then use that as our starting page number.
        if (this.config.request_parameters.p) {
            this.page = this.config.request_parameters.p;
        } else {
            this.page = 1;
        }

        this.currently_fetching = false;

        if (this.config.showall_enabled) {
            this._addShowAllLink();
        }

        this._hidePager();
        this._hideBottomToolbar();

        this._attachEvents();

        var cookie_page = this._getCookieValue();
        if (cookie_page) {
            var fetch_recursive = function (i) {
                // This needs to be set to 1. Previously, it was set to 0 which was causing an additional page to be requested
                // each time this piece of code ran, so every time you returned you'd be getting an additional page of products.
                if (i > 1) {
                    this._fetch(2 + cookie_page - i, function () {
                        fetch_recursive(--i);
                    });
                } else {
                    this.page = cookie_page;
                }
            }.bind(this);

            fetch_recursive(cookie_page);
        }
    },

    _hidePager: function () {
        $$(this.config.pager_selector).invoke('hide');
    },

    _hideBottomToolbar: function () {
        $$(this.config.bottom_toolbar_selector).invoke('hide');
    },

    _addShowAllLink: function () {
        $$(this.config.top_toolbar_selector).each(function (el) {
            var show_all = new Element('a', {
                href: this.config.showall_link,
            });
            show_all.addClassName('meanbee-infinitescroll-showall');
            show_all.update(this.config.showall_text);

            $(el).insert({
                bottom: show_all
            });
        }.bind(this));
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
                this._fetch(++this.page);
            }.bind(this))
        }.bind(this));

        if (this.config.autoscroll_enabled) {
            Event.observe(window, "scroll", function() {
                if (!this.config.on_last_page) {
                    var distance_from_bottom = $($$('html')[0]).getHeight() - document.viewport.getScrollOffsets()[1] - document.viewport.getHeight();

                    if (distance_from_bottom <= this.config.scroll_distance && !this.currently_fetching) {
                        this._fetch(++this.page);
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

    _fetch: function (page, completed_callback) {
        var post_parameters = Object.extend(this.config.request_parameters, {
            p: page
        })

        this.currently_fetching = true;
        this._hideButton();
        this._showBusy();

        var cache_value = this.cache.get(this._getCacheKey(page));
        if (!cache_value) {
            new Ajax.Request(this.config.endpoint, {
                postBody: Object.toQueryString(post_parameters),
                onSuccess: function(response) {
                    var json = response.responseJSON;
                    this._insertIntoDOM(json, completed_callback);
                    this.cache.set(this._getCacheKey(), json);
                }.bind(this)
            });
        } else {
            this._insertIntoDOM(cache_value, completed_callback);
        }
    },

    _insertIntoDOM: function (json, completed_callback) {
        var dom = new Element('div');

        if (json.content.last) {
            this.config.on_last_page = true;
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

        if (typeof(completed_callback) != 'undefined') {
            completed_callback();
        }
    },

    _getCookieValue: function () {
        return parseInt(Mage.Cookies.get(this.config.cookie_key));
    },

    _getCacheKey: function (page) {
        if (page == undefined) {
            page = this.page;
        }
        return this.config.cookie_key + '/' + page;
    }
});

/**
 * jStorage wrapper
 */
var Meanbee_InfiniteScroll_Cache = Class.create({
    initialize: function(ttl) {
        this.ttl = ttl;
    },

    set: function (key, value) {
        $.jStorage.set(key, value);

        /** Set a TTL of an hour **/
        $.jStorage.setTTL(key, 1000 * this.ttl);
    },

    get: function (key) {
        return $.jStorage.get(key);
    }
});
