/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */
if (typeof Utils !== 'object') {

    var Utils = (function () {

        /**
         * Parse the URL
         * and return his protocol and domain as URL (http://example.com)
         *
         * URI Parsing with Javascript
         * @returns {string}
         */
        function getDomainFromUrl(url) {
            // See: https://gist.github.com/jlong/2428561
            var parser = document.createElement('a');
            parser.href = url;
            // return parser.protocol + "//" + parser.host;
            return parser.protocol + "//" + parser.hostname + ":" + parser.port;
        }

        /**
         * Read a page's GET URL variables and return them as an associative array.
         * @returns {Array}
         */
        function getUrlVars()
        {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        /**
         * Send a message to parent window
         */
        function postMessage(message)
        {
            var referrer = getDomainFromUrl(document.referrer);
            var url = getDomainFromUrl(window.location.href);
            if (referrer === url) {
                var urlVars = getUrlVars();
                referrer = decodeURIComponent(urlVars['url']);
            }
            message.formID = options.id;
            try {
                parent.postMessage(JSON.stringify(message), getDomainFromUrl(referrer));
            } catch (e) {
                console.log(e);
            }
        }

        /**
         * Show alert message
         * @param container
         * @param txt
         * @param type
         */
        function showMessage(container, txt, type)
        {
            var message = $("<div>", {"class": "alert alert-"+type, "html": txt});
            $(container).append(message);
        }

        /**
         * Hide alert message
         * @param container
         */
        function hideMessage(container)
        {
            $(container).empty();
        }

        /**
         * Convert string to 32bit integer
         *
         * @param str
         * @returns {number}
         */
        function hashCode(str)
        {
            var hash = 0;
            for (i = 0; i < str.length; i++) {
                char = str.charCodeAt(i);
                hash = ((hash<<5)-hash)+char;
                hash = hash & hash; // Convert to 32bit integer
            }
            return hash;
        }

        /**
         * Utils
         *
         * @type {{getDoaminFromUrl: getDomainFromUrl, getUrlVars: getUrlVars, postMessage: postMessage}}
         */
        var Utils = {
            getDomainFromUrl: getDomainFromUrl,
            getUrlVars: getUrlVars,
            postMessage: postMessage,
            showMessage: showMessage,
            hideMessage: hideMessage,
            hashCode: hashCode
        };

        return Utils;

    }());
}
