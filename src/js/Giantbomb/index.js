'use strict';

import React, {DOM, createClass, createFactory} from 'react';
import debounce from 'debounce';

import Searchbox from './components/Searchbox';
import Searchresults from './components/Searchresults';
import {fetch} from './server/fetch';

const [searchbox, searchresults] = [Searchbox, Searchresults].map(createFactory);

const {div, input, ul, li} = DOM;

const GiantbombSearch = createClass({
	getInitialState() {
		return {
			searching: false,
			results: []
		};
	},

	componentWillMount() {
		this.debounceFetch = debounce(this.fetch, 400);
	},

	fetch(e) {
		const {value} = e.target;

		if (value < 3) return false;

		this.setState({
			searching: true
		});

		fetch({
			query: value
		}, json => {
			this.setState({
				searching: false,
				results: json.results
			});
		});
	},

	render() {
		return div({},
			searchbox({
				onChange: this.debounceFetch,
				searching: this.state.searching
			}),
			searchresults({
				results: this.state.results
			})
		);
	}
});

export default GiantbombSearch;
