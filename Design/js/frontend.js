$(function() {

    // toogle between login and signup

    $(".login-page h1 span").click(function() {

        $(this).addClass("active").siblings().removeClass("active");

        $(".login-page form").hide();

        $("." + $(this).data("class")).fadeIn(100);
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

    // live-preview function depending on data-class

    $(".live").keyup(function() {

        $($(this).data("class")).text($(this).val());

    });

    // live-preview function depending on class for eaach field

    /*
    $(".live-name").keyup(function() {

        $(".live-preview .caption h3").text($(this).val());
        
    });
    $(".live-descripe").keyup(function() {

        $(".live-preview .caption p").text($(this).val());
        
    });

    $(".live-price").keyup(function() {

        $(".live-preview .price").text("$" + $(this).val());
        
    });
    */

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