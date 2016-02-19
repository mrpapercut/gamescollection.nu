'use strict';

import fetchJsonp from 'fetch-jsonp';
import assign from 'object-assign';
import qs from 'query-string';

import {
	GIANTBOMB_API_KEY,
	GIANTBOMB_API_URL
} from '../constants/creds';

const params = {
	api_key: GIANTBOMB_API_KEY,
	format: 'jsonp',
	resources: 'game'
};

export const fetch = (extraparams, cb) => fetchJsonp([
	GIANTBOMB_API_URL,
	'search/?',
	qs.stringify(assign({}, params, extraparams))
].join(''), {
	jsonpCallback: 'json_callback'
}).then((res, err) => {
	if (err) console.warn(err);
	else return res.json();
}).then(cb);
