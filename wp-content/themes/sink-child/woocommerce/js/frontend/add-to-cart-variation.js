!function(t, a, i, r) {
    console.log('mierda de gallinas');
    var e = function(t) {
        this.$form = t,
        this.$attributeFields = t.find(".variations select"),
        this.$singleVariation = t.find(".single_variation"),
        this.$singleVariationWrap = t.find(".single_variation_wrap"),
        this.$resetVariations = t.find(".reset_variations"),
        this.$product = t.closest(".product"),
        this.variationData = t.data("product_variations"),
        this.useAjax = !1 === this.variationData,
        this.xhr = !1,
        this.$singleVariationWrap.show(),
        this.$form.off(".wc-variation-form"),
        this.getChosenAttributes = this.getChosenAttributes.bind(this),
        this.findMatchingVariations = this.findMatchingVariations.bind(this),
        this.isMatch = this.isMatch.bind(this),
        this.toggleResetLink = this.toggleResetLink.bind(this),
        t.on("click.wc-variation-form", ".reset_variations", {
            variationForm: this
        }, this.onReset),
        t.on("reload_product_variations", {
            variationForm: this
        }, this.onReload),
        t.on("hide_variation", {
            variationForm: this
        }, this.onHide),
        t.on("show_variation", {
            variationForm: this
        }, this.onShow),
        t.on("click", ".single_add_to_cart_button", {
            variationForm: this
        }, this.onAddToCart),
        t.on("reset_data", {
            variationForm: this
        }, this.onResetDisplayedVariation),
        t.on("reset_image", {
            variationForm: this
        }, this.onResetImage),
        t.on("change.wc-variation-form", ".variations select", {
            variationForm: this
        }, this.onChange),
        t.on("found_variation.wc-variation-form", {
            variationForm: this
        }, this.onFoundVariation),
        t.on("check_variations.wc-variation-form", {
            variationForm: this
        }, this.onFindVariation),
        t.on("update_variation_values.wc-variation-form", {
            variationForm: this
        }, this.onUpdateAttributes),
        t.trigger("check_variations"),
        t.trigger("wc_variation_form")
    };
    e.prototype.onReset = function(t) {
        t.preventDefault(),
        t.data.variationForm.$attributeFields.val("").change(),
        t.data.variationForm.$form.trigger("reset_data")
    }
    ,
    e.prototype.onReload = function(t) {
        var a = t.data.variationForm;
        a.variationData = a.$form.data("product_variations"),
        a.useAjax = !1 === a.variationData,
        a.$form.trigger("check_variations")
    }
    ,
    e.prototype.onHide = function(t) {
        t.preventDefault(),
        t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-is-unavailable").addClass("disabled wc-variation-selection-needed"),
        t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled")
    }
    ,
    e.prototype.onShow = function(t, a, i) {
        t.preventDefault(),
        i ? (t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("disabled wc-variation-selection-needed wc-variation-is-unavailable"),
        t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-disabled").addClass("woocommerce-variation-add-to-cart-enabled")) : (t.data.variationForm.$form.find(".single_add_to_cart_button").removeClass("wc-variation-selection-needed").addClass("disabled wc-variation-is-unavailable"),
        t.data.variationForm.$form.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled"))
    }
    ,
    e.prototype.onAddToCart = function(i) {
        t(this).is(".disabled") && (i.preventDefault(),
        t(this).is(".wc-variation-is-unavailable") ? a.alert(wc_add_to_cart_variation_params.i18n_unavailable_text) : t(this).is(".wc-variation-selection-needed") && a.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text))
    }
    ,
    e.prototype.onResetDisplayedVariation = function(t) {
        var a = t.data.variationForm;
        a.$product.find(".product_meta").find(".sku").wc_reset_content(),
        a.$product.find(".product_weight").wc_reset_content(),
        a.$product.find(".product_dimensions").wc_reset_content(),
        a.$form.trigger("reset_image"),
        a.$singleVariation.slideUp(200).trigger("hide_variation")
    }
    ,
    e.prototype.onResetImage = function(t) {
        t.data.variationForm.$form.wc_variations_image_update(!1)
    }
    ,
    e.prototype.onFindVariation = function(a) {
        var i = a.data.variationForm
          , r = i.getChosenAttributes()
          , e = r.data;
        if (r.count === r.chosenCount)
            if (i.useAjax)
                i.xhr && i.xhr.abort(),
                i.$form.block({
                    message: null,
                    overlayCSS: {
                        background: "#fff",
                        opacity: .6
                    }
                }),
                e.product_id = parseInt(i.$form.data("product_id"), 10),
                e.custom_data = i.$form.data("custom_data"),
                i.xhr = t.ajax({
                    url: wc_cart_fragments_params.wc_ajax_url.toString().replace("%%endpoint%%", "get_variation"),
                    type: "POST",
                    data: e,
                    success: function(t) {
                        t ? i.$form.trigger("found_variation", [t]) : (i.$form.trigger("reset_data"),
                        i.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + "</p>"),
                        i.$form.find(".wc-no-matching-variations").slideDown(200))
                    },
                    complete: function() {
                        i.$form.unblock()
                    }
                });
            else {
                i.$form.trigger("update_variation_values");
                var o = i.findMatchingVariations(i.variationData, e).shift();
                o ? i.$form.trigger("found_variation", [o]) : (i.$form.trigger("reset_data"),
                i.$form.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + "</p>"),
                i.$form.find(".wc-no-matching-variations").slideDown(200))
            }
        else
            i.$form.trigger("update_variation_values"),
            i.$form.trigger("reset_data");
        i.toggleResetLink(r.chosenCount > 0)
    }
    ,
    e.prototype.onFoundVariation = function(a, i) {
        var r = a.data.variationForm
          , e = r.$product.find(".product_meta").find(".sku")
          , o = r.$product.find(".product_weight")
          , n = r.$product.find(".product_dimensions")
          , s = r.$singleVariationWrap.find(".quantity")
          , _ = !0
          , c = !1
          , d = "";
        i.sku ? e.wc_set_content(i.sku) : e.wc_reset_content(),
        i.weight ? o.wc_set_content(i.weight_html) : o.wc_reset_content(),
        i.dimensions ? n.wc_set_content(i.dimensions_html) : n.wc_reset_content(),
        r.$form.wc_variations_image_update(i),
        i.variation_is_visible ? (c = wp.template("variation-template"),
        i.variation_id) : c = wp.template("unavailable-variation-template"),
        d = (d = (d = c({
            variation: i
        })).replace("/*<![CDATA[*/", "")).replace("/*]]>*/", ""),
        r.$singleVariation.html(d),
        r.$form.find('input[name="variation_id"], input.variation_id').val(i.variation_id).change(),
        "yes" === i.is_sold_individually ? (s.find("input.qty").val("1").attr("min", "1").attr("max", ""),
        s.hide()) : (s.find("input.qty").attr("min", i.min_qty).attr("max", i.max_qty),
        s.show()),
        i.is_purchasable && i.is_in_stock && i.variation_is_visible || (_ = !1),
        t.trim(r.$singleVariation.text()) ? r.$singleVariation.slideDown(200).trigger("show_variation", [i, _]) : r.$singleVariation.show().trigger("show_variation", [i, _])
    }
    ,
    e.prototype.onChange = function(a) {
        var i = a.data.variationForm;
        i.$form.find('input[name="variation_id"], input.variation_id').val("").change(),
        i.$form.find(".wc-no-matching-variations").remove(),
        i.useAjax ? i.$form.trigger("check_variations") : (i.$form.trigger("woocommerce_variation_select_change"),
        i.$form.trigger("check_variations"),
        t(this).blur()),
        i.$form.trigger("woocommerce_variation_has_changed")
    }
    ,
    e.prototype.addSlashes = function(t) {
        return t = t.replace(/'/g, "\\'"),
        t = t.replace(/"/g, '\\"')
    }
    ,
    e.prototype.onUpdateAttributes = function(a) {
        var i = a.data.variationForm
          , r = i.getChosenAttributes().data;
        i.useAjax || (i.$attributeFields.each(function(a, e) {
            var o = t(e)
              , n = o.data("attribute_name") || o.attr("name")
              , s = t(e).data("show_option_none")
              , _ = ":gt(0)"
              , c = 0
              , d = t("<select/>")
              , m = o.val() || ""
              , v = !0;
            if (!o.data("attribute_html")) {
                var l = o.clone();
                l.find("option").removeAttr("disabled attached").removeAttr("selected"),
                o.data("attribute_options", l.find("option" + _).get()),
                o.data("attribute_html", l.html())
            }
            d.html(o.data("attribute_html"));
            var h = t.extend(!0, {}, r);
            h[n] = "";
            var g = i.findMatchingVariations(i.variationData, h);
            for (var f in g)
                if ("undefined" != typeof g[f]) {
                    var u = g[f].attributes;
                    for (var p in u)
                        if (u.hasOwnProperty(p)) {
                            var w = u[p]
                              , b = "";
                            p === n && (g[f].variation_is_active && (b = "enabled"),
                            w ? (w = t("<div/>").html(w).text(),
                            d.find('option[value="' + i.addSlashes(w) + '"]').addClass("attached " + b)) : d.find("option:gt(0)").addClass("attached " + b))
                        }
                }
            c = d.find("option.attached").length,
            !m || 0 !== c && 0 !== d.find('option.attached.enabled[value="' + i.addSlashes(m) + '"]').length || (v = !1),
            c > 0 && m && v && "no" === s && (d.find("option:first").remove(),
            _ = ""),
            d.find("option" + _ + ":not(.attached)").remove(),
            o.html(d.html()),
            o.find("option" + _ + ":not(.enabled)").prop("disabled", !0),
            m ? v ? o.val(m) : o.val("").change() : o.val("")
        }),
        i.$form.trigger("woocommerce_update_variation_values"))
    }
    ,
    e.prototype.getChosenAttributes = function() {
        var a = {}
          , i = 0
          , r = 0;
        return this.$attributeFields.each(function() {
            var e = t(this).data("attribute_name") || t(this).attr("name")
              , o = t(this).val() || "";
            o.length > 0 && r++,
            i++,
            a[e] = o
        }),
        {
            count: i,
            chosenCount: r,
            data: a
        }
    }
    ,
    e.prototype.findMatchingVariations = function(t, a) {
        for (var i = [], r = 0; r < t.length; r++) {
            var e = t[r];
            this.isMatch(e.attributes, a) && i.push(e)
        }
        return i
    }
    ,
    e.prototype.isMatch = function(t, a) {
        var i = !0;
        for (var r in t)
            if (t.hasOwnProperty(r)) {
                var e = t[r]
                  , o = a[r];
                void 0 !== e && void 0 !== o && 0 !== e.length && 0 !== o.length && e !== o && (i = !1)
            }
        return i
    }
    ,
    e.prototype.toggleResetLink = function(t) {
        t ? "hidden" === this.$resetVariations.css("visibility") && this.$resetVariations.css("visibility", "visible").hide().fadeIn() : this.$resetVariations.css("visibility", "hidden")
    }
    ,
    t.fn.wc_variation_form = function() {
        return new e(this),
        this
    }
    ,
    t.fn.wc_set_content = function(t) {
        void 0 === this.attr("data-o_content") && this.attr("data-o_content", this.text()),
        this.text(t)
    }
    ,
    t.fn.wc_reset_content = function() {
        void 0 !== this.attr("data-o_content") && this.text(this.attr("data-o_content"))
    }
    ,
    t.fn.wc_set_variation_attr = function(t, a) {
        void 0 === this.attr("data-o_" + t) && this.attr("data-o_" + t, this.attr(t) ? this.attr(t) : ""),
        !1 === a ? this.removeAttr(t) : this.attr(t, a)
    }
    ,
    t.fn.wc_reset_variation_attr = function(t) {
        void 0 !== this.attr("data-o_" + t) && this.attr(t, this.attr("data-o_" + t))
    }
    ,
    t.fn.wc_maybe_trigger_slide_position_reset = function(a) {
        var i = t(this)
          , r = i.closest(".product").find(".images")
          , e = !1
          , o = a && a.image_id ? a.image_id : "";
        i.attr("current-image") !== o && (e = !0),
        i.attr("current-image", o),
        e && r.trigger("woocommerce_gallery_reset_slide_position")
    }
    ,
    t.fn.wc_variations_image_update = function(i) {
        var r = this
          , e = r.closest(".product")
          , o = e.find(".images")
          , n = e.find(".flex-control-nav li:eq(0) img")
          , s = o.find(".woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder").eq(0)
          , _ = s.find(".wp-post-image")
          , c = s.find("a").eq(0);
        if (i && i.image && i.image.src && i.image.src.length > 1) {
            if (t('.flex-control-nav li img[src="' + i.image.thumb_src + '"]').length > 0)
                return (n = t('.flex-control-nav li img[src="' + i.image.thumb_src + '"]')).trigger("click"),
                void r.attr("current-image", i.image_id);
            _.wc_set_variation_attr("src", i.image.src),
            _.wc_set_variation_attr("height", i.image.src_h),
            _.wc_set_variation_attr("width", i.image.src_w),
            _.wc_set_variation_attr("srcset", i.image.srcset),
            _.wc_set_variation_attr("sizes", i.image.sizes),
            _.wc_set_variation_attr("title", i.image.title),
            _.wc_set_variation_attr("alt", i.image.alt),
            _.wc_set_variation_attr("data-src", i.image.full_src),
            _.wc_set_variation_attr("data-large_image", i.image.full_src),
            _.wc_set_variation_attr("data-large_image_width", i.image.full_src_w),
            _.wc_set_variation_attr("data-large_image_height", i.image.full_src_h),
            s.wc_set_variation_attr("data-thumb", i.image.src),
            n.wc_set_variation_attr("src", i.image.thumb_src),
            c.wc_set_variation_attr("href", i.image.full_src)
        } else
            _.wc_reset_variation_attr("src"),
            _.wc_reset_variation_attr("width"),
            _.wc_reset_variation_attr("height"),
            _.wc_reset_variation_attr("srcset"),
            _.wc_reset_variation_attr("sizes"),
            _.wc_reset_variation_attr("title"),
            _.wc_reset_variation_attr("alt"),
            _.wc_reset_variation_attr("data-src"),
            _.wc_reset_variation_attr("data-large_image"),
            _.wc_reset_variation_attr("data-large_image_width"),
            _.wc_reset_variation_attr("data-large_image_height"),
            s.wc_reset_variation_attr("data-thumb"),
            n.wc_reset_variation_attr("src"),
            c.wc_reset_variation_attr("href");
        a.setTimeout(function() {
            t(a).trigger("resize"),
            r.wc_maybe_trigger_slide_position_reset(i),
            o.trigger("woocommerce_gallery_init_zoom")
        }, 20)
    }
    ,
    t(function() {
        "undefined" != typeof wc_add_to_cart_variation_params && t(".variations_form").each(function() {
            t(this).wc_variation_form()
        })
    })
}(jQuery, window, document);
