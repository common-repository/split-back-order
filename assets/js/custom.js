jQuery(document).ready(function(){
    jQuery('.showhide').on('change', function() {
      if (this.value == 'yes')
      {
        jQuery(".outerdiv").show();
      }
      else
      {
        jQuery(".outerdiv").hide();
      }
    });
});