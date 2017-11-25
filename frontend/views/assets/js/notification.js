/**
 * 获取未读通知数
 * @param callback
 */
function getUnreadNotifications(callback) {
    callback = callback || jQuery.noop;
    jQuery.getJSON("/notification/notification/unread-notifications", function (result) {
        return callback(result.total);
    });
}