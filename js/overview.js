$(document).ready(function()
{
    var overview = new Cmeo("overview");

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
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        overview.createExamples(templateId);
    });

    /**
     * Add one or more categories to the "Assigned" list and remove the same from the "Available" list
     */
    $('#addCategory').on('click', function(e) {
        e.preventDefault();
        overview.moveCategoryModal('assigned');
    });

    /**
     * Remove one or more categories from the "Assigned" list and add the same to the "Assigned" list
     */
    $('#removeCategory').on('click', function(e) {
        e.preventDefault();
        overview.moveCategoryModal('available');
    });

    /**
     * Add categories to the template via the "Select categories" pop-up (AJAX)
     */
    $('.addCategoryOverview').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        overview.addCategoryByModalView(templateId);
    });

    /**
     * Remove categories from the template via the trashcan icon
     *
     * The category will not be deleted but set on "DELETED"
     */
    $('.removeCategoryShortcut').on('click', function(){
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        var categoryId = parseInt(id[2]);
        overview.removeCategoryByShortcut(templateId, categoryId);
    });

    /**
     * Remove categories from the template via the "Select categories" pop-up (AJAX)
     *
     * The category will not be deleted but set on "DELETED"
     */
    $('.removeCategoryOverview').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        overview.removeCategoryByModalView(templateId);
    });

    /**
     * Clone a template
     */
    $(".cloneTemplate").on('click', function(){
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        overview.cloneTemplate(templateId);
    });

    /**
     * Delete a template
     */
    $(".deleteTemplate").on('click', function(){
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        overview.deleteTemplate(templateId);
    });
});
