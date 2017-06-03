company = {};

var $companiesTableWrapper = document.getElementById('companies-table-wrapper');

/**
 * Filter the company list.
 *
 * @param input
 */
company.filter = function (input) {
    var query = input.value;
    var filterUrl = input.getAttribute('data-filter-url');

    axios.post(filterUrl, { filter: query })
        .then(function (response) {
            var json = response.data;

            if (json.payload) {
                $companiesTableWrapper.innerHTML = json.payload;
            } else {
                notification.show(json.message, 'danger');
                console.log(json.message);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
};