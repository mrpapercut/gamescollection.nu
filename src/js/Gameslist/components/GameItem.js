'use strict';

import React from 'react';

const {div, img, span} = React.DOM;

const GameItem = props => div({
	className: 'game-item',
	'data-id': props.id
},
	img({
		src: props.image.thumb
	}),
	span({
		className: 'game-item-title'
	},
		props.name + ' (' + props.platform + ')'
	)
);

export default GameItem;
