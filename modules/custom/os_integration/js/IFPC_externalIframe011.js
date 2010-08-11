/**
* @author Jorge Reyes jreyes A@T myspace [dot] com 
*/

/*
* Extra Gadgest functionality
* */

var gadgets = gadgets || {};

gadgets.config = function () {
    var components = {};

    return {
        /**
        * Registers a configurable component and its configuration parameters.
        *
        * @param {String} component The name of the component to register. Should
        *     be the same as the fully qualified name of the <Require> feature or
        *     the fully qualified javascript object reference (e.g. gadgets.io).
        * @param {Object} opt_validators Mapping of option name to validation
        *     functions that take the form function(data) {return isValid(data);}
        * @param {Function} opt_callback A function to be invoked when a
        *     configuration is registered. If passed, this function will be invoked
        *     immediately after a call to init has been made. Do not assume that
        *     dependent libraries have been configured until after init is
        *     complete. If you rely on this, it is better to defer calling
        *     dependent libraries until you can be sure that configuration is
        *     complete. Takes the form function(config), where config will be
        *     all registered config data for all components. This allows your
        *     component to read configuration from other components.
        * @throws {Error} If the component has already been registered.
        */
        register: function (component, opt_validators, opt_callback) {
            if (components[component]) {
                throw new Error('Component "' + component + '" is already registered.');
            }
            components[component] = {
                validators: opt_validators || {},
                callback: opt_callback
            };
        },

        /**
        * Retrieves configuration data on demand.
        *
        * @param {String} opt_component The component to fetch. If not provided
        *     all configuration will be returned.
        * @return {Object} The requested configuration.
        * @throws {Error} If the given component has not been registered
        */
        get: function (opt_component) {
            if (opt_component) {
                if (!components[opt_component]) {
                    throw new Error('Component "' + opt_component + '" not registered.');
                }
                return configuration[opt_component] || {};
            }
            return configuration;
        },

        /**
        * Initializes the configuration.
        *
        * @param {Object} config The full set of configuration data.
        * @param {Boolean} opt_noValidation True if you want to skip validation.
        * @throws {Error} If there is a configuration error.
        */
        init: function (config, opt_noValidation) {
            configuration = config;
            for (var name in components) {
                var component = components[name],
            conf = config[name],
            validators = component.validators;
                if (!opt_noValidation) {
                    for (var validator in validators) {
                        if (!validators[validator](conf[validator])) {
                            throw new Error('Invalid config value "' + conf[validator] +
                  '" for parameter "' + validator + '" in component "' +
                  name + '"');
                        }
                    }
                }
                if (component.callback) {
                    component.callback(config);
                }
            }
        },

        // Standard validators go here.

        /**
        * Ensures that data is one of a fixed set of items.
        * @param {Array.<String>} list The list of valid values.
        * Also supports argument sytax: EnumValidator("Dog", "Cat", "Fish");
        */
        EnumValidator: function (list) {
            var listItems = [];
            if (arguments.length > 1) {
                for (var i = 0, arg; arg = arguments[i]; ++i) {
                    listItems.push(arg);
                }
            } else {
                listItems = list;
            }
            return function (data) {
                for (var i = 0, test; test = listItems[i]; ++i) {
                    if (data === listItems[i]) {
                        return true;
                    }
                }
            };
            return false;
        },

        /**
        * Tests the value against a regular expression.
        */
        RegExValidator: function (re) {
            return function (data) {
                return re.test(data);
            }
        },

        /**
        * Validates that a value was provided.
        */
        ExistsValidator: function (data) {
            return typeof data !== "undefined";
        },

        /**
        * Validates that a value is a non-empty string.
        */
        NonEmptyStringValidator: function (data) {
            return typeof data === "string" && data.length > 0
        },

        /**
        * Validates that the value is a boolean.
        */
        BooleanValidator: function (data) {
            return !!data;
        },

        /**
        * Similar to the ECMAScript4 virtual typing system, ensures that
        * whatever object was passed in is "like" the existing object.
        * Doesn't actually do type validation though, but instead relies
        * on other validators.
        *
        * example:
        *
        *  var validator = new gadgets.config.LikeValidator(
        *    "booleanField" : gadgets.config.BooleanValidator,
        *    "regexField" : new gadgets.config.RegExValidator(/foo.+/);
        *  );
        *
        * This can be used recursively as well to validate sub-objects.
        *
        * @param {Object} test The object to test against.
        */
        LikeValidator: function (test) {
            return function (data) {
                for (var member in test) {
                    var t = test[member];
                    if (!t(data[member])) {
                        return false;
                    }
                }
                return true;
            };
        }
    };
} ();


/**
* @static
* @class Provides general-purpose utility functions.
* @name gadgets.util
*/

