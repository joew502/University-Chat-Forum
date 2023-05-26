/**
 * Set the width of the sidebar to 20vw and the left margin of the page content to 20vw
 */
function openSidebar() {
    document.getElementById("sidebar").style.width = "32vw";
}

/**
 * Set the width of the sidebar to 0 and the left margin of the main content to 0
 */
function closeSidebar() {
    document.getElementById("sidebar").style.width = "0";
}

/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content*/
let dropdown = document.getElementsByClassName("SB-dropdown-btn");
let i;

for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        let dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
