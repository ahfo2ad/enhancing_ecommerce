
$(function() {


    // dashboard function

    $(".toggle-info").click(function() {

        $(this).toggleClass("selected").parent().next(".panel-body").fadeToggle(150);

        if($(this).hasClass("selected")) {

            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
        else{

            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });

    //  calling selectboxit

    $("select").selectBoxIt({

        autoWidth: false
    });

    // show and hide focus function

    $("[placeholder]").focus(function(){

        $(this).attr("data-text", $(this).attr("placeholder"));

        $(this).attr("placeholder", "");
    }).blur(function(){

        $(this).attr("placeholder", $(this).attr("data-text"));
    });

    // required attribure function

    $("input").each(function() {

        if($(this).attr("required") == "required") {

            $(this).after('<span class="astix">*</span>');
        }
    });

    // show password function

    var showpass = $(".password");

    $(".show-pass").hover(function() {

        showpass.attr("type", "text");

    }, function() {

        showpass.attr("type", "password");
    });

    // connfirmation message function

    $(".confirm").click(function() {

        return confirm("Are You Sure?");
    });


    $(".categ h3").click(function() {

        $(this).next(".full-view").fadeToggle(300);
    });

    $(".ordering span").click(function() {

        $(this).addClass("active").siblings("span").removeClass("active");

        if($(this).data("view") === "full") {

            $(".categ .full-view").fadeIn(300);
        }
        else {

            $(".categ .full-view").fadeOut(300);
        }
    });

    // show and hide delete button on child categories

    $(".showlinks").hover(function(){

        $(this).find(".show-delete").fadeIn(300);

    }, function() {

        $(this).find(".show-delete").fadeOut(300);
    });

});

    // start toggle between light and dark mode


    // $(".mode").on("click", function() {
    //         $("*").toggleClass("dark-mode");
            
    //     });

    // $(function() {
    //     $(".mode").click(function() {

    //         $("*").toggleClass("dark-mode");
    //     });
    // });


$('*').toggleClass(localStorage.toggled);

function darkLight() {
    /*DARK CLASS*/
    if (localStorage.toggled != 'dark') {

        $('*').toggleClass('dark', true);
        localStorage.toggled = "dark";
            
        } 
    else {
        $('*').toggleClass('dark', false);
        localStorage.toggled = "";
        }
    }

    /*Add 'checked' property to input if background == dark*/
    if ($('*').hasClass('dark')) {

        $( '#checkBox' ).prop( "checked", true )
    } 
    else {

        $( '#checkBox' ).prop( "checked", false )
    }


    // end toggle between light and dark mode