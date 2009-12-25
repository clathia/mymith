function CreateHTTP() {
  var xmlhttp=false;
  /*@cc_on @*/
  /*@if (@_jscript_version >= 5)
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest!='undefined')
    xmlhttp = new XMLHttpRequest();
  return xmlhttp;
}

function HTTPGet(uri, callback_function, callback_parameter) {
  var xmlhttp = CreateHTTP();
  var bAsync = true;
  if (!callback_function)
    bAsync = false;
  xmlhttp.open('GET', uri, bAsync);
  xmlhttp.send(null);

  if (bAsync) {
    if (callback_function)
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4)
          callback_function(xmlhttp.responseText, xmlhttp, callback_parameter)
      }
    return true;
  } else
    return xmlhttp.responseText;
}

function HTTPinto(html, http, node) {
  node.innerHTML = html;
}