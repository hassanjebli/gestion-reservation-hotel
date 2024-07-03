
       

        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("collapsed");
            $(".main-content").toggleClass("collapsed");
            if ($(window).width() <= 768) {
                $("#sidebar-wrapper").toggleClass("show");
            }
        });

        $("#close-sidebar").click(function() {
            $("#sidebar-wrapper").removeClass("show");
        });