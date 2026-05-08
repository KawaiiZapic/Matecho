import "core-js/modules/es.global-this";
import "core-js/modules/es.array.flat";
import "core-js/modules/es.array.flat-map";
import "core-js/modules/es.array.at";
import "core-js/modules/es.object.from-entries";
import "core-js/modules/es.object.has-own";
import "core-js/modules/es.string.replace-all";
import "core-js/modules/web.structured-clone";

if (!Element.prototype.toggleAttribute) {
  Element.prototype.toggleAttribute = function (name, force) {
    if (force !== void 0) force = !!force;

    if (this.hasAttribute(name)) {
      if (force) return true;

      this.removeAttribute(name);
      return false;
    }
    if (force === false) return false;

    this.setAttribute(name, "");
    return true;
  };
}

if (!HTMLSlotElement.prototype.assignedElements) {
  HTMLSlotElement.prototype.assignedElements = function (...args) {
    return HTMLSlotElement.prototype.assignedNodes
      .apply(this, args)
      .filter(n => n instanceof Element);
  };
}

if (!Element.prototype.getAnimations) {
  Element.prototype.getAnimations = function () {
    return [];
  };
}

void import("./main");
