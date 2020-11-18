function loadSelect2() {
    $('.kt_select2_global, .select2-selection--single').each(function () {
        let options = {
            templateResult:function (item) {
                let $values = $('<span>' + item.text + '</span>');

                if (item.reference !== undefined) {
                    $values = $('<span>' + item.text + ' - ' + item.reference + '</span>');
                }

                if (item.reference !== undefined && item.image !== undefined) {
                    $values = $('<span><img src="' + item.image + '" width="35" /> ' + item.text + ' - ' + item.reference + '</span>');
                }

                return $values
            }
        };

        let autocomplete_url = $(this).data('autocomplete-url');

        if (autocomplete_url) {
            options.ajax = {
                url: autocomplete_url,
                dataType: 'json'
            };
        }

        if ($(this).data('tags') === true) {
            options.tags = true;
        }

        $(this).select2(options);
    });
}

$(document).ready(function() {
    loadSelect2();
});
