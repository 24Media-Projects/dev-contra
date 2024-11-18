import debounce from 'lodash.debounce';

class LiveSearch {

  constructor(props = {}) {

    this.abortFetch = false;

    this.settings = Object.assign({}, {
      minCharacters: Number(BTW.search.min_characters),
      noResultsText: BTW.search.search_no_results_text,
      searchPageBaseUrl: BTW.search.search_base_url,
      viewAllResultsText: BTW.search.search_view_allResults_text,
      action: 'get_live_search_results',
      nonce: BTW.search.nonce,

    }, props);

    // Ensure that any callback could be register alone.
    this.settings.callbacks = Object.assign({}, {
      render: () => { },
      reset: () => { },
      clearResults: () => { },
      beforeRequest: () => { },

    }, props.callbacks || {});


    // If there is no form selector return false
    if (!props.selector || !document.querySelector(props.selector) || !document.querySelector(this.settings.closeSearchContainerSelector)) return false;

    // KeyUp / submit, add a debounce function
    this.debounce = debounce(this.getSearchResults.bind(this), 1000);

    this.fetchData = window.btwFetchData;

    this.$form = document.querySelector(this.settings.selector);
    this.$searchForm = this.$form.querySelector('.search-form');            // The search Form
    this.$searchInput = this.$form.querySelector('.live-search'),             // The input field
      this.$helpTextContainer = this.$form.querySelector('.help_text');

    this.$resultsContainer = document.querySelector('.live-search-results');
    this.$searchResultsWrapOuterContainer = document.querySelector('.live-search-results-wrap--outer-container');
    this.$closeSearchContainer = this.$form.querySelector(this.settings.closeSearchContainerSelector);

     // The previous field value, use to check if the value has changed before running the live search proccess
    this.lastSearchQuery = '';
    
    // We add a nonce to ajax requests
    this.nonce = BTW.search.nonce;  
    
    this.xhr;

    this._bindEvents();

  }

  _bindEvents() {

    this.$searchForm.addEventListener('submit', this.onSubmit.bind(this));
    this.$searchInput.addEventListener('keyup', this.onInputKeyUp.bind(this));
    this.$closeSearchContainer.addEventListener('click', this.reset.bind(this, true));
  }

  reset(full = false) {

    if (full) {
      this.$searchInput.value = '';
      this.lastSearchQuery = '';
      this.debounce.cancel();
    }

    this.$helpTextContainer.classList.remove('active');

    this.$resultsContainer.innerHTML = '';

    this.$searchResultsWrapOuterContainer.classList.remove('loading');
    this.$searchResultsWrapOuterContainer.classList.remove('no-results');
    this.$searchResultsWrapOuterContainer.classList.remove('active');

    this.settings.callbacks.reset.apply(this);

  }


  clearResults() {

    this.abortFetch = true;

    this.$searchInput.value = '';
    this.lastSearchQuery = '';

    this.debounce.cancel();

    this.$helpTextContainer.classList.remove('active');

    this.$resultsContainer.innerHTML = '';

    this.$searchResultsWrapOuterContainer.classList.remove('loading');
    this.$searchResultsWrapOuterContainer.classList.remove('no-results');
    this.$searchResultsWrapOuterContainer.classList.remove('active');

    this.settings.callbacks.clearResults.apply(this);

  }


  // The ajax request proccess.
  // The action is get_search_results and method is GET
  async getSearchResults() {

    this.reset();

    let query = this.$searchInput.value.trim();

    this.settings.callbacks.beforeRequest.apply(this);

    this.$searchResultsWrapOuterContainer.classList.add('active');
    this.$searchResultsWrapOuterContainer.classList.add('loading');

    let response = await this.fetchData('GET', {
      queryStrings: {
        nonce: this.settings.nonce,
        s: query,
        action: this.settings.action,
      },
    });

    this.$searchResultsWrapOuterContainer.classList.remove('loading');

    if (this.abortFetch === true) {
      this.abortFetch = false;
      return false;
    }

    this.lastSearchQuery = query;

    this.renderResults(response);

  }


  renderResults(response) {

    // If error, we return a no results found.
    if (response === false || response.success === false || !response.data.length) {

      this.$searchResultsWrapOuterContainer.classList.add('no-results');
      // this.$searchResultsWrapOuterContainer.classList.add( 'active' );

      this.$resultsContainer.innerHTML += '<p class="result search-no-results">' + this.settings.noResultsText + '</p>';

      // trigger Callback for Render 
      this.settings.callbacks.render.apply(this);

      return false;
    }

    // If success we append the results to the container
    // If resutls are more than 10, show the url of the search query

    // this.$searchResultsWrapOuterContainer.classList.add('active');

    this.$resultsContainer.innerHTML = response.data.join('');

    if (response.more) {
      this.$resultsContainer.innerHTML += '<a class="btn button" href="' + this.settings.searchPageBaseUrl + this.$searchInput.value.trim() + '" >' + this.settings.viewAllResultsText + '</a>';
    }

    // trigger Callback for Render 
    this.settings.callbacks.render.apply(this);

  }

  onSubmit(event) {

    event.preventDefault();

    let key = event.type === 'keyup' ? (event.charCode || event.keyCode) : null,
      query = this.$searchInput.value.trim();

    if (!query || query.length < this.settings.minCharacters) {
      this.$helpTextContainer.classList.add('active');
      return false;
    }

    if (this.lastSearchQuery === query) return false;

    this.debounce();

    if (event.type === 'submit') {
      //Focus out on form submit
      this.$searchInput.blur();
    }

  }

  onInputKeyUp(event){

    this.abortFetch = false;
    this.onSubmit(event);

  }

}

window.LiveSearch = LiveSearch;