gadgets.util = function () {
    /**
    * Parses URL parameters into an object.
    * @return {Array.&lt;String&gt;} The parameters
    */
    function parseUrlParams() {
        // Get settings from url, 'hash' takes precedence over 'search' component
        // don't use document.location.hash due to browser differences.
        var query;
        var l = document.location.href;
        var queryIdx = l.indexOf("?");
        var hashIdx = l.indexOf("#");
        if (hashIdx === -1) {
            query = l.substr(queryIdx + 1);
        } else {
            // essentially replaces "#" with "&"
            query = [l.substr(queryIdx + 1, hashIdx - queryIdx - 1), "&",
               l.substr(hashIdx + 1)].join("");
        }
        return query.split("&");
    }

    var parameters = null;
    var features = {};
    var onLoadHandlers = [];

    // Maps code points to the value to replace them with.
    // If the value is "false", the character is removed entirely, otherwise
    // it will be replaced with an html entity.
    var escapeCodePoints = {
        // nul; most browsers truncate because they use c strings under the covers.
        0: false,
        // new line
        10: true,
        // carriage return
        13: true,
        // double quote
        34: true,
        // single quote
        39: true,
        // less than
        60: true,
        // greater than
        62: true,
        // Backslash
        92: true,
        // line separator
        8232: true,
        // paragraph separator
        8233: true
    };

    /**
    * Regular expression callback that returns strings from unicode code points.
    *
    * @param {Array} match Ignored
    * @param {String} value The codepoint value to convert
    * @return {String} The character corresponding to value.
    */
    function unescapeEntity(match, value) {
        return String.fromCharCode(value);
    }

    /**
    * Initializes feature parameters.
    */
    function init(config) {
        features = config["core.util"] || {};
    }
    if (gadgets.config) {
        gadgets.config.register("core.util", null, init);
    }

    return /** @scope gadgets.util */{

    /**
    * Gets the URL parameters.
    *
    * @return {Object} Parameters passed into the query string
    * @member gadgets.util
    * @private Implementation detail.
    */
    getUrlParameters: function () {
        if (parameters !== null) {
            return parameters;
        }
        parameters = {};
        var pairs = parseUrlParams();
        var unesc = window.decodeURIComponent ? decodeURIComponent : unescape;
        for (var i = 0, j = pairs.length; i < j; ++i) {
            var pos = pairs[i].indexOf('=');
            if (pos === -1) {
                continue;
            }
            var argName = pairs[i].substring(0, pos);
            var value = pairs[i].substring(pos + 1);
            // difference to IG_Prefs, is that args doesn't replace spaces in
            // argname. Unclear on if it should do:
            // argname = argname.replace(/\+/g, " ");
            value = value.replace(/\+/g, " ");
            parameters[argName] = unesc(value);
        }
        return parameters;
    },

    /**
    * Creates a closure that is suitable for passing as a callback.
    * Any number of arguments
    * may be passed to the callback;
    * they will be received in the order they are passed in.
    *
    * @param {Object} scope The execution scope; may be null if there is no
    *     need to associate a specific instance of an object with this
    *     callback
    * @param {Function} callback The callback to invoke when this is run;
    *     any arguments passed in will be passed after your initial arguments
    * @param {Object} var_args Initial arguments to be passed to the callback
    *
    * @member gadgets.util
    * @private Implementation detail.
    */
    makeClosure: function (scope, callback, var_args) {
        // arguments isn't a real array, so we copy it into one.
        var baseArgs = [];
        for (var i = 2, j = arguments.length; i < j; ++i) {
            baseArgs.push(arguments[i]);
        }
        return function () {
            // append new arguments.
            var tmpArgs = baseArgs.slice();
            for (var i = 0, j = arguments.length; i < j; ++i) {
                tmpArgs.push(arguments[i]);
            }
            return callback.apply(scope, tmpArgs);
        };
    },

    /**
    * Utility function for generating an "enum" from an array.
    *
    * @param {Array.<String>} values The values to generate.
    * @return {Map&lt;String,String&gt;} An object with member fields to handle
    *   the enum.
    *
    * @private Implementation detail.
    */
    makeEnum: function (values) {
        var obj = {};
        for (var i = 0, v; v = values[i]; ++i) {
            obj[v] = v;
        }
        return obj;
    },

    /**
    * Gets the feature parameters.
    *
    * @param {String} feature The feature to get parameters for
    * @return {Object} The parameters for the given feature, or null
    *
    * @member gadgets.util
    */
    getFeatureParameters: function (feature) {
        return typeof features[feature] === "undefined"
          ? null : features[feature];
    },

    /**
    * Returns whether the current feature is supported.
    *
    * @param {String} feature The feature to test for
    * @return {Boolean} True if the feature is supported
    *
    * @member gadgets.util
    */
    hasFeature: function (feature) {
        return typeof features[feature] !== "undefined";
    },

    /**
    * Registers an onload handler.
    * @param {Function} callback The handler to run
    *
    * @member gadgets.util
    */
    registerOnLoadHandler: function (callback) {
        onLoadHandlers.push(callback);
    },

    /**
    * Runs all functions registered via registerOnLoadHandler.
    * @private Only to be used by the container, not gadgets.
    */
    runOnLoadHandlers: function () {
        for (var i = 0, j = onLoadHandlers.length; i < j; ++i) {
            onLoadHandlers[i]();
        }
    },

    /**
    * Escapes the input using html entities to make it safer.
    *
    * If the input is a string, uses gadgets.util.escapeString.
    * If it is an array, calls escape on each of the array elements
    * if it is an object, will only escape all the mapped keys and values if
    * the opt_escapeObjects flag is set. This operation involves creating an
    * entirely new object so only set the flag when the input is a simple
    * string to string map.
    * Otherwise, does not attempt to modify the input.
    *
    * @param {Object} input The object to escape
    * @param {Boolean} opt_escapeObjects Whether to escape objects.
    * @return {Object} The escaped object
    * @private Only to be used by the container, not gadgets.
    */
    escape: function (input, opt_escapeObjects) {
        if (!input) {
            return input;
        } else if (typeof input === "string") {
            return gadgets.util.escapeString(input);
        } else if (typeof input === "array" || typeof (input.constructor) === Array || input instanceof Array) {
            for (var i = 0, j = input.length; i < j; ++i) {
                input[i] = gadgets.util.escape(input[i]);
            }
        } else if (typeof input === "object" && opt_escapeObjects) {
            var newObject = {};
            for (var field in input) if (input.hasOwnProperty(field)) {
                newObject[gadgets.util.escapeString(field)]
              = gadgets.util.escape(input[field], true);
            }
            return newObject;
        }
        return input;
    },

    /**
    * Escapes the input using html entities to make it safer.
    *
    * Currently not in the spec -- future proposals may change
    * how this is handled.
    *
    * TODO: Parsing the string would probably be more accurate and faster than
    * a bunch of regular expressions.
    *
    * @param {String} str The string to escape
    * @return {String} The escaped string
    */
    escapeString: function (str) {
        var out = [], ch, shouldEscape;
        for (var i = 0, j = str.length; i < j; ++i) {
            ch = str.charCodeAt(i);
            shouldEscape = escapeCodePoints[ch];
            if (shouldEscape === true) {
                out.push("&#", ch, ";");
            } else if (shouldEscape !== false) {
                // undefined or null are OK.
                out.push(str.charAt(i));
            }
        }
        return out.join("");
    },

    /**
    * Reverses escapeString
    *
    * @param {String} str The string to unescape.
    */
    unescapeString: function (str) {
        return str.replace(/&#([0-9]+);/g, unescapeEntity);
    },

    sanitizeHTML: function (text) {
        //---------------------------------------------------------------
        // Whitelists of HTML elements and attributes.
        //---------------------------------------------------------------

        /** @namespace */
        var html4 = {};

        /**
        * HTML element flags.
        * @enum {number}
        */
        html4.eflags = {
            OPTIONAL_ENDTAG: 1,
            BREAKS_FLOW: 2,
            EMPTY: 4,
            NAVIGATES: 8,
            CDATA: 0x10,
            RCDATA: 0x20,
            UNSAFE: 0x40
        };

        /**
        * HTML attribute flags.
        * @enum {number}
        */
        html4.atype = {
            SCRIPT: 1,
            STYLE: 2,
            IDREF: 3,
            NAME: 4,
            NMTOKENS: 5,
            URI: 6,
            FRAME: 7
        };

        /**
        * Maps HTML4 element names to flag bitsets.
        * Since this is a whitelist, be sure to do
        * {@code html4.ELEMENTS.hasOwnProperty} to determine whether or not an element
        * is allowed.
        */
        html4.ELEMENTS = {
            'a': html4.eflags.NAVIGATES,
            'abbr': 0,
            'acronym': 0,
            'address': 0,
            'applet': html4.eflags.UNSAFE,
            'area': html4.eflags.EMPTY | html4.eflags.NAVIGATES,
            'b': 0,
            // Changes the meaning of URIs
            'base': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            // Affects global styles.
            'basefont': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            'bdo': 0,
            'big': 0,
            'blockquote': html4.eflags.BREAKS_FLOW,
            // Attributes merged into global body.
            'body': html4.eflags.UNSAFE | html4.eflags.OPTIONAL_ENDTAG,
            'br': html4.eflags.EMPTY | html4.eflags.BREAKS_FLOW,
            'button': 0,
            'caption': 0,
            'center': html4.eflags.BREAKS_FLOW,
            'cite': 0,
            'code': 0,
            'col': html4.eflags.EMPTY,
            'colgroup': html4.eflags.OPTIONAL_ENDTAG,
            'dd': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'del': 0,
            'dfn': 0,
            'dir': html4.eflags.BREAKS_FLOW,
            'div': html4.eflags.BREAKS_FLOW,
            'dl': html4.eflags.BREAKS_FLOW,
            'dt': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'em': 0,
            'fieldset': 0,
            'font': 0,
            'form': html4.eflags.BREAKS_FLOW | html4.eflags.NAVIGATES,
            'frame': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            // Attributes merged into global frameset.
            'frameset': html4.eflags.UNSAFE,
            'h1': html4.eflags.BREAKS_FLOW,
            'h2': html4.eflags.BREAKS_FLOW,
            'h3': html4.eflags.BREAKS_FLOW,
            'h4': html4.eflags.BREAKS_FLOW,
            'h5': html4.eflags.BREAKS_FLOW,
            'h6': html4.eflags.BREAKS_FLOW,
            'head': (html4.eflags.UNSAFE | html4.eflags.OPTIONAL_ENDTAG
                        | html4.eflags.BREAKS_FLOW),
            'hr': html4.eflags.EMPTY | html4.eflags.BREAKS_FLOW,
            'html': (html4.eflags.UNSAFE | html4.eflags.OPTIONAL_ENDTAG
                        | html4.eflags.BREAKS_FLOW),
            'i': 0,
            'iframe': html4.eflags.UNSAFE,
            'img': html4.eflags.EMPTY,
            'input': html4.eflags.EMPTY,
            'ins': 0,
            'isindex': (html4.eflags.UNSAFE | html4.eflags.EMPTY
                        | html4.eflags.BREAKS_FLOW | html4.eflags.NAVIGATES),
            'kbd': 0,
            'label': 0,
            'legend': 0,
            'li': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            // Can load global styles.
            'link': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            'map': 0,
            'menu': html4.eflags.BREAKS_FLOW,
            // Can override document headers and encoding, or cause navigation.
            'meta': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            // Ambiguous tokenization.  Content is CDATA/PCDATA depending on browser.
            'noframes': html4.eflags.UNSAFE | html4.eflags.BREAKS_FLOW,
            // Ambiguous tokenization.  Content is CDATA/PCDATA depending on browser.
            'noscript': html4.eflags.UNSAFE,
            'object': html4.eflags.UNSAFE,
            'ol': html4.eflags.BREAKS_FLOW,
            'optgroup': 0,
            'option': html4.eflags.OPTIONAL_ENDTAG,
            'p': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'param': html4.eflags.UNSAFE | html4.eflags.EMPTY,
            'plaintext': (html4.eflags.OPTIONAL_ENDTAG | html4.eflags.UNSAFE
                        | html4.eflags.CDATA),
            'pre': html4.eflags.BREAKS_FLOW,
            'q': 0,
            's': 0,
            'samp': 0,
            'script': html4.eflags.UNSAFE | html4.eflags.CDATA,
            'select': 0,
            'small': 0,
            'span': 0,
            'strike': 0,
            'strong': 0,
            'style': html4.eflags.UNSAFE | html4.eflags.CDATA,
            'sub': 0,
            'sup': 0,
            'table': html4.eflags.BREAKS_FLOW,
            'tbody': html4.eflags.OPTIONAL_ENDTAG,
            'td': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'textarea': html4.eflags.RCDATA,
            'tfoot': html4.eflags.OPTIONAL_ENDTAG,
            'th': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'thead': html4.eflags.OPTIONAL_ENDTAG,
            'title': (html4.eflags.UNSAFE | html4.eflags.BREAKS_FLOW
                        | html4.eflags.RCDATA),
            'tr': html4.eflags.OPTIONAL_ENDTAG | html4.eflags.BREAKS_FLOW,
            'tt': 0,
            'u': 0,
            'ul': html4.eflags.BREAKS_FLOW,
            'var': 0,
            'xmp': html4.eflags.CDATA
        };

        /**
        * Maps HTML4 attribute names to flag bitsets.
        */
        html4.ATTRIBS = {
            'abbr': 0,
            'accept': 0,
            'accept-charset': 0,
            'accesskey': 0,
            'action': html4.atype.URI,
            'align': 0,
            'alink': 0,
            'alt': 0,
            'archive': html4.atype.URI,
            'axis': 0,
            'background': html4.atype.URI,
            'bgcolor': 0,
            'border': 0,
            'cellpadding': 0,
            'cellspacing': 0,
            'char': 0,
            'charoff': 0,
            'charset': 0,
            'checked': 0,
            'cite': html4.atype.URI,
            'class': html4.atype.NMTOKENS,
            'classid': html4.atype.URI,
            'clear': 0,
            'code': 0,
            'codebase': html4.atype.URI,
            'codetype': 0,
            'color': 0,
            'cols': 0,
            'colspan': 0,
            'compact': 0,
            'content': 0,
            'coords': 0,
            'data': html4.atype.URI,
            'datetime': 0,
            'declare': 0,
            'defer': 0,
            'dir': 0,
            'disabled': 0,
            'enctype': 0,
            'face': 0,
            'for': html4.atype.IDREF,
            'frame': 0,
            'frameborder': 0,
            'headers': 0,
            'height': 0,
            'href': html4.atype.URI,
            'hreflang': 0,
            'hspace': 0,
            //'http-equiv'  : 0,   // unsafe
            'id': html4.atype.IDREF,
            'ismap': 0,
            'label': 0,
            'lang': 0,
            'language': 0,
            'link': 0,
            'longdesc': html4.atype.URI,
            'marginheight': 0,
            'marginwidth': 0,
            'maxlength': 0,
            'media': 0,
            'method': 0,
            'multiple': 0,
            'name': html4.atype.NAME,
            'nohref': 0,
            'noresize': 0,
            'noshade': 0,
            'nowrap': 0,
            'object': 0,
            'onblur': html4.atype.SCRIPT,
            'onchange': html4.atype.SCRIPT,
            'onclick': html4.atype.SCRIPT,
            'ondblclick': html4.atype.SCRIPT,
            'onfocus': html4.atype.SCRIPT,
            'onkeydown': html4.atype.SCRIPT,
            'onkeypress': html4.atype.SCRIPT,
            'onkeyup': html4.atype.SCRIPT,
            'onload': html4.atype.SCRIPT,
            'onmousedown': html4.atype.SCRIPT,
            'onmousemove': html4.atype.SCRIPT,
            'onmouseout': html4.atype.SCRIPT,
            'onmouseover': html4.atype.SCRIPT,
            'onmouseup': html4.atype.SCRIPT,
            'onreset': html4.atype.SCRIPT,
            'onselect': html4.atype.SCRIPT,
            'onsubmit': html4.atype.SCRIPT,
            'onunload': html4.atype.SCRIPT,
            'profile': html4.atype.URI,
            'prompt': 0,
            'readonly': 0,
            'rel': 0,
            'rev': 0,
            'rows': 0,
            'rowspan': 0,
            'rules': 0,
            'scheme': 0,
            'scope': 0,
            'scrolling': 0,
            'selected': 0,
            'shape': 0,
            'size': 0,
            'span': 0,
            'src': html4.atype.URI,
            'standby': 0,
            'start': 0,
            'style': html4.atype.STYLE,
            'summary': 0,
            'tabindex': 0,
            'target': html4.atype.FRAME,
            'text': 0,
            'title': 0,
            'type': 0,
            'usemap': html4.atype.URI,
            'valign': 0,
            'value': 0,
            'valuetype': 0,
            'version': 0,
            'vlink': 0,
            'vspace': 0,
            'width': 0
        };


        //-----------------------------------------------------------
        // Provides a factory that allows transformations on HTML.
        //-----------------------------------------------------------

        /** @namespace */
        var html = (function () {

            var ENTITIES = {
                LT: '<',
                GT: '>',
                AMP: '&',
                NBSP: '\240',
                QUOT: '"',
                APOS: '\''
            };

            var decimalEscapeRe = /^#(\d)$/;
            var hexEscapeRe = /^#x([0-9A-F])$/;
            function lookupEntity(name) {
                name = name.toUpperCase();  // TODO: &pi; is different from &Pi;
                if (ENTITIES.hasOwnProperty(name)) { return ENTITIES[name]; }
                var m = name.match(decimalEscapeRe);
                if (m) {
                    return String.fromCharCode(parseInt(m[1], 10));
                } else if (!!(m = name.match(hexEscapeRe))) {
                    return String.fromCharCode(parseInt(m[1], 16));
                }
                return '';
            }

            function decodeOneEntity(_, name) {
                return lookupEntity(name);
            }

            var entityRe = /&(#\d+|#x[\da-f]+|\w+);/g;
            function unescapeEntities(s) {
                return s.replace(entityRe, decodeOneEntity);
            }

            var ampRe = /&/g;
            var looseAmpRe = /&([^a-z#]|#(?:[^0-9x]|x(?:[^0-9a-f]|$)|$)|$)/gi;
            var ltRe = /</g;
            var gtRe = />/g;
            var quotRe = /\"/g;

            function escapeAttrib(s) {
                return s.replace(ampRe, '&amp;').replace(ltRe, '&lt;').replace(gtRe, '&gt;')
                .replace(quotRe, '&quot;');
            }

            /**
            * Escape entities in RCDATA that can be escaped without changing the meaning.
            */
            function normalizeRCData(rcdata) {
                return rcdata
                .replace(looseAmpRe, '&amp;$1')
                .replace(ltRe, '&lt;')
                .replace(gtRe, '&gt;');
            }


            /** token definitions. */
            var INSIDE_TAG_TOKEN = new RegExp(
            // Don't capture space.
              '^\\s*(?:'
            // Capture an attribute name in group 1, and value in groups 2-4.
              + ('(?:'
                 + '([a-z][a-z-]*)'
                 + ('(?:'
                    + '\\s*=\\s*'
                    + ('(?:'
                       + '\"([^\"]*)\"'
                       + '|\'([^\']*)\''
                       + '|([^>\"\'\\s]*)'
                       + ')'
                       )
                    + ')'
                    ) + '?'
                 + ')'
                 )
            // End of tag captured in group 5.
              + '|(/?>)'
            // Don't capture cruft
              + '|[^\\w\\s>]+)',
              'i');

            var OUTSIDE_TAG_TOKEN = new RegExp(
              '^(?:'
            // Entity captured in group 1.
              + '&(\\#[0-9]+|\\#[x][0-9a-f]+|\\w+);'
            // Comment, doctypes, and processing instructions not captured.
              + '|<!--[\\s\\S]*?-->|<!\w[^>]*>|<\\?[^>*]*>'
            // '/' captured in group 2 for close tags, and name captured in group 3.
              + '|<(/)?([a-z][a-z0-9]*)'
            // Text captured in group 4.
              + '|([^<&]+)'
            // Cruft captured in group 5.
              + '|([<&]))',
              'i');

            /**
            * Given a SAX-like event handler, produce a function that feeds those
            * events and a parameter to the event handler.
            *
            * The event handler has the form:<pre>
            * {
            *   // Name is an upper-case HTML tag name.  Attribs is an array of
            *   // alternating upper-case attribute names, and attribute values.  The
            *   // attribs array is reused by the parser.  Param is the value passed to
            *   // the saxParser.
            *   startTag: function (name, attribs, param) { ... },
            *   endTag:   function (name, param) { ... },
            *   pcdata:   function (text, param) { ... },
            *   rcdata:   function (text, param) { ... },
            *   cdata:    function (text, param) { ... },
            *   startDoc: function (param) { ... },
            *   endDod:   function (param) { ... },
            * }</pre>
            *
            * @param {Object} event handler.
            * @return {Function} that takes a chunk of html and a parameter.
            *   The parameter is passed on to the handler methods.
            */
            function makeSaxParser(handler) {
                return function parse(htmlText, param) {
                    htmlText = String(htmlText);
                    var htmlUpper = null;

                    var inTag = false;  // True iff we're currently processing a tag.
                    var attribs = [];  // Accumulates attribute names and values.
                    var tagName;  // The name of the tag currently being processed.
                    var eflags;  // The element flags for the current tag.
                    var openTag;  // True if the current tag is an open tag.

                    handler.startDoc && handler.startDoc(param);

                    while (htmlText) {
                        var m = htmlText.match(inTag ? INSIDE_TAG_TOKEN : OUTSIDE_TAG_TOKEN);
                        htmlText = htmlText.substring(m[0].length);

                        if (inTag) {
                            if (m[1]) { // attribute
                                // setAttribute with uppercase names doesn't work on IE6.
                                var attribName = m[1].toLowerCase();
                                var encodedValue = m[2] || m[3] || m[4];
                                var decodedValue;
                                if (encodedValue != null) {  // Matches null & undefined
                                    decodedValue = unescapeEntities(encodedValue);
                                } else {
                                    // Use name as value for valueless attribs, so
                                    //   <input type=checkbox checked>
                                    // gets attributes ['type', 'checkbox', 'checked', 'checked']
                                    decodedValue = attribName;
                                }
                                attribs.push(attribName, decodedValue);
                            } else if (m[5]) {
                                if (eflags !== undefined) {  // False if not in whitelist.
                                    if (openTag) {
                                        handler.startTag && handler.startTag(tagName, attribs, param);
                                    } else {
                                        handler.endTag && handler.endTag(tagName, param);
                                    }
                                }

                                if (openTag
                        && (eflags & (html4.eflags.CDATA | html4.eflags.RCDATA))) {
                                    if (htmlUpper === null) {
                                        htmlUpper = htmlText.toLowerCase();
                                    } else {
                                        htmlUpper = htmlUpper.substring(
                            htmlUpper.length - htmlText.length);
                                    }
                                    var dataEnd = htmlUpper.indexOf('</' + tagName);
                                    if (dataEnd < 0) { dataEnd = htmlText.length; }
                                    if (eflags & html4.eflags.CDATA) {
                                        handler.cdata
                            && handler.cdata(htmlText.substring(0, dataEnd), param);
                                    } else if (handler.rcdata) {
                                        handler.rcdata(
                            normalizeRCData(htmlText.substring(0, dataEnd)), param);
                                    }
                                    htmlText = htmlText.substring(dataEnd);
                                }

                                tagName = eflags = openTag = undefined;
                                attribs.length = 0;
                                inTag = false;
                            }
                        } else {
                            if (m[1]) {  // Entity
                                handler.pcdata && handler.pcdata(m[0], param);
                            } else if (m[3]) {  // Tag
                                openTag = !m[2];
                                inTag = true;
                                tagName = m[3].toLowerCase();
                                eflags = html4.ELEMENTS.hasOwnProperty(tagName)
                        ? html4.ELEMENTS[tagName] : undefined;
                            } else if (m[4]) {  // Text
                                handler.pcdata && handler.pcdata(m[4], param);
                            } else if (m[5]) {  // Cruft
                                handler.pcdata
                        && handler.pcdata(m[5] === '&' ? '&amp;' : '&lt;', param);
                            }
                        }
                    }

                    handler.endDoc && handler.endDoc(param);
                };
            }

            return {
                normalizeRCData: normalizeRCData,
                escapeAttrib: escapeAttrib,
                unescapeEntities: unescapeEntities,
                makeSaxParser: makeSaxParser
            };
        })();

        /**
        * Returns a function that strips unsafe tags and attributes from html.
        * @param {Function} sanitizeAttributes
        *     from tagName, attribs[]) to null or a sanitized attribute array.
        *     The attribs array can be arbitrarily modified, but the same array
        *     instance is reused, so should not be held.
        * @return {Function} from html to sanitized html
        */
        html.makeHtmlSanitizer = function (sanitizeAttributes) {
            var stack = [];
            var ignoring = false;
            return html.makeSaxParser({
                startDoc: function (_) {
                    stack = [];
                    ignoring = false;
                },
                startTag: function (tagName, attribs, out) {
                    if (ignoring) { return; }
                    if (!html4.ELEMENTS.hasOwnProperty(tagName)) { return; }
                    var eflags = html4.ELEMENTS[tagName];
                    if (eflags & html4.eflags.UNSAFE) {
                        ignoring = !(eflags & html4.eflags.EMPTY);
                        return;
                    }
                    attribs = sanitizeAttributes(tagName, attribs);
                    if (attribs) {
                        if (!(eflags & html4.eflags.EMPTY)) {
                            stack.push(tagName);
                        }

                        out.push('<', tagName);
                        for (var i = 0, n = attribs.length; i < n; i += 2) {
                            var attribName = attribs[i],
                          value = attribs[i + 1];
                            if (value != null) {  // Skip null or undefined
                                out.push(' ', attribName, '="', html.escapeAttrib(value), '"');
                            }
                        }
                        out.push('>');
                    }
                },
                endTag: function (tagName, out) {
                    if (ignoring) {
                        ignoring = false;
                        return;
                    }
                    if (!html4.ELEMENTS.hasOwnProperty(tagName)) { return; }
                    var eflags = html4.ELEMENTS[tagName];
                    if (!(eflags & (html4.eflags.UNSAFE | html4.eflags.EMPTY))) {
                        var index;
                        if (eflags & html4.eflags.OPTIONAL_ENDTAG) {
                            for (index = stack.length; --index >= 0; ) {
                                var stackEl = stack[index];
                                if (stackEl === tagName) { break; }
                                if (!(html4.ELEMENTS[stackEl] & html4.eflags.OPTIONAL_ENDTAG)) {
                                    // Don't pop non optional end tags looking for a match.
                                    return;
                                }
                            }
                        } else {
                            for (index = stack.length; --index >= 0; ) {
                                if (stack[index] === tagName) { break; }
                            }
                        }
                        if (index < 0) { return; }  // Not opened.
                        for (var i = stack.length; --i > index; ) {
                            var stackEl = stack[i];
                            if (!(html4.ELEMENTS[stackEl] & html4.eflags.OPTIONAL_ENDTAG)) {
                                out.push('</', stackEl, '>');
                            }
                        }
                        stack.length = index;
                        out.push('</', tagName, '>');
                    }
                },
                pcdata: function (text, out) {
                    if (!ignoring) { out.push(text); }
                },
                rcdata: function (text, out) {
                    if (!ignoring) { out.push(text); }
                },
                cdata: function (text, out) {
                    if (!ignoring) { out.push(text); }
                },
                endDoc: function (out) {
                    for (var i = stack.length; --i >= 0; ) {
                        out.push('</', stack[i], '>');
                    }
                    stack.length = 0;
                }
            });
        };

        /**
        * Strips unsafe tags and attributes from html.
        * @param {string} html to sanitize
        * @return {string} html
        */
        function html_sanitize(htmlText) {
            var out = [];
            html.makeHtmlSanitizer(
              function sanitizeAttribs(tagName, attribs) {
                  for (var i = 0; i < attribs.length; i += 2) {
                      var attribName = attribs[i];
                      var value = attribs[i + 1];
                      if (html4.ATTRIBS.hasOwnProperty(attribName)) {
                          switch (html4.ATTRIBS[attribName]) {
                              case html4.atype.SCRIPT:
                              case html4.atype.STYLE:
                                  value = null;
                                  break;
                          }
                      } else {
                          value = null;
                      }
                      attribs[i + 1] = value;
                  }
                  return attribs;
              })(htmlText, out);
            return out.join('');
        }

        return html_sanitize(text);
    }
};
} ();

gadgets.views = function () {

    /**
    * Reference to the current view object.
    */
    var currentView = null;

    /**
    * Map of all supported views for this container.
    */
    var supportedViews = {};

    /**
    * Map of parameters passed to the current request.
    */
    var params = {};

    /**
    * Initializes views. Assumes that the current view is the "view"
    * url parameter (or default if "view" isn't supported), and that
    * all view parameters are in the form view-<name>
    * TODO: Use unified configuration when it becomes available.
    *
    */
    function init(config) {
        var supported = config["views"];

        var x = 0;
        for (var s in supported) if (supported.hasOwnProperty(s)) {
            var obj = supported[s];
            supportedViews[s] = new gadgets.views.View(obj.name_, obj.isOnlyVisible_);
            //HACK BELOW
            supportedViews[x] = supportedViews[s]; x++; //HACK for back compat to 0.6 container
            //REMOVE ABOVE LINE AT SOME POINT
            var aliases = obj.aliases || [];
            for (var i = 0, alias; alias = aliases[i]; ++i) {
                supportedViews[alias] = new gadgets.views.View(obj.name_, obj.isOnlyVisible_);
            }
        }
        var urlParams = gadgets.util.getUrlParameters();
        if (urlParams["p"]) {
            var tmpParams = gadgets.json.parse(
				decodeURIComponent(urlParams["p"]));
            if (tmpParams) {
                params = tmpParams;
                for (var p in params) if (params.hasOwnProperty(p)) {
                    if (null !== params[p] && "undefined" !== typeof (params[p])) {
                        params[p] = gadgets.util.escapeString(params[p]);
                    }
                }
            }
        }

        currentView = supportedViews[urlParams.views] || supportedViews["default"];
    }

    var requiredConfig = {
        "default": new gadgets.config.LikeValidator({
            "isOnlyVisible_": gadgets.config.BooleanValidator
        })
    };

    gadgets.config.register("views", requiredConfig, init);

    return {
        requestNavigateTo: function (view, opt_params) {
            gadgets.rpc.call(
          null, "requestNavigateTo", null, view.getName(), opt_params);
        },

        getCurrentView: function () {
            return currentView;
        },

        getSupportedViews: function () {
            return supportedViews;
        },

        getParams: function () {
            return params;
        }
    };
} ();

gadgets.views.View = function (name, opt_isOnlyVisible) {
    this.name_ = name;
    this.isOnlyVisible_ = !!opt_isOnlyVisible;
};

gadgets.views.View.prototype.getName = function () {
    return this.name_;
};

gadgets.views.View.prototype.isOnlyVisibleGadget = function () {
    return this.isOnlyVisible_;
};

gadgets.views.ViewType = gadgets.util.makeEnum([
  "FULL_PAGE", "DASHBOARD", "POPUP"
]);

/**
* @static
* @class Provides operations for getting information about and modifying the
*     window the gadget is placed in.
* @name gadgets.window
*/
gadgets.window = gadgets.window || {};

// we wrap these in an anonymous function to avoid storing private data
// as members of gadgets.window.
(function () {

    var oldHeight;

    /**
    * Detects the inner dimensions of a frame.
    * See: http://www.quirksmode.org/viewport/compatibility.html for more
    * information.
    * @returns {Object} An object with width and height properties.
    * @member gadgets.window
    */
    gadgets.window.getViewportDimensions = function () {
        var x, y;
        if (self.innerHeight) {
            // all except Explorer
            x = self.innerWidth;
            y = self.innerHeight;
        } else if (document.documentElement &&
               document.documentElement.clientHeight) {
            // Explorer 6 Strict Mode
            x = document.documentElement.clientWidth;
            y = document.documentElement.clientHeight;
        } else if (document.body) {
            // other Explorers
            x = document.body.clientWidth;
            y = document.body.clientHeight;
        } else {
            x = 0;
            y = 0;
        }
        return { width: x, height: y };
    };

    /**
    * Adjusts the gadget height
    * @param {Number} opt_height An optional preferred height in pixels. If not
    *     specified, will attempt to fit the gadget to its content.
    * @member gadgets.window
    */
    gadgets.window.adjustHeight = function (opt_height) {
        var newHeight = parseInt(opt_height, 10);
        var percentage = false;
        if (isNaN(newHeight)) {
            var vh = gadgets.window.getViewportDimensions().height;
            var body = document.body;
            var docEl = document.documentElement;
            if (document.compatMode == 'CSS1Compat' && docEl.scrollHeight) {
                newHeight = docEl.scrollHeight != vh ? docEl.scrollHeight : docEl.offsetHeight;
            }
            else {
                var sh = docEl.scrollHeight;
                var oh = docEl.offsetHeight;

                if (docEl.clientHeight != oh) {
                    sh = body.scrollHeight;
                    oh = body.offsetHeight;
                }

                if (sh > vh) {
                    newHeight = sh > oh ? sh : oh;
                }
                else {
                    newHeight = sh < oh ? sh : oh;
                }

                if (newHeight === vh &&
                    window.navigator &&
                    window.navigator.userAgent &&
                    window.navigator.userAgent.toLowerCase().indexOf("safari") >= 0) { //for safari, quite ugly...
                    var newDiv = document.createElement("div");
                    newDiv.innerHTML = document.body.innerHTML;
                    newDiv.style.visibility = "hidden";
                    newDiv.id = "_temp_____div_for_____adjustHeight";
                    document.body.appendChild(newDiv);
                    newHeight = document.getElementById("_temp_____div_for_____adjustHeight").offsetHeight + 15;
                    newDiv.innerHTML = "";
                    document.body.removeChild(newDiv);
                }
            }
        }
        else if (0 === newHeight) {
            newHeight = parseFloat(opt_height);
            if (!isNaN(newHeight) && (newHeight <= 1 || newHeight > 0)) percentage = true;
        }

        if (newHeight != oldHeight || percentage) {
            oldHeight = newHeight;
            var p = opensocial.Container.get().params_;
            _IFPC.call(
            p.panelId,
            "resizeWidget",
            [p.panelId, newHeight],
            p.remoteRelay,
            null,
            p.localRelay,
            null);
        }
    };
} ());


if (typeof (MyOpenSpace) === "undefined") MyOpenSpace = {};

MyOpenSpace.Util = {};

MyOpenSpace.Util.isArray = function (arr) {
    if (null === arr || "undefined" === typeof (arr)) {
        return false;
    }
    if ("array" === typeof (arr) || Array === arr.constructor) {
        return true;
    }
    if ("object" === typeof (arr) && !isNaN(arr.length)) {
        return true;
    }
    return false;
};

/**
* @ignore
*/
MyOpenSpace.Util.parseIdPrefix = function (id, opt_type) {
    var results = ('' + id).match(/^(?:myspace\.com\.([a-zA-Z.]+)\.)?(-?\d+)$/);
    if (results === null || results.length === 0) {
        return null;
    }
    var type = results[1];
    if (typeof opt_type !== 'undefined' && (typeof type !== 'undefined' && type !== "")) {
        if (opt_type !== type) return null;
    }
    return results[2];
};

MyOpenSpace.Activity = function (params) {
    this.fields_ = params || {};
};

MyOpenSpace.Activity.Field = {
    TITLE_ID: 'titleId',
    TITLE: 'title',
    TEMPLATE_PARAMS: 'templateParams',
    URL: 'url',
    MEDIA_ITEMS: 'mediaItems',
    BODY_ID: 'bodyId',
    BODY: 'body',
    EXTERNAL_ID: 'externalId',
    STREAM_TITLE: 'streamTitle',
    STREAM_URL: 'streamUrl',
    STREAM_SOURCE_URL: 'streamSourceUrl',
    STREAM_FAVICON_URL: 'streamFaviconUrl',
    PRIORITY: 'priority',
    ID: 'id',
    USER_ID: 'userId',
    APP_ID: 'appId',
    POSTED_TIME: 'postedTime'
};

MyOpenSpace.Activity.prototype.getId = function () {
    return this.getField(MyOpenSpace.Activity.Field.ID);
};

MyOpenSpace.Activity.prototype.getField = function (key) {
    return this.fields_[key];
};

MyOpenSpace.Activity.prototype.setField = function (key, data) {
    return this.fields_[key] = data;
};

MyOpenSpace.CreateActivityPriority = {
    HIGH: 'HIGH',
    LOW: 'LOW'
};

MyOpenSpace.NavigationParameters = function (params) {
    this.fields_ = params || {};
};

MyOpenSpace.NavigationParameters.Field = {
    VIEW: 'view',
    OWNER: 'owner',
    PARAMETERS: 'parameters',
    DESTINATION_TYPE: 'destinationType'
};

MyOpenSpace.NavigationParameters.prototype.getField = function (key) {
    return this.fields_[key];
};

MyOpenSpace.NavigationParameters.prototype.setField = function (key, data) {
    return this.fields_[key] = data;
};

MyOpenSpace.NavigationParameters.DestinationType = {
    VIEWER_DESTINATION: "viewerDestination",
    RECIPIENT_DESTINATION: "recipientDestination"
};


MyOpenSpace.MediaItem = function (mimeType, url, opt_params) {
    this.fields_ = opt_params || {};
    this.fields_[MyOpenSpace.MediaItem.Field.MIME_TYPE] = mimeType;
    this.fields_[MyOpenSpace.MediaItem.Field.URL] = url;
};


MyOpenSpace.MediaItem.Type = {
    IMAGE: 'image',
    VIDEO: 'video',
    AUDIO: 'audio'
}

MyOpenSpace.MediaItem.Field = {
    TYPE: 'type',
    MIME_TYPE: 'mimeType',
    URL: 'url'
};

MyOpenSpace.MediaItem.prototype.getField = function (key) {
    return this.fields_[key];
};


MyOpenSpace.MediaItem.prototype.setField = function (key, data) {
    return this.fields_[key] = data;
};



MyOpenSpace.Permission = function () { };

/**
* The available permission types
* @class
* @name MyOpenSpace.Permission.Field
* @static
* @internal
*/
MyOpenSpace.Permission.Field = {
    DISPLAY_ON_PROFILE: "DisplayOnProfile",
    DISPLAY_ON_HOME: "DisplayOnHome",
    SEND_UPDATES_TO_FRIENDS: "SendUpdatesToFriends",
    SHOW_UPDATES_FROM_FRIENDS: "ShowUpdatesFromFriends",
    ACCESS_TO_PRIVATE_VIDEOS_PHOTOS: "AccessToPrivateVideosPhotos",
    ACCESS_TO_PUBLIC_VIDEOS_PHOTOS: "AccessToPublicVideosPhotos",
    ACCESS_TO_IDENTITY_INFORMATION: "AccessToIdentityInformation",
    ADD_PHOTOS_TO_ALBUMS: "AddPhotosAlbums",
    UPDATE_MOOD_STATUS: "UpdateMoodStatus",
    UPDATE_PROFILE: "UpdateProfile",
    CONTACT_INFO: "ViewContactInfo",
    FULL_PROFILE_INFO: "ViewFullProfileInfo",

    BASIC_COMMUNICATIONS: "BasicCommunications",
    ACCESS_TO_FRIEND_LIST: "AccessToFriendList",
    ACCESS_TO_BASIC_INFO: "BasicInfo"
};


/**
* The available permission settings that can be checked
* @class
* @name MyOpenSpace.Permission
* @static
*/
MyOpenSpace.Permission = {


    VIEWER_DISPLAY_ON_PROFILE: {
        permission: MyOpenSpace.Permission.Field.DISPLAY_ON_PROFILE,
        permissionIndicator: "DP"
    },

    VIEWER_DISPLAY_ON_HOME: {
        permission: MyOpenSpace.Permission.Field.DISPLAY_ON_HOME,
        permissionIndicator: "DH"
    },

    VIEWER_SEND_UPDATES_TO_FRIENDS: {
        permission: MyOpenSpace.Permission.Field.SEND_UPDATES_TO_FRIENDS,
        permissionIndicator: "UT"
    },

    VIEWER_SHOW_UPDATES_FROM_FRIENDS: {
        permission: MyOpenSpace.Permission.Field.SHOW_UPDATES_FROM_FRIENDS,
        permissionIndicator: "UF"
    },

    VIEWER_ACCESS_TO_PRIVATE_VIDEOS_PHOTOS: {
        permission: MyOpenSpace.Permission.Field.ACCESS_TO_PRIVATE_VIDEOS_PHOTOS,
        permissionIndicator: "PR"
    },

    VIEWER_ACCESS_TO_PUBLIC_VIDEOS_PHOTOS: {
        permission: MyOpenSpace.Permission.Field.ACCESS_TO_PUBLIC_VIDEOS_PHOTOS,
        permissionIndicator: "PB"
    },
    VIEWER_ACCESS_TO_IDENTITY_INFORMATION: {
        permission: MyOpenSpace.Permission.Field.ACCESS_TO_IDENTITY_INFORMATION,
        permissionIndicator: "AI"
    },
    VIEWER_ADD_PHOTOS_TO_ALBUMS: {
        permission: MyOpenSpace.Permission.Field.ADD_PHOTOS_TO_ALBUMS,
        permissionIndicator: "PA"
    },
    VIEWER_UPDATE_MOOD_STATUS: {
        permission: MyOpenSpace.Permission.Field.UPDATE_MOOD_STATUS,
        permissionIndicator: "UM"
    },
    VIEWER_UPDATE_PROFILE: {
        permission: MyOpenSpace.Permission.Field.UPDATE_PROFILE,
        permissionIndicator: "UP"
    },
    VIEWER_CONTACT_INFO: {
        permission: MyOpenSpace.Permission.Field.CONTACT_INFO,
        permissionIndicator: "VC"
    },
    VIEWER_FULL_PROFILE_INFO: {
        permission: MyOpenSpace.Permission.Field.FULL_PROFILE_INFO,
        permissionIndicator: "VF"
    },
    VIEWER_ACCESS_TO_BASIC_INFO: {
        permission: MyOpenSpace.Permission.Field.ACCESS_TO_BASIC_INFO,
        permissionIndicator: "BI"
    },
    VIEWER_ACCESS_TO_FRIEND_LIST: {
        permission: MyOpenSpace.Permission.Field.ACCESS_TO_FRIEND_LIST,
        permissionIndicator: "FL"
    },
    VIEWER_BASIC_COMMUNICATIONS: {
        permission: MyOpenSpace.Permission.Field.BASIC_COMMUNICATIONS,
        permissionIndicator: "BC"
    }
};

MyOpenSpace.View = {};

MyOpenSpace.View.Field = {
    CANVAS: "canvas",
    PROFILE_LEFT: "profile.left",
    PROFILE_RIGHT: "profile.right",
    PROFILE: "profile",
    HOME: "home",
    DEFAULT: "canvas"
};

MyOpenSpace.Message = function (body, opt_params) {
    this.fields_ = opt_params || {};
    this.fields_[MyOpenSpace.Message.Field.BODY] = body;
};

MyOpenSpace.Message.Field = {

    TYPE: 'type',
    TITLE: 'title',
    BODY: 'body',
    TITLE_ID: 'titleId',
    BODY_ID: 'bodyId'
};


MyOpenSpace.Message.prototype.getField = function (key) {
    return this.fields_[key];
};

MyOpenSpace.Message.prototype.setField = function (key, data) {
    return this.fields_[key] = data;
};


MyOpenSpace.PostTo = {};

MyOpenSpace.PostTo.Targets = {
    PROFILE: "PROFILE",
    SEND_MESSAGE: "SEND_MESSAGE",
    COMMENTS: "COMMENTS",
    BULLETINS: "BULLETINS",
    BLOG: "BLOG",
    SHARE_APP: "SHARE_APP",
    ACTIVITY: "ACTIVITY",
    ADD_TO_FRIENDS: "ADD_TO_FRIENDS"
};

MyOpenSpace.PostTo.Result = {
    ERROR: -1,
    CANCELLED: 0,
    SUCCESS: 1
};

MyOpenSpace.MySpaceContainer = function () {

    gadgets.util.getUrlParameters().views = gadgets.util.getUrlParameters().opensocial_surface; //HACK ALERT
    var config = {};
    var supported_views = {};
    supported_views["default"] = new gadgets.views.View(MyOpenSpace.View.Field.DEFAULT, true);
    supported_views[MyOpenSpace.View.Field.CANVAS] = new gadgets.views.View(MyOpenSpace.View.Field.CANVAS, true);

    config["views"] = supported_views;
    gadgets.config.init(config);

    this.osMode_ = gadgets.views.getCurrentView();
    var uriFragment = window.location.hash;
    if (uriFragment && uriFragment.length >= 0) {
        uriFragment = uriFragment.substring(1, uriFragment.length);
        if (uriFragment.indexOf("&") >= 0) {
            uriFragment = uriFragment.substring(0, uriFragment.indexOf("&"));
        }
    }

    this.params_ = {};

    var urlParams = gadgets.util.getUrlParameters();

    if (urlParams && urlParams.opensocial_token) {
        this.params_["osToken"] = urlParams.opensocial_token;
    }

    if (urlParams && urlParams.opensocial_owner_id) {
        this.params_["ownerid"] = urlParams.opensocial_owner_id;
    }

    if (urlParams.opensocial_viewer_id) {
        this.params_["viewerId"] = urlParams.opensocial_viewer_id;
    }


    if (urlParams && urlParams.appid) {
        this.params_["appid"] = urlParams.appid;
    }

    if (urlParams && urlParams.ptoString) {
        this.params_["supportedPostToTargets"] = urlParams.ptoString.split(",");
    }
    else {
        this.params_["supportedPostToTargets"] = ["COMMENTS", "BLOG", "BULLETINS", "PROFILE", "SEND_MESSAGE", "SHARE_APP", "APP_MESSAGE", "ADD_TO_FRIENDS"];
    }

    if (urlParams.userBlockedApp) {
        this.params_["appBlocked"] = true;
    }
    else {
        this.params_["appBlocked"] = false;
    }

    if (urlParams.userLoggedOut) {
        this.params_["loggedOut"] = true;
    }
    else {
        this.params_["loggedOut"] = false;
    }

    if (urlParams.installState) {
        this.params_["installState"] = "" + urlParams.installState;
    }
    else {
        this.params_["installState"] = "1"
    }

    if (urlParams.perm) {
        perm = gadgets.json.parse('{"permissions":' + urlParams.perm + "}");
        this.params_["viewerPerm"] = perm.permissions;
    }
    else {
        this.params_["viewerPerm"] = [];
    }

    MyOpenSpace.MDPContainerRSAMultipleRecipients = true;
    MyOpenSpace.MDPContainerUseOpenCanvas = true;
    MyOpenSpace.EnableClientCache = false;

    if (urlParams && urlParams.mc) {
        var mc = urlParams.mc.split(",");
        for (var i = 0; i < mc.length; i++) {
            switch (mc[i]) {
                case "UOC":
                    MyOpenSpace.MDPContainerUseOpenCanvas = false;
                    break;
                case "RSAMR":
                    MyOpenSpace.MDPContainerRSAMultipleRecipients = false;
                    break;
                case "ECC":
                    MyOpenSpace.EnableClientCache = true;
                    break;
            }
        }
    }

    this.params_["remoteRelay"] = "http://profile.myspace.com/Modules/Applications/Pages/ifpc_relay.aspx";
}
MyOpenSpace.MySpaceContainer.container_ = new MyOpenSpace.MySpaceContainer();

MyOpenSpace.MySpaceContainer.get = function () {
    return MyOpenSpace.MySpaceContainer.container_;
}

MyOpenSpace.MySpaceContainer.prototype.getQueryString = function () {
    var qryString = window.location.search.substring(1);
    var perms = this.params_["viewerPerm"];
    var permission = "";
    for (var i = 0; i < perms.length; i++) {
        if (permission[i] !== '') {
            if (permission !== '') permission += "%2C";
            permission += "%22" + perms[i] + "%22"
        }
    }
    permission = "[" + permission + "]";
    qryString = qryString.replace(/&perm=(.*?)&/, "&perm=" + permission + "&");

    if ("" + this.params_["installState"] === "1") {
        return qryString.replace(/&installState=(.*?)&/, "&installState=1&")
    }
    else {
        return qryString.replace(/&installState=(.*?)&/, "&installState=0&")
    }
}


MyOpenSpace.MySpaceContainer.prototype.newMediaItem = function (mimeType, url,
    opt_params) {
    return new MyOpenSpace.MediaItem(mimeType, url, opt_params);
};

MyOpenSpace.MySpaceContainer.prototype.newActivity = function (opt_params) {
    return new MyOpenSpace.Activity(opt_params);
};

var _IFPC = window["_IFPC"];

MyOpenSpace.MySpaceContainer.prototype.registerParam = function (key, value) {
    this.params_[key] = value;
};

MyOpenSpace.MySpaceContainer.prototype.getParam = function (key) {
    return this.params_[key];
};


MyOpenSpace.MySpaceContainer.prototype.newMessage = function (body, opt_params) {
    return new MyOpenSpace.Message(body, opt_params);
};

MyOpenSpace.MySpaceContainer.prototype.requestCreateActivity = function (activity, priority, opt_callback) {
    var container = MyOpenSpace.MySpaceContainer.get();
    if (!container.hasPermission(MyOpenSpace.Permission.VIEWER_SEND_UPDATES_TO_FRIENDS)) {
        if (opt_callback) {
            var ri = {
                "errorCode": "UNAUTHORIZED",
                "errorMessage": "You do not have permission to send an activities. Send updates to friends permission is required."
            };
            opt_callback(ri);
        }
        return;
    }
    if (!activity || !activity.getField(MyOpenSpace.Activity.Field.TITLE_ID)) {
        if (opt_callback) {
            var ri = {
                "errorCode": "BAD_REQUEST",
                "errorMessage": "You must supply an MyOpenSpace.Activity object with a TITLE_ID."
            };
            opt_callback(ri);
        }
        return;
    }


    var convertActivityToMessage = function (activity) {
        var body, title, title_id, body_id, type = MyOpenSpace.PostTo.Targets.ACTIVITY;
        var container = MyOpenSpace.MySpaceContainer.get();

        // parse media items, save them to the message body
        if (activity.getField(MyOpenSpace.Activity.Field.MEDIA_ITEMS) && activity.getField(MyOpenSpace.Activity.Field.MEDIA_ITEMS).length > 0) {
            var mediaItemsOld = activity.getField(MyOpenSpace.Activity.Field.MEDIA_ITEMS);
            var mediaItemsNew = "{";

            for (var i = 0; i < mediaItemsOld.length; i++) {
                if (0 !== i) {
                    mediaItemsNew += ",";
                }
                mediaItemsNew += "\"" + mediaItemsOld[i].getField(MyOpenSpace.MediaItem.Field.URL) + "\"";
            }
            mediaItemsNew += "}";

            body = escape(mediaItemsNew);
        }

        // parse template params, save them to the title
        if (activity.getField(MyOpenSpace.Activity.Field.TEMPLATE_PARAMS)) {
            title = escape(gadgets.json.stringify(activity.getField(MyOpenSpace.Activity.Field.TEMPLATE_PARAMS)));
        }

        // parse title id, save them to the title id
        title_id = escape(activity.getField(MyOpenSpace.Activity.Field.TITLE_ID));

        // parse priority, save them to the body id
        body_id = activity.getField(MyOpenSpace.Activity.Field.PRIORITY);

        var params = {};
        params[MyOpenSpace.Message.Field.TITLE] = title;
        params[MyOpenSpace.Message.Field.TITLE_ID] = title_id;
        params[MyOpenSpace.Message.Field.TYPE] = type;
        params[MyOpenSpace.Message.Field.BODY_ID] = priority;

        return container.newMessage(body, params);
    };

    if (priority) activity.setField(MyOpenSpace.Activity.Field.PRIORITY, priority);
    var message = convertActivityToMessage(activity);

    container.postTo(message, opt_callback);
};

MyOpenSpace.MySpaceContainer.prototype.adjustHeight = function (opt_height) {
    var newHeight = parseInt(opt_height, 10);
    var percentage = false;
    if (isNaN(newHeight)) {
        var vh = gadgets.window.getViewportDimensions().height;
        var body = document.body;
        var docEl = document.documentElement;
        if (document.compatMode == 'CSS1Compat' && docEl.scrollHeight) {
            newHeight = docEl.scrollHeight != vh ? docEl.scrollHeight : docEl.offsetHeight;
        }
        else {
            var sh = docEl.scrollHeight;
            var oh = docEl.offsetHeight;

            if (docEl.clientHeight != oh) {
                sh = body.scrollHeight;
                oh = body.offsetHeight;
            }

            if (sh > vh) {
                newHeight = sh > oh ? sh : oh;
            }
            else {
                newHeight = sh < oh ? sh : oh;
            }

            if (newHeight === vh &&
                    window.navigator &&
                    window.navigator.userAgent &&
                    window.navigator.userAgent.toLowerCase().indexOf("safari") >= 0) { //for safari, quite ugly...
                var newDiv = document.createElement("div");
                newDiv.innerHTML = document.body.innerHTML;
                newDiv.style.visibility = "hidden";
                newDiv.id = "_temp_____div_for_____adjustHeight";
                document.body.appendChild(newDiv);
                newHeight = document.getElementById("_temp_____div_for_____adjustHeight").offsetHeight + 15;
                newDiv.innerHTML = "";
                document.body.removeChild(newDiv);
            }
        }
    }
    else if (0 === newHeight) {
        newHeight = parseFloat(opt_height);
        if (!isNaN(newHeight) && (newHeight <= 1 || newHeight > 0)) percentage = true;
    }

    var oldHeight = gadgets.window.getViewportDimensions().height;
    if (newHeight != oldHeight || percentage) {
        var p = this.params_;
        _IFPC.call(
            p.panelId,
            "resizeWidget",
            [p.panelId, newHeight],
            p.remoteRelay,
            null,
            p.localRelay,
            null);
    }
};

MyOpenSpace.MySpaceContainer.prototype.requestNavigateTo = function (view, opt_params) {

    if (view) {
        if (0 === view.indexOf("profile.")) view = "profile";
        var p = this.params_;
        _IFPC.call(
            p.panelId,
            "requestNavigateTo",
            [p.appid, p.ownerid, view.toLowerCase(), opt_params],
            p.remoteRelay,
            null,
            p.localRelay,
            null);
    }
}


MyOpenSpace.MySpaceContainer.prototype.postTo = function (message, opt_callback, recipientId, recipientName, recipientThumbnail, recipientProfileUrl) {
    if (MyOpenSpace.View.Field.CANVAS !== gadgets.views.getCurrentView().getName()) {
        return {
            "errorCode": "BAD_REQUEST",
            "errorMessage": "PostTo does not support this view, only the canvas view is supported."
        };
    }
    var target_is_supported = false;
    var supported = this.params_.supportedPostToTargets;

    var messageSubject = "";
    var messageBody;
    var messageType;

    if (null !== message && "undefined" !== typeof (message)) {
        messageSubject = message.getField(MyOpenSpace.Message.Field.TITLE);
        messageBody = message.getField(MyOpenSpace.Message.Field.BODY);
        messageType = message.getField(MyOpenSpace.Message.Field.TYPE);
    }
    else {
        if (opt_callback)
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "You must supply a valid Message object."
            });
        return;
    }

    if (messageType === MyOpenSpace.PostTo.Targets.SHARE_APP) {
        this.requestShareApp(recipientId, message, opt_callback);
        return;
    }

    for (var i = 0; i < supported.length; i++) {
        if (supported[i] === messageType) {
            target_is_supported = true;
            break;
        }
    }

    if (!target_is_supported) {
        if (opt_callback)
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "That PostTo target is not supported."
            });
        return;
    }

    var token = this.params_.osToken;
    if (typeof (token) === 'undefined') {
        if (opt_callback)
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "Open social token not set."
            });
        return;
    }
    var priority = null;
    var titleId = null;
    if (messageType === MyOpenSpace.PostTo.Targets.ACTIVITY) {
        titleId = message.getField(MyOpenSpace.Message.Field.TITLE_ID);
        priority = message.getField(MyOpenSpace.Message.Field.BODY_ID);
        recipientId = this.params_.viewerId;
    }

    var panelId = this.params_.panelId;
    if (messageType === MyOpenSpace.PostTo.Targets.ADD_TO_FRIENDS) {
        this.requestAddToFriends(recipientId, opt_callback);
    }
    else {
        _IFPC.call(
            panelId,
            "postTo",
            [token, messageType,
			messageSubject, messageBody, recipientId, recipientThumbnail,
			recipientName, recipientProfileUrl, titleId, priority, this.params_.appid],
            this.params_.remoteRelay,
            opt_callback,
            this.params_.localRelay,
            null);
    }
};

