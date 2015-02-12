function Manager(companyId, advertiserId) {
    this.companyId = companyId;
    this.advertiserId = advertiserId;

    this.debug = function() {
        console.log('companyId = ' + this.companyId);
        console.log('advertiserId = ' + this.advertiserId);
    }

    this.init = function() {
        if($('#manager_company_info').length !== 0) {
            console.log('company');
        }
        if($('#manager_advertiser_info').length !== 0) {
            console.log('advertiser');
        }
        if($('#manager_category_info').length !== 0) {
            console.log('category');
        }
        if($('#manager_active_filters').length !== 0) {
            console.log('active_filters');
        }
        if($('#manager_select_filters').length !== 0) {
            console.log('select_filters');
        }
    }
}

$(document).ready(function()
{
    var manager = new Manager(122, 170);
    manager.init();
    manager.debug();
});
