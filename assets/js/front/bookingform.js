jQuery(document).ready(function($){

    $('#rentright_booking_submit').on('click',function(e){

        e.preventDefault();

        $.ajax({
            url: rentright_bookingform_var.ajaxurl,
            type: 'post',
            data: {
                action: 'booking_form',
                nonce: rentright_bookingform_var.nonce,
                name: $('#rentright_name').val(),
                email: $('#rentright_email').val(),
                phone: $('#rentright_phone').val(),
                price: $('#rentright_price').val(),
                location: $('#rentright_location').val(),
                agent: $('#rentright_agent').val(),
            },
            success: function(data){
                $('#rentright_result').html(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });

    });
});