MyOpenSpace.MySpaceContainer.prototype.newNavigationParameters = function (opt_params) {
    return new MyOpenSpace.NavigationParameters(opt_params);
};

MyOpenSpace.MySpaceContainer.prototype.requestAddToFriends = function (recipientId, opt_callback) {
    //Only can run on canvas
    if (MyOpenSpace.View.Field.CANVAS !== gadgets.views.getCurrentView().getName()) {
        return {
            "errorCode": "BAD_REQUEST",
            "errorMessage": "PostTo does not support this view, only the canvas view is supported."
        };
    }

    var supported = this.params_.supportedPostToTargets;
    for (var i = 0; i < supported.length; i++) {
        if (supported[i] === MyOpenSpace.PostTo.Targets.ADD_TO_FRIENDS) {
            target_is_supported = true;
            break;
        }
    }

    if (!target_is_supported) {
        if (opt_callback) {
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "requestAddToFriends is not supported or temporarily disabled."
            });
        }
        return;
    }

    if (typeof (recipientId) === 'undefined' || recipientId === null) {
        if (opt_callback) {
            opt_callback(opensocial.Container.get().newResponseItem(null, null,
						        opensocial.ResponseItem.Error.BAD_REQUEST,
						        "Recipient is required."));
        }
        return;
    }

    if (MyOpenSpace.Util.isArray(recipientId)) {
        if (opt_callback) {
            opt_callback(opensocial.Container.get().newResponseItem(null, null,
						        opensocial.ResponseItem.Error.NOT_IMPLEMENTED,
						        "Unsupported idSpec, only OWNER, VIEWER or one friend ID is allowed."));
        }
        return;
    }

    var friendId;
    if (recipientId == "VIEWER")
        friendId = this.params_.viewerId;
    else if (recipientId == "OWNER")
        friendId = this.params_.ownerid;
    else {
        friendId = MyOpenSpace.Util.parseIdPrefix(recipientId);
    }

    if (null === friendId || typeof friendId === 'undefined' || friendId === '') {
        if (opt_callback) {
            opt_callback(opensocial.Container.get().newResponseItem(null, null,
					            opensocial.ResponseItem.Error.BAD_REQUEST,
					            "Provided Recipient ID is not valid"));
        }
        return;
    }

    var ifpc_params = {};
    ifpc_params.friendId = friendId;
    ifpc_params.appId = this.params_.appid;
    //TODO: check how many vars really we need to set from the following.
    ifpc_params.os_token = this.params_.osToken;
    ifpc_params.post_type = MyOpenSpace.PostTo.Targets.ADD_TO_FRIENDS;
    ifpc_params.opt_appId = this.params_.appid;

    _IFPC.call(
    this.params_.panelId,
    'addFriend',
    [ifpc_params],
    this.params_.remoteRelay,
    opt_callback,
    this.params_.localRelay,
    null);
}

