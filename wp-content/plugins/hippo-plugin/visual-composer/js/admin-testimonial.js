jQuery(function () {


    console && console.log('admin_enqueue_js.js is loaded');
// Come from vc_map -> 'js_view' => 'HippoTestimonial'
    window.HippoTestimonials = vc.shortcode_view.extend({
        // Render method called after element is added( cloned ), and on first initialisation


        initialize:function (params) {
            window.HippoTestimonials.__super__.initialize.call(this, params);

           // _.bindAll(this, 'stopSorting');

        },

        render               : function () {
            console && console.log('HippoTestimonial: render method called.');
            window.HippoTestimonials.__super__.render.call(this); //make sure to call __super__. To execute logic fron inherited view. That way you can extend original logic. Otherwise, you will fully rewrite what VC will do at this event

            return this;
        },
        ready                : function (e) {
            console && console.log('HippoTestimonial: ready method called.');
            window.HippoTestimonials.__super__.ready.call(this, e);

            console.log(this.$el);

            return this;
        },
        //Called every time when params is changed/appended. Also on first initialisation
        changeShortcodeParams: function (model) {
            console && console.log('HippoTestimonial: changeShortcodeParams method called.');
            console && console.log(model.getParam('value') + ': this was maped in vc_map() "param_name"  => "value"');
            window.HippoTestimonials.__super__.changeShortcodeParams.call(this, model);
        },
        changeShortcodeParent: function (model) {
            console && console.log('HippoTestimonial: changeShortcodeParent method called.');
            window.HippoTestimonials.__super__.changeShortcodeParent.call(this, model);
        },
        deleteShortcode      : function (e) {
            console && console.log('HippoTestimonial: deleteShortcode method called.');
            window.HippoTestimonials.__super__.deleteShortcode.call(this, e);
        },
        editElement          : function (e) {
            console && console.log('HippoTestimonial: editElement method called.');
            window.HippoTestimonials.__super__.editElement.call(this, e);
        },
        clone                : function (e) {
            console && console.log('HippoTestimonial: clone method called.');
            window.HippoTestimonials.__super__.clone.call(this, e);
        }
    });




    window.HippoTestimonial = window.VcColumnView.extend({
        // Render method called after element is added( cloned ), and on first initialisation
        render               : function () {
            console && console.log('HippoTestimonial: render method called.');
            window.HippoTestimonial.__super__.render.call(this); //make sure to call __super__. To execute logic fron inherited view. That way you can extend original logic. Otherwise, you will fully rewrite what VC will do at this event

            return this;
        },
        ready                : function (e) {
            console && console.log('HippoTestimonial: ready method called.');
            window.HippoTestimonial.__super__.ready.call(this, e);

            console.log(this.$el);

            return this;
        },
        //Called every time when params is changed/appended. Also on first initialisation
        changeShortcodeParams: function (model) {
            console && console.log('HippoTestimonial: changeShortcodeParams method called.');
            console && console.log(model.getParam('value') + ': this was maped in vc_map() "param_name"  => "value"');
            window.HippoTestimonial.__super__.changeShortcodeParams.call(this, model);
        },

        deleteShortcode      : function (e) {
            console && console.log('HippoTestimonial: deleteShortcode method called.');
            window.HippoTestimonial.__super__.deleteShortcode.call(this, e);
        }




    });


});
