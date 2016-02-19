'use strict';

import React, {DOM, createClass, createFactory} from 'react';

const {input} = DOM;

const Searchbox = createClass({
	render() {
		return input({
			type: 'text',
			onChange: e => {e.persist(); this.props.onChange(e)},
			className: this.props.searching ? 'searching' : ''
		});
	}
});

export default Searchbox;