MyOpenSpace.MySpaceContainer.prototype.requestShareApp = function (recipients, reason, opt_callback, opt_params) {
    if (MyOpenSpace.View.Field.CANVAS !== gadgets.views.getCurrentView().getName()) {
        if (opt_callback) {
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "requestShareApp does not support this view, only the canvas view is supported."
            });
        }
        return;
    }

    var navParams = {};
    if (null !== opt_params && "undefined" !== typeof (opt_params)) {
        if (opt_params.constructor !== Array) {
            opt_params = [opt_params];
        }

        for (var i = 0; i < opt_params.length; i++) {
            if ("undefined" === typeof (opt_params[i].getField)) {
                if (opt_callback) {
                    opt_callback({
                        "errorCode": "BAD_REQUEST",
                        "errorMessage": "The opt_params parameter passed into requestShareApp must be a valid MyOpenSpace.NavigationParameters object, or an array of such."
                    });
                }
                return;
            }

            if (opt_params[i].getField(MyOpenSpace.NavigationParameters.Field.DESTINATION_TYPE) ===
	                MyOpenSpace.NavigationParameters.DestinationType.VIEWER_DESTINATION) {
                if (opt_callback) {
                    opt_callback({
                        "errorCode": "NOT_IMPLEMENTED",
                        "errorMessage": "MyOpenSpace.NavigationParameters.DestinationType.VIEWER_DESTINATION is not supported."
                    });
                }
                return;
            }

            var paramsLength = gadgets.json.stringify(opt_params[i].getField(MyOpenSpace.NavigationParameters.Field.PARAMETERS)).length;
            var maxLength = 1024;
            if (paramsLength > maxLength) {
                if (opt_callback) {
                    opt_callback({
                        "errorCode": "BAD_REQUEST",
                        "errorMessage": "MyOpenSpace.NavigationParameters.Field.PARAMETERS must be under " + maxLength + " characters, yours was:" + paramsLength
                    });
                }
                return;
            }

            navParams[opt_params[i].getField(MyOpenSpace.NavigationParameters.Field.DESTINATION_TYPE)] =
	            opt_params[i].getField(MyOpenSpace.NavigationParameters.Field.PARAMETERS);
        }
    }

    if (typeof (reason) === 'undefined' || typeof (reason.getField) === 'undefined') {
        if (opt_callback) {
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "Invalid opensocial.Message object for parameter 'reason'."
            });
        }
        return;
    }

    var supported = this.params_.supportedPostToTargets;
    for (var i = 0; i < supported.length; i++) {
        if (supported[i] === MyOpenSpace.PostTo.Targets.SHARE_APP) {
            target_is_supported = true;
            break;
        }
    }

    if (!target_is_supported) {
        if (opt_callback) {
            opt_callback({
                "errorCode": "BAD_REQUEST",
                "errorMessage": "requestShareApp is not supported or temporarily disabled."
            });
        }
        return;
    }

    if (typeof (recipients) === 'undefined' || recipients === null) {
        opt_callback({
            "errorCode": "BAD_REQUEST",
            "errorMessage": "'recipients' parameter is required."
        });
        return;
    }

    if (recipients.constructor !== Array) {
        recipients = [recipients];
    }
    else if (false === MyOpenSpace.MDPContainerRSAMultipleRecipients && recipients.length > 1) {
        if (opt_callback) {
            opt_callback({
                "errorCode": "NOT_IMPLEMENTED",
                "errorMessage": "requestShareApp does not currently support multiple recipients."
            });
        }
        return;
    }


    var results, ids = [];
    for (var i = 0; i < recipients.length; i++) {
        results = MyOpenSpace.Util.parseIdPrefix(recipients[i]);
        if (results === null) {
            if (opt_callback) {
                opt_callback({
                    "errorCode": "BAD_REQUEST",
                    "errorMessage": "Invalid id, IDs must be strings or array of strings, in the format '6221' or 'myspace.com:6221'"
                });
            }
            return;
        }

        ids.push(results);
    }

    var subject = reason.getField(MyOpenSpace.Message.Field.TITLE);
    var body = reason.getField(MyOpenSpace.Message.Field.BODY);

    var ifpc_params = {};
    ifpc_params.os_token = this.params_.osToken;
    ifpc_params.post_type = MyOpenSpace.PostTo.Targets.SHARE_APP;
    ifpc_params.subject = subject;
    ifpc_params.content = body;
    ifpc_params.opt_recipientId = ids.join(",");
    ifpc_params.opt_appId = this.params_.appid;
    ifpc_params.opt_navParams = navParams;

    _IFPC.call(this.params_.panelId,
        "postToV2",
        [ifpc_params],
        this.params_.remoteRelay,
        opt_callback,
        this.params_.localRelay,
        null);
};

