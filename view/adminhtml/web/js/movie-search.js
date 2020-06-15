require([
        'ko',
        'jquery',
        'Magento_Ui/js/modal/confirm',
        'Magento_Ui/js/modal/alert',
    ],
    function(ko, $, confirmation, alert) {

        let SM = {
            SELECTOR_SEARCH_FORM: "form#search-movie",
            SELECTOR_CURRENT_PAGE: "input[name='search[page]']",
            SELECTOR_SELECT_ALL: "#select_all",
            REDIRECT_URL: $('#redirect-url').data('redirectUrl'),
            ENDPOINT_MOVIE_SEARCH_RESULT: $('#endpoint-search-result').data('endpointSearchResult'),
            ENDPOINT_MOVIE_SEARCH_IMPORT: $('#endpoint-movie-search-import').data('endpointMovieSearchImport'),
            current_page: 1,
            last_query: '',
            currentResult: [],

            init: function () {
                SM.screenLoader(true);
                SM.initSearchForm();
                SM.initPageActions();
                SM.initImportBtnAction();
                SM.initSelectAllAction();
            },

            initSearchForm: function()
            {
                SM.screenLoader(false);
                document.querySelector(SM.SELECTOR_SEARCH_FORM)
                    .addEventListener('submit', function(e) {
                        e.preventDefault();
                        try {
                            SM.searchMovie();
                        } catch(error) {
                            console.log(error);
                        }
                    })
                document.querySelector('#search-movie-btn').disabled = false;
            },

            initSelectAllAction: function()
            {
                let searchForm = document.querySelector(SM.SELECTOR_SELECT_ALL);

                searchForm.addEventListener('click', function(el){
                    SMG.selectAllItems(searchForm.checked);
                });
            },

            initImportBtnAction: function()
            {
                $('#import-movies-button').on('click', function (e){
                    e.preventDefault();

                    let lenght = document.querySelectorAll("tbody#movie-results input[type='checkbox']:checked").length;

                    if(!lenght) {
                        alert({
                            title: $.mage.__('Import Movies'),
                            content: $.mage.__('Select at least 1 movie to import'),
                            actions: {
                                always: function(){}
                            }
                        });
                    } else {
                        confirmation({
                            title: 'Import Movies',
                            content: 'Do you want to import ' + lenght + ' movies?',
                            actions: {
                                confirm: function () {
                                    SM.runImport();
                                },
                                cancel: function () {
                                    return false;
                                }
                            }
                        });
                    }
                });
            },

            initPageActions: function()
            {
                /* Next Page btn */
                let nextPageBtn = document.querySelector('#page-next');
                nextPageBtn.addEventListener('click', function(){
                    if(document.querySelector('#page-next').disabled == false) {
                        SM.current_page += 1;
                        SM.updateCurrentPage();
                        SM.searchMovie(SM.current_page);
                    }
                });

                /* Prev Page btn */
                let prevPageBtn = document.querySelector('#page-prev');
                prevPageBtn.addEventListener('click', function(){
                    if(document.querySelector('#page-prev').disabled == false) {
                        SM.current_page -= 1;
                        SM.updateCurrentPage();
                        SM.searchMovie(SM.current_page);
                    }
                });

                /* Page Input */
                let pageInput = document.querySelector(SM.SELECTOR_CURRENT_PAGE);
                pageInput.addEventListener('input', function (evt) {
                    SM.current_page = document.querySelector(SM.SELECTOR_CURRENT_PAGE).value;
                    SM.searchMovie();
                });
            },

            threatFormData: function(formData)
            {
                let newFormData = {};
                $.each(formData, function(index, field) {
                    newFormData[field.name] = field.value;
                });
                return newFormData;
            },

            screenLoader(turnOn = false)
            {
                turnOn = (turnOn === true) ? 'processStart' : 'processStop';
                $('body').trigger(turnOn);
            },

            searchMovie: function()
            {
                let formValues = SM.getSearchFormValues();

                $('.show-with-results').show();

                if(formValues.query != undefined && SM.last_query != formValues.query) {
                    SM.current_page = 1;
                    SM.last_query = formValues.query;
                }

                let endpoint = SM.ENDPOINT_MOVIE_SEARCH_RESULT + SM.getParams('Search');

                formValues.page = SM.current_page;

                console.log(formValues);

                $.ajax({
                    url: endpoint,
                    data: formValues,
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        SM.screenLoader(true);
                        SM.updateElementsBeforeSearch();
                    },
                    success: function(collection) {
                        SM.screenLoader(false);
                        let result = [];

                        for (var key in collection.results) {
                            if (!collection.results.hasOwnProperty(key)) continue;
                            var obj = collection.results[key];
                            result[obj.id] = obj;
                        }

                        if(!result.length) {
                            $('#results-grid').hide();
                        }

                        SM.currentResult = result;
                        SM.updateResults(collection);
                    }
                }).done(function(response){
                    SM.screenLoader(false);
                });

            },

            getSearchFormValues: function()
            {
                let formValues = SM.threatFormData($(SM.SELECTOR_SEARCH_FORM).serializeArray());
                return formValues;
            },

            getSearchFormInputValue: function(inputNameAttr)
            {
                return document.querySelector(SM.SELECTOR_SEARCH_FORM + " " + "input[name='search["+inputNameAttr+"]']").value;
            },

            getSearchFormSelectValue: function(inputNameAttr)
            {
                let selectElement = document.querySelector(SM.SELECTOR_SEARCH_FORM + " " + "select[name='search["+inputNameAttr+"]']");
                return selectElement.options[selectElement.selectedIndex].value;
            },

            getParams: function(type = null)
            {
                type = "LimaMovies" + type;
                let params = {
                    [type]: true,
                    isAjax: true,
                    form_key: window.FORM_KEY,
                };
                return  "?" + $.param(params);
            },

            getCurrentPage: function()
            {
                let pageInput = document.querySelector(SM.SELECTOR_CURRENT_PAGE);
                return (Number.isInteger(pageInput.value) && pageInput.value > 0) ? pageInput.value : 1;
            },

            updateResults: function(collection)
            {
                SM.updatePagination(collection.page, collection.total_pages);
                SM.updateRecordsFound(collection.total_results);
                SMG.updateGridItems(collection);
            },

            updateElementsBeforeSearch: function()
            {
                // Clear Grid Results
                SM.currentResult = [];
                document.querySelector('tbody#movie-results').innerHTML = "";

                // Clear Select All Check
                document.querySelector(SM.SELECTOR_SELECT_ALL).checked = false;

                // Clear limit page input
                SM.updateLimitPage(1);

                // Clear records found span
                SM.updateRecordsFound(0);
            },

            updatePagination: function(currentPage = 1, limitPage = 1)
            {
                // Updating pagination limit and input
                SM.updateCurrentPage(currentPage);
                SM.updateLimitPage(limitPage);

                // Enable or disable pagination buttons
                SM.updateNextPrevBtn('prev', (currentPage > 1));
                SM.updateNextPrevBtn('next', (limitPage > currentPage));
            },

            updateNextPrevBtn: function(type, isActive)
            {
                let selector = "#page-" + type;

                if(isActive) {
                    document.querySelector(selector).removeAttribute("disabled");
                } else {
                    document.querySelector(selector).setAttribute("disabled", "disabled");
                }
            },

            getDataToImport: function()
            {
                let idsSelected = [];
                let movieId;

                document.querySelectorAll(SMG.SELECTOR_ITEMS_CHECKBOX).forEach(function(el) {
                    if(el.checked) {
                        movieId = el.value;
                        movieFullData = SM.currentResult[movieId];

                        movieToImport = {
                            id: movieId,
                            price: SMG.getDefaultValue(movieId, SMG.PREFIX_FIELD.price),
                            stock: SMG.getDefaultValue(movieId, SMG.PREFIX_FIELD.stock),
                            image: movieFullData.poster_path,
                            video: movieFullData.video,
                            title: movieFullData.title,
                            overview: movieFullData.overview,
                            release_date: movieFullData.release_date,
                            adult: movieFullData.adult,
                            attribute_set_id: movieFullData.attribute_set_id,
                            language: movieFullData.original_language
                        }
                        idsSelected.push(movieToImport);
                    }
                });

                return idsSelected;
            },

            runImport: function()
            {
                let endpoint = SM.ENDPOINT_MOVIE_SEARCH_IMPORT + SM.getParams('Import');
                let data = {};
                data.items = SM.getDataToImport();

                if(data.items.length > 0) {
                    $.ajax({
                        url: endpoint,
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        beforeSend: function() {
                            SM.screenLoader(true);
                        },
                        success: function(response) {
                            SM.screenLoader(false);

                            alert({
                                title: $.mage.__('Movies Imported'),
                                content: $.mage.__('You sent ' + response + ' movie' + ((response > 1) ? 's' : '') + ' to the import queue'),
                                actions: {
                                    always: function(){}
                                }
                            });

                            window.location.href = SM.REDIRECT_URL;
                            console.log("redirecting: " + SM.REDIRECT_URL);
                        }
                    }).done(function(response){
                        SM.screenLoader(false);
                        window.location.href = SM.REDIRECT_URL;
                        console.log("redirecting: " + SM.REDIRECT_URL);
                    });
                } else {
                    alert({
                        title: $.mage.__('Error'),
                        content: $.mage.__('Should be more then one movie selected to import'),
                        actions: {
                            always: function(){}
                        }
                    });
                }
            },

            updateLimitPage: function(value)  {
                document.querySelector("#limit-page").innerHTML = value;
            },

            updateCurrentPage: function()  {
                document.querySelector("#page_select").value = SM.current_page;
            },

            updateRecordsFound: function(value)  {
                document.querySelector("#records-found").innerHTML = value;
            },
        };

        let SMG = {

            SELECTOR_ITEMS_CHECKBOX: 'tbody#movie-results input[type="checkbox"]',
            PREFIX_FIELD: {
                'price': 'price_',
                'stock': 'stock_',
                'movie': 'movie_',
            },

            updateGridItems: function (collection) {
                let tableContentElement = document.querySelector('tbody#movie-results ');

                for (let key in collection.results) {
                    if (!collection.results.hasOwnProperty(key)) continue;
                    let obj = collection.results[key];

                    let itemElement = SMG.addGridResultItem(obj);
                    tableContentElement.appendChild(itemElement);
                }
            },

            addGridResultItem: function(item)
            {
                let trElement = document.createElement('tr');

                trElement.appendChild(SMG.createItemElement(item.id, item.id, 'checkbox'));
                trElement.appendChild(SMG.createItemElement(item.poster_path, item.id, 'poster'));
                trElement.appendChild(SMG.createItemElement(item.id, item.id));
                trElement.appendChild(SMG.createItemElement(item.price, item.id, 'price'));
                trElement.appendChild(SMG.createItemElement(item.stock, item.id, 'stock'));
                trElement.appendChild(SMG.createItemElement(item.title, item.id));
                trElement.appendChild(SMG.createItemElement(item.adult, item.id, 'boolean'));
                trElement.appendChild(SMG.createItemElement(item.release_date, item.id));
                trElement.appendChild(SMG.createItemElement(item.overview, item.id));

                return trElement;
            },

            createItemElement: function(value, movieId, customType = false)
            {
                let tdElement = document.createElement('td');
                let divElement = document.createElement('div');
                if(customType) {
                    if('checkbox' == customType) {
                        let inputElement = document.createElement('input');
                        inputElement.type = 'checkbox';
                        inputElement.name = SMG.PREFIX_FIELD.movie + movieId;
                        inputElement.value = movieId;
                        divElement.appendChild(inputElement);

                    } else if  ('price' == customType) {
                        let inputElement = document.createElement('input');
                        inputElement.type = 'text';
                        inputElement.name = SMG.PREFIX_FIELD.price + movieId;
                        inputElement.value = value;
                        divElement.appendChild(inputElement);

                    } else if  ('stock' == customType) {
                        let inputElement = document.createElement('input');
                        inputElement.type = 'text';
                        inputElement.name = SMG.PREFIX_FIELD.stock + movieId;
                        inputElement.value = value;
                        divElement.appendChild(inputElement);

                    } else if  ('poster' == customType) {
                        let imgElement = document.createElement('img');
                        imgElement.src = value;
                        imgElement.style = "width: 55px; height: 80px;";
                        divElement.appendChild(imgElement);
                    } else if  ('boolean' == customType) {
                        divElement.innerHTML = (value === 'false' || value === false) ? $.mage.__('No') : $.mage.__('Yes');

                    }
                } else {
                    divElement.innerHTML = value;
                }

                tdElement.appendChild(divElement);

                return tdElement;
            },

            getDefaultValue: function(movieId, prefix)
            {
                console.log(movieId);
                console.log(prefix);
                let inputName = prefix + movieId;
                let inputSelector = 'input[name="' + inputName + '"]';
                let defaultValue = 0;
                try {
                    let defaultValueInput = document.querySelector(inputSelector);
                    defaultValue = defaultValueInput.value;
                } catch (e) {
                    console.log(e);
                }
                return defaultValue;
            },

            selectAllItems: function(checked)
            {
                document.querySelectorAll(SMG.SELECTOR_ITEMS_CHECKBOX).forEach(function(el) {
                    if(checked) {
                        el.checked = true;
                    } else {
                        el.checked = false;
                    }
                });
            },
        }

        SM.init();
    });
