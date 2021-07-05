/*!
* Start Bootstrap - Agency v7.0.4 (https://startbootstrap.com/theme/agency)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE)
*/
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector("#mainNav");
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove("navbar-shrink");
        } else {
            navbarCollapsible.classList.add("navbar-shrink");
        }

    };

    // Shrink the navbar
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener("scroll", navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector("#mainNav");
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: "#mainNav",
            offset: 74,
        });
    }

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector(".navbar-toggler");
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll("#navbarResponsive .nav-link")
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener("click", () => {
            if (window.getComputedStyle(navbarToggler).display !== "none") {
                navbarToggler.click();
            }
        });
    });

    // If on the home page, toogle the "home" class on navbar element
    var url = document.location.href;
    let button = document.getElementById("mainNav");
    var regex = new RegExp("http://monsite.fr/#.+");
if (url === "http://monsite.fr/" || regex.test(url)) {
    button.classList.toggle("home");
}

    var btnReplyComment = document.body.getElementsByClassName("reply");

    var form = document.getElementById("form-comment");

for (element of btnReplyComment) {
    element.addEventListener("click",function () {
            
        if (document.getElementById("form-clone")) {
            var content = document.getElementById("form-clone").querySelector("#contenu").value;
            document.getElementById("form-clone").remove();
        }

        var formClone = form.cloneNode(true);
        formClone.id="form-clone";
        var parentId = this.getAttribute("data-id");
        var comment = document.getElementById("comment-"+ parentId);
        var inputHidden = document.getElementById("parentId");
        var inputHiddenClone = formClone.children[1];

        comment.insertAdjacentElement("afterEnd",formClone);
        form.getElementsByTagName("textarea")[0].value="";
        if (content) {
            formClone.getElementsByTagName("textarea")[0].value=content;
        }
        formClone.querySelector("#contenu").focus();
        formClone.querySelector("label").innerHTML="Répondre à ce commentaire";
        inputHidden.value = parentId;
        inputHiddenClone.value = parentId;
    });
}

    form.addEventListener("click", function () {
        if (document.getElementById("form-clone")) {
            var content = document.getElementById("form-clone").querySelector("#contenu").value;
            document.getElementById("form-clone").remove();
        }
        if (content) {
            this.getElementsByTagName("textarea")[0].value=content;
        }
    });

})