/**
* Returns true if the current gadget has access to the specified
* permission. If the gadget calls opensocial.requestPermission and permissions
* are granted then this function must return true on all subsequent calls.
*
* @internal
* @private
*/
MyOpenSpace.MySpaceContainer.prototype.hasPermission = function (permission) {
    var params = this.params_;
    var appBlocked = params.appBlocked;
    var loggedOut = params.loggedOut;

    if ((appBlocked || loggedOut)) {
        return false;
    }

    var checkPermsissions = function (permission) {
        var userPermissions = params.viewerPerm;

        for (var i in userPermissions) {
            if (userPermissions[i] === permission.permissionIndicator) {
                return true;
            }

            if (permission.permissionIndicator === MyOpenSpace.Permission.VIEWER_CONTACT_INFO.permissionIndicator
             && userPermissions[i] === MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO.permissionIndicator) {
                return true;
            }

            if (permission.permissionIndicator === MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO.permissionIndicator
             && userPermissions[i] === MyOpenSpace.Permission.VIEWER_CONTACT_INFO.permissionIndicator) {
                return true;
            }
        }
        return false;
    }

    var installState = params.installState;


    if (
		permission.permissionIndicator === "BI" || permission.permissionIndicator === "BC" ||
		permission.permissionIndicator === "FL"
	) {
        if (installState === "0") {
            return checkPermsissions(permission);
        }
        else {
            return true;
        }
    }
    else if (installState === "0") {
        return false;
    }

    return checkPermsissions(permission);

}


