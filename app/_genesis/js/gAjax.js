(function($) {
    $.gAjax = {
        load: function(page, param, target, callback) {
            jQuery.ajax({
                type: "POST",
                url: page,
                data: param,
                beforeSend: function() {
                    jQuery.gDisplay.loadStart(target);
                },
                error: function() {
                    jQuery.gDisplay.loadError(target, "Error loading page...");
                },
                success: function(resp) {
                    jQuery.gDisplay.loadStop(target);
                    jQuery(target).html(resp);

                    if (typeof callback === 'function') {
                        callback.call(this, resp);
                    }
                }
            });
        },
        exec: function(method, page, param, alert, callback, async, preloader) {
            if (async === undefined)
                async = true;
            method = (method === undefined) ? "POST" : method;
            jQuery.ajax({
                type: method,
                url: page,
                data: param,
                dataType: 'json',
                async: async,
                beforeSend: function() {
                    if (preloader === undefined || preloader === true)
                        jQuery.gDisplay.loadStart('html');
                },
                error: function(json) {
                    var msg = "Error loading page... ";
                    console.log( json );
                    if(json.responseJSON !== undefined){
                        msg = json.responseJSON.msg;
                    }
                    jQuery.gDisplay.loadError('html', msg);
                },
                success: function(json) {
                    if (preloader === undefined || preloader === true)
                        jQuery.gDisplay.loadStop('html');

                    if (typeof callback === 'function') {
                        callback.call(this, json);
                    }

                    if (alert === undefined || alert === true)
                        jQuery.gDisplay.showAlert(json, '', '');
                    else {
                        if (!json.status) {
                            jQuery.gDisplay.showError(json.msg, '');
                        }
                    }
                }
            });
        },
        execCallback: function(page, param, alert, callback, async, preloader) {
            if (async === undefined)
                async = true;
            jQuery.ajax({
                type: "POST",
                url: page,
                data: param,
                dataType: 'json',
                async: async,
                beforeSend: function() {
                    if (preloader === undefined || preloader === true)
                        jQuery.gDisplay.loadStart('html');
                },
                error: function() {
                    if (preloader === undefined || preloader === true)
                        jQuery.gDisplay.loadError('html', "Error loading page...");
                },
                success: function(json) {
                    if (preloader === undefined || preloader === true)
                        jQuery.gDisplay.loadStop('html');

                    if (typeof callback === 'function') {
                        callback.call(this, json);
                    }

                    if (alert === undefined || alert === true)
                        jQuery.gDisplay.showAlert(json, '', '');
                    else {
                        if (!json.status) {
                            jQuery.gDisplay.showError(json.msg, '');
                        }
                    }
                }
            });
        }
    }
})(jQuery);