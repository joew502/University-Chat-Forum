/**
 *  When the user clicks on the button, toggle between hiding and showing the dropdown content */
function logo(e) {
    //console.log("here")
    document.getElementById("NB-dropdown-menu").classList.toggle("NB-show");
    e.stopPropagation();
}
//document.getElementById("NB-profile").addEventListener("click", logo(e))



/**
 * Close the dropdown if the user clicks outside of it
 */
window.addEventListener("click", function(e) {
    if (!e.target.matches('.navbar-level-wrapper')) {
        let myDropdown = document.getElementById("NB-dropdown-menu");
        if (myDropdown != null && myDropdown.classList.contains('NB-show')) {
            myDropdown.classList.remove('NB-show');
        }
    }
});
