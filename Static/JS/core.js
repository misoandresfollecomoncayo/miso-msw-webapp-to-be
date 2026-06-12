var URL_API = "https://www.uniexpresssolutions.com/API/";
var URL_PLATFORM = "https://www.uniexpresssolutions.com/Platform/";

class Entity {
    
    constructor(id,type) {
        this.id = id;
        this.type = type;
    }
    
}

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

$("[name=btnLang]").on("click", function() {
    $.ajax({
        url: URL_API + "Session/ChangeLanguage.php",
        data: {
            Language: $(this).data("lang")
        },
        beforeSend: function() {
            showPreload();
        },
        success: function() {
            document.location.reload();
        }
    });
});

class MessageBox {
    
    constructor(text) {
        this.text = text;
    }
    
    show() {
        this.background = $(document.createElement("div"));
        this.background.addClass("popup-background");
        this.background.css("align-items","center");
        
        var self = this;
        
        this.background.on("click", function(e) {
            if (e.target !== this) return;
            self.close();
        });
        
        var content = $(document.createElement("div"));
        content.addClass("messagebox");
        content.html(this.text);
        self.background.append(content);
        
        $(document.body).append(this.background);
    }
    
    close() {
        this.background.remove();
    }
}

class Popup {
    
    constructor(path) {
        this.path = path;
    }
    
    show() {
        this.background = $(document.createElement("div"));
        this.background.addClass("popup-background");
        
        var self = this;
        
        this.background.on("click", function(e) {
            if (e.target !== this) return;
            self.close();
        });
        
        $.ajax({
            url: this.path,
            success: function(r) {
                var content = $(document.createElement("div"));
                content.html(r);
                self.background.append(content);
            }
        });
        
        $(document.body).append(this.background);
    }
    
    close() {
        this.background.remove();
    }
    
}

class NewContextMenu {
                
    constructor(event, options) {
        this.callerX = event.clientX;
        this.callerY = event.clientY;
        this.callerWidth = 0;
        this.callerHeight = 0;

        this.divContainer = $(document.createElement("div"));
        this.divContainer.addClass("context-menu");

        for (var i=0; i<options.length; i++) {
            var option = options[i];

            let item = document.createElement("div");
            item.innerHTML = option.text;
            item.setAttribute('index', i);
            item.addEventListener("click", function(e) {
                options[item.getAttribute("index")].fn();
            });
            
            $(this.divContainer).append(item);
        }

        $(document.body).append(this.divContainer);

        this.containerHeight = this.divContainer.height();
        this.containerWidth = this.divContainer.width();

        this.divContainer.css("left", (this.callerX - this.containerWidth) + "px");
        this.divContainer.css("top", (this.callerY + this.callerHeight) + "px");
        
        let self = this;
        
        $(document).on("click", function(e) {
            self.dismiss();
        });
    }

    dismiss() {
        this.divContainer.remove();
    }

}
