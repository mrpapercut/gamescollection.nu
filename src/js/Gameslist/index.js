'use strict';

import {createClass, DOM} from 'react';

import {fetchAll} from './server/fetch';

const {div} = DOM;

const Gameslist = createClass({

	componentWillMount() {
		fetchAll(res => console.log(res));
	},

	render() {
		return div({

		});
	}
});

export default Gameslist;
