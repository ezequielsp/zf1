var menuClicked = function(self) {
    $('.navbar-nav li').removeClass("active");
    $(self).parent().addClass('active');
    //$(self).find('i').addClass('icon-white');

    var menu = $(self).data("value");
    var name = $(self).attr("name");
    $(self).off('click');
    
    $.ajax({
        url: menu,
        type: "GET",
        dataType: "html",
        success: function(response) {
            $('#js-main-content').html(response);
            window.history.pushState('', '', menu);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // XMLHttpRequest.responseText has your json string
            // XMLHttpRequest.status has the 401 status code
            if (XMLHttpRequest.status === 401) {
                location.href = '/auth/logout';
            }
        },
        complete: function(XMLHttpRequest, textStatus, errorThrown) {
            // kill the block on either success or error
            $(self).on('click', function() {
                menuClicked(this);
            });

            $.getScript( "/src/js/config.js" );
            $.getScript( "/src/js/modal.js" );
        }
    });
};

$(".js-main-menu").on('click',  function(e) {
    $(this).off(e);
    e.preventDefault();
    menuClicked(this);
});