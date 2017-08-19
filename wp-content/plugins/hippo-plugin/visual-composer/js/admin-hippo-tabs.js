// Custom Icon tab view

(function ($) {

    $(function () {

        var i18n = window.i18nLocale,
            store = vc.storage,
            Shortcodes = vc.shortcodes;

        Shortcodes.on('sync', function (model, resp, options) {
            if (typeof model.attributes != 'undefined') {
                if (model.attributes.shortcode == 'hippo_tabs') {
                    var params = _.extend({}, model.attributes.params)
                    // console.log(params)
                    $.ajax({
                        type   : 'POST',
                        url    : window.ajaxurl,
                        data   : {
                            action  : 'hippo_tab_save_post_data',
                            post_id : $('#post_ID').val(),
                            settings: params
                        },
                        context: this
                    }).done(function (data) {
                        //console.log(data)
                    });
                }

            }

        });


        window.HippoIconTabsView = vc.shortcode_view.extend({
            new_tab_adding    : false,
            events            : {
                'click .add_tab'                             : 'addTab',
                'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
                'click > .vc_controls .vc_control-btn-edit'  : 'editElement',
                'click > .vc_controls .vc_control-btn-clone' : 'clone',
                'click .wpb_save_edit_form'                  : 'save'
            },
            initialize        : function (params) {
                window.HippoIconTabsView.__super__.initialize.call(this, params);

                _.bindAll(this, 'stopSorting');

            },
            render            : function () {
                window.HippoIconTabsView.__super__.render.call(this);
                this.$tabs = this.$el.find('.wpb_tabs_holder');
                this.createAddTabButton();
                return this;
            },
            ready             : function (e) {
                window.HippoIconTabsView.__super__.ready.call(this, e);
            },
            createAddTabButton: function () {

                var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
                this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
                this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
            },
            addTab            : function (e) {
                e.preventDefault();
                this.new_tab_adding = true;
                var tab_title = this.model.get('shortcode') === 'vc_tour' ? window.i18nLocale.slide : window.i18nLocale.tab,
                    tabs_count = this.$tabs.find('[data-element_type=hippo_tab]').length,
                    tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
                vc.shortcodes.create({
                    shortcode: 'hippo_tab',
                    params   : {title: tab_title, tab_id: tab_id},
                    parent_id: this.model.id
                });
                return false;
            },
            stopSorting       : function (event, ui) {
                var shortcode;

                this.$tabs.find('ul.tabs_controls li:not(.add_tab_block)').each(function (index) {
                    var href = $(this).find('a').attr('href').replace("#", "");
                    // $('#' + href).appendTo(this.$tabs);
                    shortcode = vc.shortcodes.get($('[id=' + $(this).attr('aria-controls') + ']').data('model-id'));
                    vc.storage.lock();
                    shortcode.save({'order': $(this).index()}); // Optimize
                });
                shortcode.save();
            },

            changedContent: function (view) {
                var params = view.model.get('params');
                if (!this.$tabs.hasClass('ui-tabs')) {
                    this.$tabs.tabs({
                        select: function (event, ui) {
                            if ($(ui.tab).hasClass('add_tab')) {
                                return false;
                            }
                            return true;
                        }
                    });
                    this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs);
                    this.$tabs.find(".ui-tabs-nav").sortable({
                        axis  : (this.$tabs.closest('[data-element_type]').data('element_type') == 'vc_tour' ? 'y' : 'x'),
                        update: this.stopSorting,
                        items : "> li:not(.add_tab_block)"
                    });
                }
                if (view.model.get('cloned') === true) {
                    var cloned_from = view.model.get('cloned_from'),
                        $after_tab = $('[href=#tab-' + cloned_from.params.tab_id + ']', this.$content).parent(),
                        $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertAfter($after_tab);
                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", 'active', $new_tab.index());
                } else {
                    if (params.icon) {
                        $("<li> <i class='icn " + params.icon + "'></i> <a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
                            .insertBefore(this.$add_button);
                    } else {
                        $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>")
                            .insertBefore(this.$add_button);
                    }

                    this.$tabs.tabs('refresh');
                    this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.ui-tabs-nav li', this.$content).length - 2 : 0);

                }
                this.new_tab_adding = false;
            },


            cloneModel: function (model, parent_id, save_order) {
                var shortcodes_to_resort = [],
                    new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                    model_clone,
                    new_params = _.extend({}, model.get('params'));
                if (model.get('shortcode') === 'hippo_tabs') _.extend(new_params, {tab_contid: +new Date() + '-' + Math.floor(Math.random() * 11)});


                if (model.get('shortcode') === 'hippo_tab') _.extend(new_params, {tab_id: +new Date() + '-' + this.$tabs.find('[data-element-type=hippo_tab]').length + '-' + Math.floor(Math.random() * 11)});
                model_clone = Shortcodes.create({
                    shortcode  : model.get('shortcode'),
                    id         : vc_guid(),
                    parent_id  : parent_id,
                    order      : new_order,
                    cloned     : (model.get('shortcode') === 'hippo_tab' ? false : true),
                    cloned_from: model.toJSON(),
                    params     : new_params
                });
                _.each(Shortcodes.where({parent_id: model.id}), function (shortcode) {
                    this.cloneModel(shortcode, model_clone.get('id'), true);
                }, this);
                return model_clone;
            }
        });


        window.HippoIconTabView = window.VcColumnView.extend({
            events               : {
                'click > .vc_controls .vc_control-btn-delete'       : 'deleteShortcode',
                'click > .vc_controls .vc_control-btn-prepend'      : 'addElement',
                'click > .vc_controls .vc_control-btn-edit'         : 'editElement',
                'click > .vc_controls .vc_control-btn-clone'        : 'clone',
                'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
            },
            render               : function () {
                var params = this.model.get('params');
                window.HippoIconTabView.__super__.render.call(this);
                if (!params.tab_id) {
                    params.tab_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
                    this.model.save('params', params);
                }
                this.id = 'tab-' + params.tab_id;
                this.$el.attr('id', this.id);
                return this;
            },
            ready                : function (e) {
                window.HippoIconTabView.__super__.ready.call(this, e);
                this.$tabs = this.$el.closest('.wpb_tabs_holder');
                var params = this.model.get('params');
                return this;
            },
            changeShortcodeParams: function (model) {
                var params = model.get('params');
                window.HippoIconTabView.__super__.changeShortcodeParams.call(this, model);
                if (_.isObject(params) && _.isString(params.title) && _.isString(params.tab_id)) {
                    $('.ui-tabs-nav [href=#tab-' + params.tab_id + ']').text(params.title);
                }
                if (params.icon && model._changing) {
                    $('.ui-tabs-nav [href=#tab-' + params.tab_id + ']').parent().find('i.icn').remove();
                    $('.ui-tabs-nav [href=#tab-' + params.tab_id + ']').parent().prepend(" <i class='icn " + params.icon + "'></i>");

                }
            },
            deleteShortcode      : function (e) {
                _.isObject(e) && e.preventDefault();
                var answer = confirm(window.i18nLocale.press_ok_to_delete_section),
                    parent_id = this.model.get('parent_id');
                if (answer !== true) return false;
                this.model.destroy();
                if (!vc.shortcodes.where({parent_id: parent_id}).length) {
                    vc.shortcodes.get(parent_id).destroy();
                    return false;
                }
                var params = this.model.get('params'),
                    current_tab_index = $('[href=#tab-' + params.tab_id + ']', this.$tabs).parent().index();
                $('[href=#tab-' + params.tab_id + ']').parent().remove();
                var tab_length = this.$tabs.find('.ui-tabs-nav li:not(.add_tab_block)').length;
                if (tab_length > 0) {
                    this.$tabs.tabs('refresh');
                }
                if (current_tab_index < tab_length) {
                    this.$tabs.tabs("option", "active", current_tab_index);
                } else if (tab_length > 0) {
                    this.$tabs.tabs("option", "active", tab_length - 1);
                }

            },
            cloneModel           : function (model, parent_id, save_order) {
                var shortcodes_to_resort = [],
                    new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                    new_params = _.extend({}, model.get('params'));
                if (model.get('shortcode') === 'hippo_tab') _.extend(new_params, {tab_id: +new Date() + '-' + this.$tabs.find('[data-element_type=hippo_tab]').length + '-' + Math.floor(Math.random() * 11)});
                var model_clone = Shortcodes.create({
                    shortcode  : model.get('shortcode'),
                    parent_id  : parent_id,
                    order      : new_order,
                    cloned     : true,
                    cloned_from: model.toJSON(),
                    params     : new_params
                });
                _.each(Shortcodes.where({parent_id: model.id}), function (shortcode) {
                    this.cloneModel(shortcode, model_clone.id, true);
                }, this);
                return model_clone;
            }
        });


    })
})(jQuery)