import './stringExtensions.d.ts';

String.prototype.trimTrailingSlash = function () {
    return this.replace(/\/$/, '');
};

String.prototype.trimKnownProtocols = function () {
    return this.replace(/(https?:\/\/)/, '');
}