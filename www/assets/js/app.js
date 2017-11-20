$(function() {

    // Read cookie information
    // console.log(JSON.parse($.cookie('page_information')));

    window.addEventListener('popstate', function(ev){
        var path = ev.currentTarget.window.location.href.pathname; 
        pageRefresh(ev);
    });
    
    const pushUrl = function(href) {
      history.pushState({}, '', href);
      window.dispatchEvent(new Event('popstate'));
    };

    pageRefresh = function (ev) {
        try{
            ev.preventDefault();
            var current_path = ev.target.pathname;
            var send_data = {};
            if($(ev.target).prop('tagName') == 'FORM'){
                send_data = $(ev.target).serializeArray();
            }
        }
        catch(error){
            console.log(error);
        }
        $.post(current_path, send_data , function (data) {
            for (var region in data) {

                // Removing component if not exists on the current path
                $('.region-' + region + ' .component').each(function(itm,elm){
                    var component_name = $(elm).attr('class').split(' ')[1].substring(10);
                    if (data[region][component_name] == undefined){
                        $('.region-' + region + ' .' + 'component-' + component_name).remove();
                    }
                });

                // Sets the components content
                for ( var component in data[region]){
                    
                    if($('.component-'+ component,'.region-' + region).length > 0){
                        
                        // Refresh the component content
                        if (data[region][component] != '<null>'){
                            $('.component-'+ component,'.region-' + region).html(data[region][component]);
                        }
                    }
                    else {
                        // Adds a new region
                        $('.region-' + region).prepend(data[region][component]);
                    }
                }
                
            }
            // Updating the path
            window.history.pushState('Object', 'Title', current_path);

            anchor_links();
        }, 'json');
    }


    // Capturing all the links
    function anchor_links() {
        $("a:not('.ff')").click(pageRefresh).addClass('ff');
    }

    // Capturing all forms
    function form_link() {
        $("form:not('.ff')").submit(pageRefresh).addClass('ff');
    }
    
    anchor_links();
    form_link();
});