'use strict';

import React, {DOM, createClass, createFactory} from 'react';

const {div, ul, li, img} = DOM;

const Searchresults = createClass({
	render() {
		return div({
			className: 'searchresults'
		},
			ul({},
				this.props.results.map(r => li({
					key: r.id
				},
					r.image && r.image.icon_url ? img({
						src: r.image.icon_url
					}) : null,
					r.name
				))
			)
		);
	}
});

export default Searchresults;
