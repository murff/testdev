$('#country_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'autofill.php',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: $('#search_variable').val()
                },
                success: function(data) {
                    // alert(data)
                    response($.map(data, function(item) {
                        return {
                            label: item,
                            value: item
                        }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0
    });
function show_addres(element)
{
         
    if (element.options[element.selectedIndex].value === 'date_added'){
            //alert("Hi, I'm alert!");
            document.getElementById('datepicker').style.display = "inline";
            document.getElementById('datepicker1').style.display = "inline";
            document.getElementById('country_name').style.display = "none";
            
        }else{
            document.getElementById('datepicker').style.display = "none";
            document.getElementById('datepicker1').style.display = "none";
            document.getElementById('country_name').style.display = "inline";
            
            
        }
            
}
function searchFilter()
    {

        //alert("");
        $("#loader").show();

        var search_variable = $("#search_variable").val();
        var search_value;
        if(search_variable !== 'date_added'){
            search_value = $("#country_name").val();
            //alert(search_value);
        }else{
            var dt_frm = $.datepicker.formatDate('yy-mm-dd', new Date( $("#datepicker").val()));
            var dt_to = $.datepicker.formatDate('yy-mm-dd', new Date( $("#datepicker1").val()));
            
            //alert(dt_frm + ' '+ dt_to  );
            
            search_value = dt_frm +"'"+' and '+"'"+  dt_to; //search_date_value1
            
        }
        window.location ='allorders.php?action=save&search_variable=' + search_variable + '&search_value=' + search_value;
        
//        $.ajax({
//            url: 'allorders.php?act=save&search_variable=' + search_variable + '&search_value=' + search_value,
//            success: function(data) {
//                //alert(data);
//                $("#loader").hide();
//                $(".contentText").html(data);
//                // $("#feedbackcommon"+act_id).show();
//
//
//            }
//        });
    }
 
   var picker = new Pikaday(
        {
            field: document.getElementById('datepicker'),
            firstDay: 1,
            minDate: new Date('2000-01-01'),
            maxDate: new Date('2020-12-31'),
            yearRange: [2000, 2020],
            bound: true,
            //container: document.getElementById('container'),
        });
    
    var picker1 = new Pikaday(
        {
            field: document.getElementById('datepicker1'),
            firstDay: 1,
            minDate: new Date('2000-01-01'),
            maxDate: new Date('2020-12-31'),
            yearRange: [2000, 2020],
            bound: true,
            //container: document.getElementById('container'),
        });