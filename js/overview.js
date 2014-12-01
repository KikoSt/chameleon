$(document).ready(function()
{
    var overview = new Constructor("overview");

    /**
     * Initializing of the carousel
     */
    $('.carousel').carousel({
        interval: 4000
    });

    /**
     *
     */
    $('.ajaxPreview').each(function(){
        overview.setClickTarget($(this));
        overview.createExamples();
    });

    /**
     * Add one or more categories to the "Assigned" list
     */
    $('#addCategory').click(function() {
        overview.moveCategoryModal('assigned');
        e.preventDefault();
    });

    /**
     * Remove one or more categories from the "Assigned" list
     */
    $('#removeCategory').click(function(e) {
        overview.moveCategoryModal('available');
        e.preventDefault();
    });

    /**
     * Add categories to the template via the "Select categories" pop-up (AJAX)
     */
    $('.addCategoryOverview').click(function(e) {
        e.preventDefault();
        overview.setClickTarget($(this));
        overview.addCategoryModal();
    });

    /**
     * Remove categories from the template via the trashcan icon
     *
     * The category will not be deleted but set on "DELETED"
     */
    $('.removeCategoryShortcut').click(function(){
        overview.setClickTarget($(this));
        overview.removeCategoryShortcut();
    });

    /**
     * Remove categories from the template via the "Select categories" pop-up (AJAX)
     *
     * The category will not be deleted but set on "DELETED"
     */
    $('.removeCategoryOverview').click(function(e) {
        e.preventDefault();
        overview.setClickTarget($(this));
        overview.removeCategoryModal();
    });

    /**
     *
     */
    $(".cloneTemplate").click(function(){
        overview.setClickTarget($(this));
        overview.cloneTemplate();
    });

    /**
     *
     */
    $(".deleteTemplate").click(function(){
        overview.setClickTarget($(this));
        overview.deleteTemplate();
    });
});