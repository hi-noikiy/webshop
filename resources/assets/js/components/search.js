search = {};

var $suggestBox = document.getElementById('suggest-box');

search.suggest = function ($target) {
    event.preventDefault();

    $suggestBox.style.display = 'none';

    var suggestUrl = $target.getAttribute('data-suggest-url');
    var query = $target.value;

    setTimeout(function () {
        if (query === $target.value) {
            axios.post(suggestUrl, {
                q: query
            })
                .then(function (response) {
                    if (response.data.success) {
                        $suggestBox.innerHTML = response.data.suggestBoxHtml;
                        $suggestBox.style.display = 'block';
                    } else {
                        console.log(response.data.message);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }, 1000);
};

search.hideSuggest = function () {
    $suggestBox.style.display = 'none';
};