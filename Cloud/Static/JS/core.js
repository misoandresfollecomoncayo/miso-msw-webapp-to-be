var URL_API = "https://www.quantumsoft.co/Cloud/API/";
var URL_PLATFORM = "https://www.quantumsoft.co/Cloud/";

$("#btnMobileMenu").on("click", function() {
    $("#divMobileMenuOverlay").show();
    $("#divMainBar").removeClass("close-main-bar");
    $("#divMainBar").addClass("open-main-bar");
});

$("#divMobileMenuOverlay").on("click", function() {
    $("#divMobileMenuOverlay").hide();
    $("#divMainBar").removeClass("open-main-bar");
    $("#divMainBar").addClass("close-main-bar");
});
