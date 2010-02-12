/*
 * this script is included in the messagebox iframe, and resets the unread message count in the navbar.
 */ 

Drupal.behaviors.dailygiftBehavior = function() {
// Trigger invite friends thickbox instead of dailychips confirmation
setTimeout(function() {
    var bl = $(".buddy_list_placeholder a.thickbox");
    if (bl.length > 0) {
        bl.eq(0).trigger('click');
    } 
}, 1000);
}