MyOpenSpace.MySpaceContainer.prototype.requestPermission = function (permission, reason, opt_callback) {
    var params = this.params_;
    var supportedPermissions = [
		MyOpenSpace.Permission.VIEWER_DISPLAY_ON_PROFILE,
		MyOpenSpace.Permission.VIEWER_DISPLAY_ON_HOME,
		MyOpenSpace.Permission.VIEWER_SEND_UPDATES_TO_FRIENDS,
		MyOpenSpace.Permission.VIEWER_ACCESS_TO_PRIVATE_VIDEOS_PHOTOS,
		MyOpenSpace.Permission.VIEWER_ACCESS_TO_PUBLIC_VIDEOS_PHOTOS,
		MyOpenSpace.Permission.VIEWER_SHOW_UPDATES_FROM_FRIENDS,
		MyOpenSpace.Permission.VIEWER_ACCESS_TO_IDENTITY_INFORMATION,
		MyOpenSpace.Permission.VIEWER_ADD_PHOTOS_TO_ALBUMS,
		MyOpenSpace.Permission.VIEWER_UPDATE_MOOD_STATUS,
		MyOpenSpace.Permission.VIEWER_UPDATE_PROFILE,
		MyOpenSpace.Permission.VIEWER_CONTACT_INFO,
		MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO
	];

    if (MyOpenSpace.View.Field.CANVAS !== gadgets.views.getCurrentView().getName()) {
        return {
            "errorCode": "NOT_IMPLEMENTED",
            "errorMessage": "requestPermission does not support this view, only the canvas view is supported."
        };
    }

    var validatedPermissions = new Array();
    var isFullContactInfo = false;
    var isFullProfileInfo = false;
    //Check that is a valid permission object
    alert(permission.permission);
    if (permission && typeof (permission.permission) !== 'undefined') {
        //Check if the permission is supported	
        for (var supported in supportedPermissions) {
            if (supportedPermissions[supported].permission === permission.permission) {
                //check if the user doesn't have the requested permission.
                if (!this.hasPermission(permission)) {
                    //VIEWER_CONTACT_INFO is merged up with VIEWER_FULL_PROFILE_INFO, so request for that permission
                    if (permission === MyOpenSpace.Permission.VIEWER_CONTACT_INFO) {
                        isFullContactInfo = true;
                        validatedPermissions.push(MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO.permission);
                    }
                    else {
                        if (permission === MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO) {
                            isFullProfileInfo = true;
                        }

                        validatedPermissions.push(permission.permission);
                    }
                }
                break;
            }
        }
    }



    var userGrantedPermissions_sync = function (permissionState) {
        var currentContainer = MyOpenSpace.MySpaceContainer.get();
        if (permissionState) {
            var currentPermissions = params.viewerPerm;
            var permissionsGranted = [];
            var allPermissionsGranted = true;
            for (var key in permissionState) {
                var permObj = null;
                var granted = permissionState[key];

                if (key == "installed") {
                    currentContainer.registerParam("installState", 1);
                    continue;
                }
                allPermissionsGranted &= granted;
                for (var permIdex in supportedPermissions) {
                    if (supportedPermissions[permIdex].permission.toLowerCase() === key.toLowerCase()) {
                        permObj = supportedPermissions[permIdex];
                        break;
                    }
                }
                if (permObj !== null) {
                    if (granted) {
                        if (permObj === MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO) {
                            if (isFullContactInfo)
                                permissionsGranted.push(MyOpenSpace.Permission.VIEWER_CONTACT_INFO);
                            if (isFullProfileInfo)
                                permissionsGranted.push(MyOpenSpace.Permission.VIEWER_FULL_PROFILE_INFO);
                        }
                        else {
                            permissionsGranted.push(permObj);
                        }
                    }
                    var permissionExist = false;
                    var permissionIndex = 0;
                    //Add permission to viewerPerm if is not there
                    for (var permIndex in currentPermissions) {
                        if (currentPermissions[permIdex] === permObj.permissionIndicator) {
                            permissionExist = true;
                            permissionIndex = permIndex;
                            break;
                        }
                    }
                    if (!permissionExist && granted) {
                        currentPermissions.push(permObj.permissionIndicator);
                        currentContainer.registerParam("viewerPerm", currentPermissions);
                    }
                    else if (permissionExist && !granted) {
                        currentPermissions.splice(permissionIndex, 1);
                        currentContainer.registerParam("viewerPerm", currentPermissions);
                    }
                }
            }
            if (opt_callback) {
                if (allPermissionsGranted && permissionsGranted.length > 0) {
                    opt_callback({
                        "permissions": permissionsGranted
                    });
                }
                else if (!allPermissionsGranted && permissionsGranted.length > 0) {
                    opt_callback({
                        "permissions": permissionsGranted,
                        "errorCode": "UNAUTHORIZED",
                        "errorMessage": "No all the permissions were granted."
                    });
                }
                else {
                    opt_callback({
                        "errorCode": "UNAUTHORIZED",
                        "errorMessage": "No new permissions were granted."
                    });
                }
                return;
            }
        }
        else {
            if (opt_callback) opt_callback({
                "errroCode": "UNAUTHORIZED",
                "errorMessage": "No new permissions were granted."
            });
            return;
        }
    };

    if (validatedPermissions.length === 0) {
        if (opt_callback) opt_callback({
            "errroCode": "BAD_REQUEST",
            "errorMessage": "No valid permissions were requested."
        });
        return;
    }
    _IFPC.call(params.panelId,
		"requestPermission",
		[params.appid, validatedPermissions, reason],
		params.remoteRelay,
		userGrantedPermissions_sync,
			params.localRelay,
			null);

};
