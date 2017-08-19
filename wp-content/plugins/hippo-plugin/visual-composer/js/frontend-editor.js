(function ($) {

    window.InlineShortcodeView_hippo_tabs = window.InlineShortcodeView_vc_row.extend({
        events              : {
            'click > :first > .vc-empty-element'               : 'addElement',
            'click > :first > .wpb_wrapper > .ui-tabs-nav > li': 'setActiveTab'
        },
        already_build       : false,
        active_model_id     : false,
        render              : function () {

            _.bindAll(this, 'stopSorting');
            this.$tabs = this.$el.find('> .icontabs');
            window.InlineShortcodeView_hippo_tabs.__super__.render.call(this);
            return this;
        },
        changed             : function () {
            if (this.$el.find('.vc-element').length == 0) {
                this.$el.addClass('vc-empty').find('> :first > div').addClass('vc-empty-element');
            } else {
                this.$el.removeClass('vc-empty').find('> :first > div').removeClass('vc-empty-element');
            }
            this.setSorting();
        },
        setActiveTab        : function (e) {
            var $tab = $(e.currentTarget);
            this.active_model_id = $tab.data('modelId');
        },
        tabsControls        : function () {
            return this.$el.find('.wpb_tabs_nav');
        },
        buildTabs           : function (active_model) {
            var active = false;
            if (active_model) {
                this.active_model_id = active_model.get('id');
                active = this.tabsControls().find('[data-model-id=' + this.active_model_id + ']').index();
            }
            if (this.active_model_id === false) {
                this.active_model_id = this.tabsControls().find('li:first').data('modelId');
            }
            vc.frame_window.vc_iframe.buildTabs(this.$tabs, active);
        },
        beforeUpdate        : function () {
            this.$tabs.find('.wpb_tabs_heading').remove();
            vc.frame_window.vc_iframe.destroyTabs(this.$tabs);
        },
        updated             : function () {
            window.InlineShortcodeView_hippo_tabs.__super__.updated.call(this);
            this.$tabs.find('.wpb_tabs_nav:first').remove();
            vc.frame_window.vc_iframe.buildTabs(this.$tabs);
            this.setSorting();
        },
        rowsColumnsConverted: function () {
            _.each(vc.shortcodes.where({parent_id: this.model.get('id')}), function (model) {
                model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
            });
        },
        addTab              : function (model) {
            if (this.updateIfExistTab(model)) return false;
            var $control = this.buildControlHtml(model),
                $cloned_tab;
            if (model.get('cloned') && ($cloned_tab = this.tabsControls().find('[data-model-id=' + model.get('cloned_from').id + ']')).length) {
                $control.insertAfter($cloned_tab);
            } else {
                $control.appendTo(this.tabsControls());
            }
            this.changed();
            return true;
        },
        updateIfExistTab    : function (model) {
            var $tab = this.tabsControls().find('[data-model-id=' + model.get('id') + ']');
            if ($tab.length) {
                $tab.find('a').text(model.getParam('title'));
                return true;
            }
            return false;
        },
        buildControlHtml    : function (model) {
            var params = model.get('params'),
                $tab = $('<li data-model-id="' + model.get('id') + '"><a href="#tab-' + model.getParam('tab_id') + '"></a></li>');
            $tab.data('model', model);
            $tab.find('> a').text(model.getParam('title'));
            return $tab;
        },
        addElement          : function (e) {
            e && e.preventDefault();
            new vc.ShortcodesBuilder()
                .create({shortcode: 'hippo_tab',
                    params        : {
                        tab_id: vc_guid() + '-' + this.tabsControls().find('li').length,
                        title : this.getDefaultTabTitle()
                    },
                    parent_id     : this.model.get('id')
                })
                .render();
        },
        getDefaultTabTitle  : function () {
            return this.model.get('shortcode') === 'hippo_tabs' ? window.i18nLocale.tab : window.i18nLocale.slide;
        },
        setSorting          : function () {
            {
                return
            }
            vc.frame_window.vc_iframe.setTabsSorting(this);
        },
        stopSorting         : function (event, ui) {
            this.tabsControls().find('> li').each(function (key, value) {
                var model = $(this).data('model');
                model.save({order: key}, {silent: true});
            });
        },
        placeElement        : function ($view, activity) {
            var model = vc.shortcodes.get($view.data('modelId'));
            if (model && model.get('place_after_id')) {
                $view.insertAfter(vc.$page.find('[data-model-id=' + model.get('place_after_id') + ']'));
                model.unset('place_after_id');
            } else {
                $view.insertAfter(this.tabsControls());
            }
            this.changed();
        },
        removeTab           : function (model) {
            if (vc.shortcodes.where({parent_id: this.model.get('id')}).length == 1) return this.model.destroy();
            var $tab = this.tabsControls().find('[data-model-id=' + model.get('id') + ']'),
                index = $tab.index();
            if (this.tabsControls().find('[data-model-id]:eq(' + (index + 1) + ')').length) {
                vc.frame_window.vc_iframe.setActiveTab(this.$tabs, (index + 1));
            } else if (this.tabsControls().find('[data-model-id]:eq(' + (index - 1) + ')').length) {
                vc.frame_window.vc_iframe.setActiveTab(this.$tabs, (index - 1));
            } else {
                vc.frame_window.vc_iframe.setActiveTab(this.$tabs, 0);
            }
            $tab.remove();
        },
        clone               : function (e) {
            _.each(vc.shortcodes.where({parent_id: this.model.get('id')}), function (model) {
                model.set('active_before_cloned', this.active_model_id === model.get('id'));
            }, this);
            window.InlineShortcodeView_hippo_tabs.__super__.clone.call(this, e);
        }
    });
    window.InlineShortcodeView_vc_tour = window.InlineShortcodeView_hippo_tabs.extend({
        render      : function () {
            _.bindAll(this, 'stopSorting');
            this.$tabs = this.$el.find('> .wpb_tour');
            window.InlineShortcodeView_hippo_tabs.__super__.render.call(this);
            return this;
        },
        beforeUpdate: function () {
            this.$tabs.find('.wpb_tour_heading,.wpb_tour_next_prev_nav').remove();
            vc.frame_window.vc_iframe.destroyTabs(this.$tabs);
        },
        updated     : function () {
            this.$tabs.find('.wpb_tour_next_prev_nav').appendTo(this.$tabs);
            window.InlineShortcodeView_vc_tour.__super__.updated.call(this);
        }
    });

    window.InlineShortcodeView_hippo_tab = window.InlineShortcodeViewContainerWithParent.extend({
        controls_selector   : '#vc-controls-template-hippo_tab',
        render              : function () {
            var tab_id, result, active, params;
            params = this.model.get('params');
            window.InlineShortcodeView_hippo_tab.__super__.render.call(this);
            this.$tab = this.$el.find('> :first');
            if (!params.tab_id) {
                params.tab_id = vc_guid() + '-' + Math.floor(Math.random() * 11);
                this.model.save('params', params);
                tab_id = 'tab-' + params.tab_id;
                this.$tab.attr('id', tab_id);
            } else {
                tab_id = this.$tab.attr('id');
            }
            this.$el.attr('id', tab_id);
            this.$tab.attr('id', tab_id + '-real');
            if (!this.$tab.find('.vc-element').length) this.$tab.html('');
            this.$el.addClass('ui-tabs-panel wpb_ui-tabs-hide');
            this.$tab.removeClass('ui-tabs-panel wpb_ui-tabs-hide');
            if (this.parent_view && this.parent_view.addTab) {
                if (!this.parent_view.addTab(this.model))  this.$el.removeClass('wpb_ui-tabs-hide');
            }
            active = this.doSetAsActive();
            this.parent_view.buildTabs(active);
            return this;
        },
        doSetAsActive       : function () {
            var active_before_cloned = this.model.get('active_before_cloned');
            if (!this.model.get('from_content') && !this.model.get('default_content') && _.isUndefined(active_before_cloned)) {
                return this.model;
            } else if (!_.isUndefined(active_before_cloned)) {
                this.model.unset('active_before_cloned');
                if (active_before_cloned === true) return this.model;
            }
            return false;
        },
        removeView          : function (model) {
            window.InlineShortcodeView_hippo_tab.__super__.removeView.call(this, model);
            if (this.parent_view && this.parent_view.addTab) {
                this.parent_view.removeTab(model);
            }
        },
        clone               : function (e) {
            _.isObject(e) && e.preventDefault() && e.stopPropagation();
            vc.clone_index = vc.clone_index / 10;
            var clone = this.model.clone(),
                params = clone.get('params'),
                builder = new vc.ShortcodesBuilder();
            vc.CloneModel(builder, this.model, this.model.get('parent_id'));
            builder.render();
        },
        rowsColumnsConverted: function () {
            _.each(vc.shortcodes.where({parent_id: this.model.get('id')}), function (model) {
                model.view.rowsColumnsConverted && model.view.rowsColumnsConverted();
            });
        }
    });

})(window.jQuery);