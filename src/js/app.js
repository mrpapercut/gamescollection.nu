'use strict';

// I hate this about webpack
require('../sass/app.scss');

import React, {DOM, createClass, createFactory} from 'react';
import ReactDOM from 'react-dom';
import debounce from 'debounce';

import GiantbombSearch from './Giantbomb';
import Gameslist from './Gameslist';

const {div} = DOM;

const [giantbombSearch, gameslist] = [GiantbombSearch, Gameslist].map(createFactory);

const App = createClass({
	render() {
		return div({
			className: 'header'
		},
			gameslist()
			/*giantbombSearch(),*/
			/*div({id: 'loading'})*/
		);
	}
});

ReactDOM.render(React.createElement(App), document.getElementById('content'));
