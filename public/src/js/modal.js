$(function() {
    $(".js-btn-modal").on('click', function(){
    	var modalTitle  = $(this).data('modal-title'),
    	    modalBody   = $(this).data('modal-body'),
    	    formSuccess = $(this).data('form-success');
    	    formEdit    = $(this).data('form-edit');
        $('#templateModalLabel').text(modalTitle);
        $.ajax({
            url: modalBody,
            type: 'GET',
            success: function (data) {
                $('#myModal .modal-body .row').html(data);
                $('#myModal').modal('show');
                $("#js-btn-save").attr('data-form-success', formSuccess);
                $("#js-btn-save").attr('data-form-edit', formEdit);
            }
        });
    });

    $("#js-btn-save").on('click', function(e){
    	e.preventDefault();
        var $btn        = $(this),
        	form        = $('#myModal .modal-body form'),
        	url         = form.attr('action'),
        	formData    = form.serialize(),
        	formSuccess = $(this).data('form-success'),
        	formEdit = $(this).data('form-edit');

        $btn.button('loading');

		$.ajax({
			type: "POST",
			url: url,
			data: formData,
			success: function () {
				location.reload();
            },
            error: function(){
                $btn.button('reset');
            },
            complete: function(){
                $btn.button('reset');
            }
		});
    });    
});
