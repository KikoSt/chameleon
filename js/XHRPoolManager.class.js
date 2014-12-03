function XHRPoolManager() {
    this.requests = new Array();
}

XHRPoolManager.prototype.registerXhr = function(xhrRequest) {
    showLoadification();
    this.requests.push(xhrRequest);
}

XHRPoolManager.prototype.unregisterXhr = function(xhrRequest) {
    var i = $.inArray(xhrRequest, this.requests);
    if(i > -1) this.requests.splice(i, 1);
    if(this.requests.length < 1) {
        hideLoadification();
    }
}
