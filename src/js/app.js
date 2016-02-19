'use strict';

// I hate this about webpack
require('../sass/app.scss');

import React, {DOM, createClass, createFactory} from 'react';
import ReactDOM from 'react-dom';
import debounce from 'debounce';

import GiantbombSearch from './Giantbomb';

const {div} = DOM;

const [giantbombSearch] = [GiantbombSearch].map(createFactory);

const App = createClass({
	render() {
		return div({
			className: 'header'
		},
			giantbombSearch()
		);
	}
});

ReactDOM.render(React.createElement(App), document.getElementById('content'));
