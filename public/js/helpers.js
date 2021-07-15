/**
 * Detect URLs and encase them in <a>
 *
 * @param {*} text
 * @returns
 */
function urlify(text) {
  const urlRegex = /(https?:\/\/[^\s]+)/g;
  if (text) {
    return text.replace(urlRegex, '<a target="_blank" href="$1">$1</a>');
  }
  return '';
}

/**
 * Find breaks, and add <br />
 *
 * @param {string} text
 * @returns
 */
function breakify(text) {
  if (text) {
    return text.replace(/(?:\r\n|\r|\n)/g, '<br />');
  }
  return '';
}


/**
 * Create map button
 *
 * @param {string} text Text of map link
 * @returns Formatted map button
 */
function mapify(text) {
  var footer = "";
  if (text) {
    footer += `<br /><a class="button" target="_blank" href="https://www.google.com/maps/search/?api=1&query=${encodeURI(text)}">Map</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp`;
  }
  return footer;
}


/**
 * Converts url to a formatted <a href=... link
 *
 * @param {string} url
 * @returns formatted HTML url
 */
function linkify(url) {
  if (url) {
    return `<a class="button" href="${url}" target="_blank">Add</a>`;
  }
}


/**
 * get event ID from URL
 *
 * @param {string} url url
 * @returns event ID
 */
function getEventId(url) {
  // console.log(url);
  return url.split('eid=')[1];
}


/**
 * Create GCalendar Link IDs
 *
 * @param {string} eventID
 * @param {string} calID
 * @returns Fully formed Gcalendar link
 */
function gcalLink(eventID, calID) {
  return `https://calendar.google.com/event?action=TEMPLATE&tmeid=${eventID}&tmsrc=${calID}&scp=ALL`;
}
