define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 为表格绑定事件
           $.ajax({url:"declared/company/getcompanydata",success:function(result){
			  		
				        $.each(result, function(i,val){     
					      $('#company_select').append("<option value='"+val.id+"'>"+val.company_park_name+"</option>");
					      
					  });   
			        	
			    }});
		
             Form.api.bindevent($("form[role=form]"), function (data, ret) {
                setTimeout(function () {
                    location.href = "/tongji-master/public/declared/company";
                }, 1000);
            });
        },
        add: function () {
		  
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
        
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});