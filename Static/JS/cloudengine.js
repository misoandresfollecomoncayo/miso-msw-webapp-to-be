/*
Author: Quantumsoft
*/

"use strict";

var popup = null;
var divPreload = null;

var Form = function(pContainer, useGRecaptcha) {
    var useCaptcha = useGRecaptcha;
    
    this.reset = function() {
        var inputs = pContainer.find("input");
        var selects = pContainer.find("select");

        // Reset inputs
        for (var i=0; i<inputs.length; i++) {
            var input = $(inputs[i]);
            input.val("");
        }

        // Reset selects
        for (var i=0; i<selects.length; i++) {
            var select = $(selects[i]);
            select[0].selectedIndex = 0;
        }
        
        // If use captcha, resete that
        if (useCaptcha) {
            grecaptcha.reset();
        }
    };
    
    this.validate = function() {
        var inputs = pContainer.find("input[type!=file],textarea");
        var selects = pContainer.find("select");
        var errorInputs = new Array();
        var requiredLabel = (navigator.language.substr(0,2) === "es" ? "es requerido." : "is required.");

        // Validate inputs
        for (var i=0; i<inputs.length; i++) {
            var input = $(inputs[i]);
            var inputParent = $(input.parent());
            var exceptionId = input.attr("id") + "Exception";
            var exceptionDiv = $("#" + exceptionId);

            input.val(input.val().trim());

            if (exceptionDiv) {
                exceptionDiv.remove();
            }

            if ((input.data("required") && input[0].type !== "checkbox" && input.val() === "") ||
                (input.data("required") && input[0].type === "checkbox" && !input[0].checked)) {
                exceptionDiv = $(document.createElement("div"));
                exceptionDiv.attr("id", exceptionId);
                exceptionDiv.text(input.data("name") + " " + requiredLabel);
                exceptionDiv.addClass("float-left margin-top text-color-red text-size-xs text-weight-bold width-100 cursor-default");
                inputParent.append(exceptionDiv);

                input.addClass("input-exception");
                errorInputs.push(input);
            }
        }

        // Validate selects
        for (var i=0; i<selects.length; i++) {
            var select = $(selects[i]);
            var selectParent = $(select.parent());
            var exceptionId = select.attr("id") + "Exception";
            var exceptionDiv = $("#" + exceptionId);

            if (exceptionDiv) {
                exceptionDiv.remove();
            }

            if (select.data("required") && (select.find(":selected").attr("value") === undefined || select.find(":selected").attr("value") === "")) {
                exceptionDiv = $(document.createElement("div"));
                exceptionDiv.attr("id", exceptionId);
                exceptionDiv.text(select.data("name") + " " + requiredLabel);
                exceptionDiv.addClass("float-left margin-top text-color-red text-size-xs text-weight-bold width-100 cursor-default");
                selectParent.append(exceptionDiv);

                select.addClass("input-exception");
                errorInputs.push(select);
            }
        }
        
        // If use captcha, validate that
        if (useCaptcha && grecaptcha.getResponse() === "") {
            errorInputs.push(grecaptcha);
        }

        if (errorInputs.length > 0) {
            $(errorInputs[0]).focus();
            return false;
        }

        return true;
    };
    
};

var Notification = function(type, message) {
    var div = document.createElement("div");
    var self = this;
    
    if (type === "ERROR") {
        $(div).addClass("background-color-red");
    } else {
        $(div).addClass("background-color-green");
    }

    $(div).addClass("text-color-white width-100 padding-2x text-align-center text-weight-bold box-shadow text-size-xs cursor-pointer");
    $(div).css("position", "fixed");
    $(div).css("left", "0");
    $(div).css("top", "-35px");
    $(div).css("z-index", "10000");
    $(div).html(message);
    
    $(document.body).append($(div));
    
    $(div).on("click", function() {
        self.closeNotification();
    });
    
    $(div).animate({
        top: 0
    }, 300, function() {
        setTimeout(self.closeNotification, 5000);
    });
    
    this.closeNotification = function() {
        $(div).animate({
            top: -35
        }, 300, function() {
            $(div).remove();
        });
    };
};

function showPreload() {
    divPreload = document.createElement("div");
    var divSpinner = document.createElement("div");
    
    $(divSpinner).addClass("spinner");
    $(divPreload).append(divSpinner);
    
    $(divPreload).addClass("height-100 width-100");
    $(divPreload).css("background-color", "rgb(0,0,0,.75)");
    $(divPreload).css("left", "0");
    $(divPreload).css("position", "fixed");
    $(divPreload).css("top", "0");
    $(divPreload).css("z-index", "100000");
    
    $(document.body).append($(divPreload));
};

function closePreload() {
    $(divPreload).remove();
};