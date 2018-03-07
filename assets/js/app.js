$("#form_latitude").parent().hide();
$("#form_longitude").parent().hide();
$("#form_cause").parent().hide();

$("#form_latitude").removeAttr('required');
$("#form_longitude").removeAttr('required');
$("#form_cause").removeAttr('required');

$("#form_type").on( "change", function() {
    $choice=$( this ).val();





    if($choice == "G"){
        ///////// hide site report fields
        $("#form_latitude").parent().hide();
        $("#form_longitude").parent().hide();
        ///////// show general report fields
        $("#form_cause").parent().show();

        $("#form_latitude").removeAttr('required');
        $("#form_longitude").removeAttr('required');
        $("#form_cause").prop('required',true);

    }
    else if($choice == "S"){
        ///////// show general report fields
        $("#form_cause").parent().hide();
        ///////// hide site report fields
        $("#form_latitude").parent().show();
        $("#form_longitude").parent().show();
        ////////// add validation
        $("#form_cause").removeAttr('required');
        $("#form_latitude").prop('required',true);
        $("#form_longitude").prop('required',true);

    }
    else{
        ///////// hide all
        $("#form_cause").parent().hide();
        $("#form_latitude").parent().hide();
        $("#form_longitude").parent().hide();

    }

});


$(".approveAction").on("click",function () {
    $id=$(this).data('id');
    $approve=$(this).data('action');

    $('#form_id').val($id);
    $('#form_approved').val($approve);

    $('#justificationModal').modal('show');

});

/*
 *  data-id           ="{{ site_report.id }}"
 data-name         ="{{ site_report.name}}"
 data-priority     ="{{ site_report.priority }}"
 data-description  ="{{ site_report.description }}"
 data-latitude     ="{{ site_report.latitude }}"
 data-longitude    ="{{ site_report.longitude }}"
 data-justification="{{ site_report.justification }}"
 data-type="S"
 * */
$(".edit").on("click",function () {
    $id=$(this).data('id');
    $parent_id=$(this).data('parent_id');
    $name=$(this).data('name');
    $description=$(this).data('description');
    $priority=$(this).data('priority');
    $type=$(this).data('type');
    $cause=$(this).data('cause');
    $latitude=$(this).data('latitude');
    $longitude=$(this).data('longitude');

    $('.edit_id').val($id);
    $('.edit_parent_id').val($parent_id);
    $('.edit_name').val($name);
    $('.edit_description').val($description);
    $('.edit_priority').val($priority);
    $('.edit_type').val($type);
    $('.edit_cause').val($cause);
    $('.edit_latitude').val($latitude);
    $('.edit_longitude').val($longitude);

    if($type == "G"){
        ///////// hide site report fields
        $(".edit_latitude").parent().hide();
        $(".edit_longitude").parent().hide();
        ///////// show general report fields
        $(".edit_cause").parent().show();

        $(".edit_latitude").removeAttr('required');
        $(".edit_longitude").removeAttr('required');
        $(".edit_cause").prop('required',true);

    }
    else if($type == "S"){
        ///////// show general report fields
        $(".edit_cause").parent().hide();
        ///////// hide site report fields
        $(".edit_latitude").parent().show();
        $(".edit_longitude").parent().show();
        ////////// add validation
        $(".edit_cause").removeAttr('required');
        $(".edit_latitude").prop('required',true);
        $(".edit_longitude").prop('required',true);

    }
    $('#editModal').modal('show');

});