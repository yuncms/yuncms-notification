var Notifications = (function (opts) {
    if (!opts.id) {
        throw new Error('Notifications: the param id is required.');
    }

    var elem = jQuery('#' + opts.id);
    if (!elem.length) {
        throw Error('Notifications: the element was not found.');
    }

    var options = jQuery.extend({
        pollInterval: 60000,
        xhrTimeout: 2000,
        readLabel: 'read',
        markAsReadLabel: 'mark as read'
    }, opts);

    /**
     * Renders a notification row
     *
     * @param object The notification instance
     * @returns {jQuery|HTMLElement|*}
     */
    var renderRow = function (object) {
        var html = '<div href="#" class="dropdown-item notification-item' + (object.read !== 0 ? ' read' : '') + '"' +
            ' data-id="' + object.id + '"' +
            ' data-category="' + object.category + '"' +
            ' data-action="' + object.action + '">' +
            '<span class="icon"></span> ' +
            '<span class="message">' + object.message + '</span>' +
            '<small class="time-ago">' + object.relativeTime + '</small>' +
            '<span class="mark-read" data-toggle="tooltip" title="' + (object.read !== 0 ? options.readLabel : options.markAsReadLabel) + '"></span>' +
            '</div>';
        return jQuery(html);
    };

    var showList = function () {
        var list = elem.find('.notifications-list');
        jQuery.ajax({
            url: options.url,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            //loader: list.parent(),
            success: function (data) {
                var seen = 0;
                if (jQuery.isEmptyObject(data.list)) {
                    list.find('.empty-row span').show();
                }
                jQuery.each(data.list, function (index, object) {
                    if (list.find('>div[data-id="' + object.id + '"]').length) {
                        return;
                    }
                    var item = renderRow(object);
                    item.find('.mark-read').on('click', function (e) {
                        e.stopPropagation();
                        if (item.hasClass('read')) {
                            return;
                        }
                        var mark = jQuery(this);
                        jQuery.ajax({
                            url: options.readUrl,
                            type: "POST",
                            data: {id: item.data('id')},
                            dataType: "json",
                            timeout: opts.xhrTimeout,
                            success: function () {
                                markRead(mark);
                            }
                        });
                    }).tooltip();

                    if (object.url) {
                        item.on('click', function () {
                            document.location = object.url;
                        });
                    }

                    if(object.seen === 0){
                        seen += 1;
                    }
                    list.append(item);
                });
                setCount(seen, true);
                startPoll(true);
            }
        });
    };

    elem.find('> a[data-toggle="dropdown"]').on('click', function () {
        if (!jQuery(this).parent().hasClass('show')) {
            showList();
        }
    });

    elem.find('.read-all').on('click', function (e) {
        e.stopPropagation();
        var link = jQuery(this);
        jQuery.ajax({
            url: options.readAllUrl,
            type: "POST",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function () {
                markRead(elem.find('.dropdown-item:not(.read)').find('.mark-read'));
                link.off('click').on('click', function () {
                    return false;
                });
            }
        });
    });

    var markRead = function (mark) {
        mark.off('click').on('click', function () {
            return false;
        });
        mark.attr('title', options.readLabel);
        mark.tooltip('dispose').tooltip();
        mark.closest('.dropdown-item').addClass('read');
    };

    var setCount = function (count, decrement) {
        var badge = elem.find('#notifications-count');
        if (decrement) {
            count = parseInt(badge.data('count')) - count;
        }

        if (count > 0) {
            badge.data('count', count).text(count).show();
        }
        else {
            badge.data('count', 0).text(0).hide();
        }
    };

    var updateCount = function () {
        jQuery.ajax({
            url: options.countUrl,
            type: "GET",
            dataType: "json",
            timeout: opts.xhrTimeout,
            success: function (data) {
                setCount(data.count);
            },
            complete: function () {
                startPoll();
            }
        });
    };

    var _updateTimeout;
    var startPoll = function (restart) {
        if (restart && _updateTimeout) {
            clearTimeout(_updateTimeout);
        }
        _updateTimeout = setTimeout(function () {
            updateCount();
        }, opts.pollInterval);
    };

    // Fire the initial poll
    startPoll();

});