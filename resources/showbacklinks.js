
function showbacklinksToogleCollapse(toggleId, collapseBodyId) {
    alert(toggleId);
    var container = document.getElementById(collapseBodyId);
    if (window.getComputedStyle(container).display === "none") {
        container.style.display = "block";
    }
    else {
        container.style.display = "none";
    }

}