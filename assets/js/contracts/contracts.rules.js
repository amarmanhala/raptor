/* global angular, base_url, bootbox, google */

"use strict";
 
        
   app.controller('ContractRulesCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
 
        
         var deferred;  
     
    //Any function returning a promise object can be used to load values asynchronously
    $scope.getSiteAddress = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'ajax/loadsitesearch', {
                params: {
                        search: val
                       },
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{

                    deferred.resolve(response.data);
                }

            });
            return deferred.promise;  

        };
       
        $scope.onSiteAddressSelect = function ($item, $model, $label) {
             
            $scope.siteaddress = $item.site;
            $("#parentJobform #labelid").val($item.labelid); 
            $("#parentJobform #contactid").val($item.contactid); 
         };
        
        $(document).on('click', '#ContractParentJobsModal #btnsave', function() {

            var siteaddress = $("#parentJobform #siteaddress");
            var labelid = $('#parentJobform #labelid');
            var contactid = $("#parentJobform #contactid");
            var custordref1 = $("#parentJobform #custordref1");
            var custordref2 =  $("#parentJobform #custordref2"); 
            var custordref3 = $("#parentJobform #custordref3"); 
            var description = $("#parentJobform #description"); 
           
            
            $("#parentJobform span.help-block").remove();
            var validationerror = false;
            if($.trim(siteaddress.val()) === "") {
                $(siteaddress).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(siteaddress.parent());
                validationerror = true;
                
            } else {
                $(siteaddress).parent().removeClass("has-error");
            }
            if($.trim(labelid.val()) === "") {
                $(siteaddress).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(siteaddress.parent());
                validationerror = true;
            } else {
                $(siteaddress).parent().removeClass("has-error");
            }
            
            if($.trim(contactid.val()) === "") {
                $(contactid).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(contactid.parent());
                validationerror = true;
            } else {
                $(contactid).parent().removeClass("has-error");
            }
            
            if($.trim(custordref1.val()) === "") {
                $(custordref1).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(custordref1.parent());
                validationerror = true;
            } else {
                $(custordref1).parent().removeClass("has-error");
            }
            if($.trim(description.val()) === "") {
                $(description).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(description.parent());
                validationerror = true;
            } else {
                $(description).parent().removeClass("has-error");
            }
       
            if(validationerror){
                return false;
            }
            
            $("#parentJobform #btnsave").button('loading'); 
            $("#parentJobform #btncancel").button('loading'); 
            $.post( base_url+"contracts/saveparentjob", $("#parentJobform").serialize(), function( data ) {
                $('#parentJobform #btnsave').removeAttr("disabled");
                $('#parentJobform #btncancel').removeAttr("disabled");
                
                $('#parentJobform #btnsave').removeClass("disabled");
                $('#parentJobform #btncancel').removeClass("disabled");
                $('#parentJobform #btnsave').html("Save");
                $('#parentJobform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#ContractParentJobsModal").modal('hide');
                    bootbox.alert(data.message);
                    location.reload();
                }
                else{
                     $('#ContractParentJobsModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#ContractParentJobsModal #btncancel', function() {
            $("#ContractParentJobsModal").modal('hide');
        });
        
        
    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$( document ).ready(function() {
    
    
     
});

var openParentJob = function(type) {
        
        $('#parentJobform').trigger("reset");
        $("#parentJobform .alert-danger").hide(); 
        $("#parentJobform span.help-block").remove();
        $("#parentJobform .has-error").removeClass("has-error");
        $('#parentJobform #btnsave').button("reset");
       
        $('#parentJobform #btncancel').button("reset");
        $('#parentJobform #btnsave').removeAttr("disabled");
        $('#parentJobform #btncancel').removeAttr("disabled"); 
        $("#parentJobform .close").css('display', 'block');
        $("#ContractParentJobsModal #loading-img").show();
        $("#ContractParentJobsModal #parentJobdiv").hide();
        $("#ContractParentJobsModal .modal-footer").hide();
     
            
        if(type === 'add'){
            $("#ContractParentJobsModal").modal();
            $("#ContractParentJobsModal h4.modal-title").html('Add Parent Job');
            $("#parentJobform #mode").val('add');   
            $('#parentJobform #jobiddiv').hide();
            $("#parentJobform #jobid").val('');
           setTimeout(function(){ 
                $("#ContractParentJobsModal #loading-img").hide();
                $("#ContractParentJobsModal #parentJobdiv").show();
                $("#ContractParentJobsModal .modal-footer").show();

            }, 1000);
        }
        else{
//            if($("#contractrulesform #parentjobid").val() === ''){ 
//                bootbox.alert('Please select location before '+ type +' location.');
//                return false;
//            }
            var jobid = $("#contractrulesform #parentjobid").val();
            $("#ContractParentJobsModal").modal();
            $("#ContractParentJobsModal h4.modal-title").html('Edit Parent Job - '+ jobid);
            $("#parentJobform #mode").val('edit'); 
            $('#parentJobform #jobiddiv').show();
            $("#parentJobform #jobid").val(jobid); 
            $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( response ) { 
                
                if (response.success) {
                     
                    //$scope.siteaddress = response.data.address;
                    $("#parentJobform #siteaddress").val(response.data.address);
                    $("#parentJobform #custordref1").val(response.data.custordref);
                    $("#parentJobform #custordref2").html(response.data.custordref2);
                    $("#parentJobform #custordref3").val(response.data.custordref3);
                    $("#parentJobform #description").html(response.data.jobdescription);
                    
                    $("#ContractParentJobsModal #loading-img").hide();
                    $("#ContractParentJobsModal #parentJobdiv").show();
                    $("#ContractParentJobsModal .modal-footer").show();
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
        }
          
    };
    
    var openSubJob = function() {
        bootbox.alert('Comming Soon...');
    };