(function($){
    $(document).ready(function(){
        var MEASUREMENTS_ID = 'measurements'; // abstracted-out for convenience in renaming
        $("body").append('<div id="'+MEASUREMENTS_ID+'"></div>');
        $("#"+MEASUREMENTS_ID).css({
            'position': 'fixed',
            'bottom': '120px',
            'right': '0',
            'background-color': 'black',
            'color': 'white',
            'padding': '5px',
            'font-size': '14px',
            'opacity': '1',
            'z-index': '1000'
        });
        getDimensions = function(){
            return $(window).width() + ' (' + $(document).width() + ') x ' + $(window).height() + ' (' + $(document).height() + ')';
        }
        $("#"+MEASUREMENTS_ID).text(getDimensions());
        $(window).on("resize", function(){
            $("#"+MEASUREMENTS_ID).text(getDimensions());
        });
    });
})(jQuery);
