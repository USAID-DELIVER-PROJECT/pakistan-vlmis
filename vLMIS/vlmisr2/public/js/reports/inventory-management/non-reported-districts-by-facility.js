  $(function() {
  
   
 $('#prov_sel').change(function() {
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: appName + "/reports/inventory-management/prov-2dist",
            data: {prov_sel: $(this).val(), combo: '2'},
            dataType: 'html',
            success: function(data)
            {

                $('#loader').hide();
                $('#dist_id').html(data);
                $('#teh_id').empty();
                $('#uc_id').empty();
            }
        });
    });
 
 if($('#prov_sel').val()!==""){
         
           $.ajax({
            type: "POST",
            url: appName + "/reports/inventory-management/prov-2dist",
            data: {prov_sel: $('#prov_sel').val(), combo: '2'},
            dataType: 'html',
            success: function(data)
            {

                
                $('#dist_id').html(data);
               $('#dist_id').val($('#dist_id_hidden').val());
            }
        });    
              
 }
 
 });