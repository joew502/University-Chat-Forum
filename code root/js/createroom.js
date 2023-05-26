/**
 * Sanitise function to make sure that a title and contents for a room is valid before creation
 * @returns {boolean}   returns false if fail
 */
function verifyRoom() {
    if (!SANE_sane(document.getElementById("CRF-create-room-title").value, "room_name")) {
        alert("room name invalid")
        return false;
    }
    if (!SANE_sane(document.getElementById("CRF-create-room-body").value, "room_text")) {
        alert("room content invalid")
        return false;
    }
}
