jQuery(document).ready(function() {
  
  setupMegaMenu();
  setupIssueNav();
  doFeatureCaps();
  
});


function setupMegaMenu() {
  
  jQuery('.megamenu-panel').hover(
    function() {
      jQuery(this).addClass('open');
    },
    
    function() {
      jQuery(this).removeClass('open');
    }
  );
  
}


function setupIssueNav() {
  
  jQuery('header.rpi-header .burger-menu i').click(function() {
    jQuery('.issue-nav-container').addClass('open');
  });
  
  jQuery('.issue-nav-container .close-button i').click(function() {
    jQuery('.issue-nav-container').removeClass('open');
  })
  
}


function doFeatureCaps() {
  
  var s = 'article.page-type-feature ';
  s += '.field__item .paragraph--type--basic-text ';
  s += '.field--name-field-text p:first';
  
  jQuery(s).first().each(function() {
    
    var t = jQuery(this).html();
    var chr1 = t.substr(0, 1);
    if (_is_letter(chr1)) {
      var new_html = '<span class="fancy-caps">' + chr1 + '</span>';
      new_html += t.substr(1);
      jQuery(this).html(new_html);
    }
    else if (_is_punctuation(chr1)) {
      var chr2 = t.substr(1, 1);
      if (_is_letter(chr2)) {
        var new_html = '<span class="fancy-caps">' + chr1 + chr2 + '</span>';
        new_html += t.substr(2);
        jQuery(this).html(new_html);
      }
    }
    
  });
  
}

function _is_punctuation(t) {
  
  var tt = t.replace(/[a-z0-9]/gi, '');
  if (tt) return true;
  return false;
  
}

function _is_letter(t) {
  
  var tt = t.replace(/[^a-z]/gi, '');
  if (tt) return true;
  return false;
  
}