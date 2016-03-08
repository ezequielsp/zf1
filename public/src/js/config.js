$(function(){
    $('*[name=jsdatetime]').appendDtpicker({
        "locale": "br",
        "dateFormat": "DD-MM-YYYY h:mm",
        "closeOnSelected": true
    });

    $(".js-example-basic-multiple").select2();

    function cityFormatResult(data)
    {
        if (!data.id)
            return data.city; // optgroup
        
        if (!data.city_img)
            return data.city;
        
        return "<img class='flag' src='/upload/cities/" + data.city_img + "' style='max-width:30px; max-height:30px;'/> " + data.city;
    }

    function cityFormatSelection(data)
    {
        return data.championship;
    }

    $("#city_id").select2({
        placeholder: "Selecione uma cidade",
        minimumInputLength: 2,
        ajax: {
            url: '/admin/api/search',
            dataType: 'json',
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {
                    param: 'city',
                    state: $("#state :selected").val(),
                    q: term, //search term
                    page: page // page number
                };
            },
            results: function (data, page) {
                var more = (page * 15) < data.length; // whether or not there are more results available

                // notice we return the value of more so Select2 knows if more results can be loaded
                return {results: data, more: more};
            }
        },
        formatResult: cityFormatResult, // omitted for brevity, see the source of this page
        formatSelection: cityFormatSelection, // omitted for brevity, see the source of this page
        dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
        escapeMarkup: function (m) {
            return m;
        } // we do not want to escape markup since we are displaying html in results
    });

    $(".js-data-example-ajax").select2({
  ajax: {
    url: "https://api.github.com/search/repositories",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo, // omitted for brevity, see the source of this page
  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
});
});