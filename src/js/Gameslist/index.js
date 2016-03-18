'use strict';

import {createClass, createFactory, DOM} from 'react';
import assign from 'object-assign';

import {fetchAll, fetchLatest} from './server/fetch';

import GameItem from './components/GameItem';

const [gameItem] = [GameItem].map(createFactory);

const {div, h2} = DOM;

const Gameslist = createClass({

	getInitialState() {
		return {
			latest: []
		}
	},

	componentWillMount() {
		fetchLatest(res => this.setState({
			latest: res
		}));
	},

	render() {
		return div({
			className: 'latest-games'
		},
			h2({
				className: 'page-title'
			}, 'Latest games'),
			this.state.latest.map(g => gameItem(assign(g, {
				key: g.id,
				image: JSON.parse(g.image)
			})))
		);
	}
});

export default Gameslist;
