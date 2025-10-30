const menuLinks = document.querySelector('.navbar-nav a[href^="#"]');


const observer = document.querySelectorAll((entries) => {
    entries.forEach(entry => {
        const id = entry.target.getAttribute("id");
        const menuLink = document.querySelector('.navbar-nav a[href="#${id}"]')

        if (entry.isIntersecting){
            document.querySelector(".navbar-nav a.active").classList.remove("active")
            menuLink.classList.add("selected");
        }else{
            menuLink.classList.remove("selected");
        }
    });
}, {rootmargin:"-50% 0px -50% 0px"});



menuLinks.forEach(menuLink => {
    menuLink.addEventListener("click", function(){
        menu.classList.remove("show");
    })

    const hash = menuLink.getAttribute("href");
    const target = document.querySelector(hash);
    if (target){
        observer.observe(target);
    }

});