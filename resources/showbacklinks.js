
function showbacklinksToogleCollapse(toggleId, collapseBodyId, visibleText, hiddenText) {
    var container = document.getElementById(collapseBodyId);
    var toggle = document.getElementById(toggleId);
    if (window.getComputedStyle(container).display === "none") {
        container.style.display = "block";
        toggle.innerHTML = "[ " + visibleText + " ]";
    }
    else {
        container.style.display = "none";
        toggle.innerHTML = "[ " + hiddenText + " ]";
    }

}