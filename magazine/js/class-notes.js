jQuery(document).ready(function() {

  // so get the current URL
  var current_url = window.location.href;
  var url_parts = current_url.split('/');
  if (url_parts.length >= 4) {
    current_url = '/class-notes';
    if (url_parts[4] == 'archive') {
      current_url = '/class-notes/archive';
    }
  }
  var selected_year = url_parts[url_parts.length - 1];  
  
  // change the selected option to match the url
  jQuery('.class-notes-select-year option[value="' + selected_year + '"]').attr(
    'selected', 'selected'
  );
  
  // if someone changes this, go to that year
  jQuery('.class-notes-select-year select').on('change', function() {
     
    // get the new value
    var selected_value = jQuery(this).val();
    if (selected_value == 'all') {
      selected_value = '';
    }
    
    // and off we go
    window.location = current_url + "/" + selected_value;
    
  });
  
  // also going to hijack the URL for the View All Button
  if (url_parts.length >= 4) {
    if (url_parts[4] == 'archive') {
      // hide the whole block
      jQuery('#block-classnotesarchivebar').remove();
    }
  }
  if (current_url == '/class-notes') {
    var archive = current_url + "/archive/" + selected_year;
    jQuery('#block-classnotesarchivebar a').attr(
      'href', archive
    );
  }

  
});