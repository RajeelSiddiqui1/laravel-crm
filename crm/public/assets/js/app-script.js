$(function () {
    "use strict";

    // Sidebar menu js
    $.sidebarMenu($('.sidebar-menu'));

    // Toggle menu js
    $(".toggle-menu").on("click", function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    // Sidebar menu activation js
    for (var i = window.location, o = $(".sidebar-menu a").filter(function () {
        return this.href == i;
    }).addClass("active").parent().addClass("active"); ;) {
        if (!o.is("li")) break;
        o = o.parent().addClass("in").parent().addClass("active");
    }

    /* Top Header */
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 60) {
            $('.topbar-nav .navbar').addClass('bg-dark');
        } else {
            $('.topbar-nav .navbar').removeClass('bg-dark');
        }
    });

    /* Back To Top */
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    $('.back-to-top').on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    // Popover and Tooltip
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    // Theme setting
    $(".switcher-icon").on("click", function (e) {
        e.preventDefault();
        $(".right-sidebar").toggleClass("right-toggled");
    });

    // Load theme from local storage on page load
    function loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            $('body').attr('class', 'bg-theme ' + savedTheme);
        } else {
            // Default theme if none is saved
            $('body').attr('class', 'bg-theme bg-theme1');
        }
    }

    // Call loadTheme on page load
    loadTheme();

    // Theme click handlers
    $('#theme1').click(function () { applyTheme('bg-theme1'); });
    $('#theme2').click(function () { applyTheme('bg-theme2'); });
    $('#theme3').click(function () { applyTheme('bg-theme3'); });
    $('#theme4').click(function () { applyTheme('bg-theme4'); });
    $('#theme5').click(function () { applyTheme('bg-theme5'); });
    $('#theme6').click(function () { applyTheme('bg-theme6'); });
    $('#theme7').click(function () { applyTheme('bg-theme7'); });
    $('#theme8').click(function () { applyTheme('bg-theme8'); });
    $('#theme9').click(function () { applyTheme('bg-theme9'); });
    $('#theme10').click(function () { applyTheme('bg-theme10'); });
    $('#theme11').click(function () { applyTheme('bg-theme11'); });
    $('#theme12').click(function () { applyTheme('bg-theme12'); });
    $('#theme13').click(function () { applyTheme('bg-theme13'); });
    $('#theme14').click(function () { applyTheme('bg-theme14'); });
    $('#theme15').click(function () { applyTheme('bg-theme15'); });

    // Function to apply and save theme
    function applyTheme(themeClass) {
        $('body').attr('class', 'bg-theme ' + themeClass);
        localStorage.setItem('theme', themeClass);
    }
});